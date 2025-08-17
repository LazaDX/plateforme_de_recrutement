<?php

namespace App\Services;

use App\Models\Offre;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class ExcelExportService
{
    public function exportCandidatures(Offre $offre, array $filters = [])
    {
        // Charger les données avec les relations nécessaires
        $offre->load([
            'postuleOffre.enqueteur',
            'postuleOffre.reponseFormulaire.questionFormulaire',
            'postuleOffre.reponseFormulaire.region',
            'postuleOffre.reponseFormulaire.district',
            'postuleOffre.reponseFormulaire.commune',
            'questionFormulaire'
        ]);

        // Filtrer les candidatures
        $candidatures = $this->filterCandidatures($offre->postuleOffre, $filters);

        // Créer le fichier Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Configuration de base
        $this->setupWorksheet($sheet, $offre);

        // En-têtes et données
        $this->addHeaders($sheet, $offre->questionFormulaire);
        $this->addData($sheet, $candidatures, $offre->questionFormulaire);

        // Styles et mise en forme
        $this->applyStyles($sheet, $candidatures->count() + 6, $offre->questionFormulaire->count() + 5);

        return $spreadsheet;
    }

    private function filterCandidatures($candidatures, array $filters)
    {
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $candidatures = $candidatures->filter(function ($candidature) use ($search) {
                return str_contains(strtolower($candidature->enqueteur->nom), $search) ||
                       str_contains(strtolower($candidature->enqueteur->email), $search) ||
                       str_contains(strtolower($candidature->type_enqueteur), $search);
            });
        }

        if (!empty($filters['status'])) {
            $candidatures = $candidatures->where('status_postule', $filters['status']);
        }

        return $candidatures;
    }

    private function setupWorksheet($sheet, $offre)
    {
        $sheet->setTitle($this->sanitizeSheetTitle('Candidatures - ' . $offre->nom_enquete));

        // En-tête principal
        $sheet->setCellValue('A1', 'Export des candidatures - ' . $offre->nom_enquete);
        $sheet->mergeCells('A1:Z1');

        // Informations générales
        $sheet->setCellValue('A3', 'Date d\'export: ' . Carbon::now()->format('d/m/Y H:i'));
        $sheet->setCellValue('A4', 'Nombre total de candidatures: ' . $offre->postuleOffre->count());
        $sheet->setCellValue('A5', 'Offre créée le: ' . ($offre->created_at ? $offre->created_at->format('d/m/Y') : 'Non défini'));
    }

    private function addHeaders($sheet, $questions)
    {
        $headers = [
            'ID',
            'Enquêteur',
            'Email',
            'Type',
            'Statut',
            'Date candidature'
        ];

        // Ajouter les questions comme colonnes
        foreach ($questions->sortBy('id') as $question) {
            $headers[] = $this->truncateText($question->label, 50);
        }

        $headerRow = 7;
        foreach ($headers as $index => $header) {
            $cellCoordinate = $this->getColumnLetter($index + 1) . $headerRow;
            $sheet->setCellValue($cellCoordinate, $header);
            $sheet->getColumnDimension($this->getColumnLetter($index + 1))->setAutoSize(true);
        }
    }

    private function addData($sheet, $candidatures, $questions)
    {
        $row = 8; // Commencer après les en-têtes

        foreach ($candidatures as $candidature) {
            $col = 1;

            // Données de base
            $sheet->setCellValue($this->getColumnLetter($col++) . $row, $candidature->id);
            $sheet->setCellValue($this->getColumnLetter($col++) . $row, $candidature->enqueteur->nom);
            $sheet->setCellValue($this->getColumnLetter($col++) . $row, $candidature->enqueteur->email);
            $sheet->setCellValue($this->getColumnLetter($col++) . $row, ucfirst($candidature->type_enqueteur));
            $sheet->setCellValue($this->getColumnLetter($col++) . $row, $this->formatStatus($candidature->status_postule));
            $sheet->setCellValue($this->getColumnLetter($col++) . $row, Carbon::parse($candidature->date_postule)->format('d/m/Y H:i'));

            // Réponses aux questions
            foreach ($questions->sortBy('id') as $question) {
                $cellValue = $this->getResponseValue($candidature, $question);
                $sheet->setCellValue($this->getColumnLetter($col++) . $row, $cellValue);
            }

            $row++;
        }
    }

    private function getResponseValue($candidature, $question)
    {
        $reponse = $candidature->reponseFormulaire->where('question_id', $question->id)->first();

        if (!$reponse) {
            return 'Pas de réponse';
        }

        switch ($question->type) {
            case 'geographique':
                $geoData = [];
                if ($reponse->region) $geoData[] = 'Région: ' . $reponse->region->region;
                if ($reponse->district) $geoData[] = 'District: ' . $reponse->district->district;
                if ($reponse->commune) $geoData[] = 'Commune: ' . $reponse->commune->commune;
                return implode(' | ', $geoData) ?: 'Pas de localisation';

            case 'image':
                return $reponse->fichier_path ?
                    'Image: ' . url('/storage/' . $reponse->fichier_path) :
                    'Aucune image';

            case 'fichier':
                return $reponse->fichier_path ?
                    'Fichier: ' . url('/storage/' . $reponse->fichier_path) :
                    'Aucun fichier';

            case 'choix_multiple':
                // Si c'est un choix multiple, on peut avoir plusieurs valeurs séparées
                return $reponse->valeur ? str_replace('|', ', ', $reponse->valeur) : 'Pas de réponse';

            default:
                return $reponse->valeur ?: 'Pas de réponse';
        }
    }

    private function applyStyles($sheet, $lastRow, $lastCol)
    {
        // Style de l'en-tête principal
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ];
        $sheet->getStyle('A1')->applyFromArray($headerStyle);

        // Style des en-têtes de colonnes
        $columnHeaderStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E2F3']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT
            ]
        ];
        $sheet->getStyle('A7:' . $this->getColumnLetter($lastCol) . '7')->applyFromArray($columnHeaderStyle);

        // Bordures pour le tableau de données
        $tableStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle('A7:' . $this->getColumnLetter($lastCol) . $lastRow)->applyFromArray($tableStyle);

        // Lignes alternées
        for ($row = 8; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $alternateStyle = [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA']
                    ]
                ];
                $sheet->getStyle('A' . $row . ':' . $this->getColumnLetter($lastCol) . $row)->applyFromArray($alternateStyle);
            }
        }

        // Ajuster la largeur des colonnes
        for ($col = 1; $col <= $lastCol; $col++) {
            $sheet->getColumnDimension($this->getColumnLetter($col))->setAutoSize(true);
        }
    }

    private function getColumnLetter($columnIndex)
    {
        $letter = '';
        while ($columnIndex > 0) {
            $columnIndex--;
            $letter = chr(65 + ($columnIndex % 26)) . $letter;
            $columnIndex = intval($columnIndex / 26);
        }
        return $letter;
    }

    private function sanitizeSheetTitle($title)
    {
        // Remplacer les caractères invalides pour les noms de feuille Excel
        $title = preg_replace('/[\\/:*?"<>|]/', '_', $title);
        return substr($title, 0, 31); // Limite de 31 caractères pour Excel
    }

    private function truncateText($text, $maxLength)
    {
        return strlen($text) > $maxLength ? substr($text, 0, $maxLength - 3) . '...' : $text;
    }

    private function formatStatus($status)
    {
        $statusMap = [
            'en_attente' => 'En attente',
            'accepte' => 'Accepté',
            'rejete' => 'Rejeté'
        ];

        return $statusMap[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    public function generateFileName($offre)
    {
        $safeName = preg_replace('/[^A-Za-z0-9\-_]/', '_', $offre->nom_enquete);
        return 'candidatures_' . $safeName . '_' . date('Y-m-d_H-i-s') . '.xlsx';
    }
}
