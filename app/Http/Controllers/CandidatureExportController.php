<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\PostuleOffre;
use App\Models\QuestionFormulaire;
use App\Models\ReponseFormulaire;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CandidatureExportController extends Controller
{
    public function exportExcel($offreId, Request $request)
    {
        try {
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 120);

            $offre = Offre::with([
                'postuleOffre' => function($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'postuleOffre.enqueteur',
                'postuleOffre.reponseFormulaire.questionFormulaire',
                'postuleOffre.reponseFormulaire.region',
                'postuleOffre.reponseFormulaire.district',
                'postuleOffre.reponseFormulaire.commune',
                'questionFormulaire' => function($query) {
                    $query->orderBy('id', 'asc');
                }
            ])->findOrFail($offreId);

            $candidatures = $offre->postuleOffre;

            if ($request->has('search') && !empty($request->search)) {
                $search = strtolower(trim($request->search));
                $candidatures = $candidatures->filter(function ($candidature) use ($search) {
                    return str_contains(strtolower($candidature->enqueteur->nom ?? ''), $search) ||
                           str_contains(strtolower($candidature->enqueteur->email ?? ''), $search) ||
                           str_contains(strtolower($candidature->type_enqueteur ?? ''), $search);
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $candidatures = $candidatures->where('status_postule', $request->status);
            }

            return new StreamedResponse(function() use ($offre, $candidatures) {
                // Créer le fichier Excel
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Titre de l'onglet (limité à 31 caractères)
                $sheetTitle = substr($offre->nom_enquete, 0, 31);
                $sheetTitle = preg_replace('/[^A-Za-z0-9 _-]/', '', $sheetTitle); // Nettoyer les caractères spéciaux
                $sheet->setTitle($sheetTitle);

                // En-tête principal
                $sheet->setCellValue('A1', 'Export des candidatures - ' . $offre->nom_enquete);
                $lastColumn = $this->getColumnLetter(5 + $offre->questionFormulaire->count());
                $sheet->mergeCells('A1:' . $lastColumn . '1');

                // Style de l'en-tête principal
                $headerStyle = $sheet->getStyle('A1:' . $lastColumn . '1');
                $headerStyle->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('FFFFFF');
                $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4472C4');
                $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Informations générales
                $sheet->setCellValue('A3', 'Date d\'export: ' . Carbon::now()->format('d/m/Y H:i:s'));
                $sheet->setCellValue('A4', 'Nombre de candidatures: ' . $candidatures->count());
                $sheet->setCellValue('A5', 'Statut de l\'offre: ' . ucfirst(str_replace('_', ' ', $offre->status_offre)));

                // Préparer les en-têtes des colonnes
                $questions = $offre->questionFormulaire;
                $headers = [
                    'Enquêteur',
                    'Email',
                    'Type Enquêteur',
                    'Statut Candidature',
                    'Date Candidature'
                ];

                // Ajouter les questions comme en-têtes
                foreach ($questions as $question) {
                    $headers[] = $this->cleanHeaderText($question->label);
                }

                // Écrire les en-têtes dans la ligne 7
                $headerRow = 7;
                foreach ($headers as $index => $header) {
                    $col = $index + 1;
                    $cellCoordinate = $this->getColumnLetter($col) . $headerRow;
                    $sheet->setCellValue($cellCoordinate, $header);

                    // Style des en-têtes
                    $cellStyle = $sheet->getStyle($cellCoordinate);
                    $cellStyle->getFont()->setBold(true);
                    $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E2F3');
                    $cellStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Ajuster la largeur des colonnes
                    $sheet->getColumnDimension($this->getColumnLetter($col))->setAutoSize(true);
                }

                // Remplir les données
                $row = $headerRow + 1;
                foreach ($candidatures as $candidature) {
                    $col = 1;

                    // Données de base
                    $sheet->setCellValue($this->getColumnLetter($col++) . $row, $candidature->enqueteur ? $candidature->enqueteur->nom : 'Inconnu');
                    $sheet->setCellValue($this->getColumnLetter($col++) . $row, $candidature->enqueteur ? $candidature->enqueteur->email : 'N/A');
                    $sheet->setCellValue($this->getColumnLetter($col++) . $row, ucfirst($candidature->type_enqueteur ?? 'N/A'));
                    $sheet->setCellValue($this->getColumnLetter($col++) . $row, ucfirst(str_replace('_', ' ', $candidature->status_postule ?? 'N/A')));
                    $sheet->setCellValue($this->getColumnLetter($col++) . $row,
                        $candidature->date_postule ? Carbon::parse($candidature->date_postule)->format('d/m/Y H:i') : 'N/A'
                    );

                    // Réponses aux questions
                    foreach ($questions as $question) {
                        $reponse = $candidature->reponseFormulaire->where('question_id', $question->id)->first();
                        $cellValue = $this->formatResponseValue($reponse, $question);
                        $sheet->setCellValue($this->getColumnLetter($col++) . $row, $cellValue);
                    }

                    // Alternance de couleur pour les lignes
                    if ($row % 2 == 0) {
                        $rowRange = 'A' . $row . ':' . $this->getColumnLetter($col - 1) . $row;
                        $sheet->getStyle($rowRange)->getFill()
                              ->setFillType(Fill::FILL_SOLID)
                              ->getStartColor()->setRGB('F8F9FA');
                    }

                    $row++;
                }

                // Ajouter des bordures au tableau
                if ($row > $headerRow + 1) {
                    $tableRange = 'A' . $headerRow . ':' . $this->getColumnLetter(count($headers)) . ($row - 1);
                    $sheet->getStyle($tableRange)->getBorders()->getAllBorders()
                          ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }

                // Figer les volets
                $sheet->freezePane('A' . ($headerRow + 1));

                // Générer le fichier
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');

                // Libérer la mémoire
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $this->generateFileName($offre) . '"',
                'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'exportation Excel : ', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'offre_id' => $offreId
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'exportation : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formatage des valeurs de réponse selon le type de question
     */
    private function formatResponseValue($reponse, $question)
    {
        if (!$reponse) {
            return 'Pas de réponse';
        }

        switch ($question->type) {
            case 'geographique':
                $geoData = [];
                if ($reponse->region) $geoData[] = 'Région: ' . $reponse->region->region;
                if ($reponse->district) $geoData[] = 'District: ' . $reponse->district->district;
                if ($reponse->commune) $geoData[] = 'Commune: ' . $reponse->commune->commune;
                return implode(' | ', $geoData) ?: 'Pas de réponse géographique';

            case 'image':
                return $reponse->fichier_path ?
                    'Image: ' . url('/storage/' . $reponse->fichier_path) :
                    'Aucune image';

            case 'fichier':
                return $reponse->fichier_path ?
                    'Fichier: ' . url('/storage/' . $reponse->fichier_path) :
                    'Aucun fichier';

            default:
                return $reponse->valeur ?: 'Pas de réponse';
        }
    }

    /**
     * Nettoyer le texte des en-têtes
     */
    private function cleanHeaderText($text)
    {
        // Supprimer les balises HTML et limiter la longueur
        $cleaned = strip_tags($text);
        $cleaned = html_entity_decode($cleaned);
        return mb_substr($cleaned, 0, 50); // Limiter à 50 caractères
    }

    /**
     * Générer un nom de fichier sécurisé
     */
    private function generateFileName($offre)
    {
        $baseName = preg_replace('/[^A-Za-z0-9_-]/', '_', $offre->nom_enquete);
        $baseName = substr($baseName, 0, 50); // Limiter la longueur
        return 'candidatures_' . $baseName . '_' . date('Y-m-d_H-i-s') . '.xlsx';
    }

    /**
     * Convertir un index de colonne en lettre Excel
     */
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

    public function searchCandidatures($offreId, Request $request)
    {
        try {
            // Validation des paramètres
            $request->validate([
                'search' => 'nullable|string|max:255',
                'status' => 'nullable|string|in:en_attente,accepte,rejete',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            $offre = Offre::with([
                'postuleOffre.enqueteur',
                'postuleOffre.reponseFormulaire.questionFormulaire',
                'postuleOffre.reponseFormulaire.region',
                'postuleOffre.reponseFormulaire.district',
                'postuleOffre.reponseFormulaire.commune'
            ])->findOrFail($offreId);

            $candidatures = $offre->postuleOffre;

            // Filtrage par recherche textuelle
            if ($request->filled('search')) {
                $search = strtolower(trim($request->search));
                $candidatures = $candidatures->filter(function ($candidature) use ($search) {
                    return str_contains(strtolower($candidature->enqueteur->nom ?? ''), $search) ||
                           str_contains(strtolower($candidature->enqueteur->email ?? ''), $search) ||
                           str_contains(strtolower($candidature->type_enqueteur ?? ''), $search);
                });
            }

            // Filtrage par statut
            if ($request->filled('status')) {
                $candidatures = $candidatures->where('status_postule', $request->status);
            }

            // Pagination optionnelle
            $perPage = $request->get('per_page', 50);
            $currentPage = $request->get('page', 1);
            $total = $candidatures->count();

            if ($perPage > 0) {
                $items = $candidatures->forPage($currentPage, $perPage)->values();
            } else {
                $items = $candidatures->values();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'candidatures' => $items,
                    'pagination' => [
                        'current_page' => $currentPage,
                        'per_page' => $perPage,
                        'total' => $total,
                        'last_page' => $perPage > 0 ? ceil($total / $perPage) : 1
                    ]
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Offre non trouvée'
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Paramètres invalides',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Erreur recherche candidatures: ' . $e->getMessage(), [
                'offre_id' => $offreId,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche'
            ], 500);
        }
    }

    // Méthodes CRUD vides à conserver
    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
