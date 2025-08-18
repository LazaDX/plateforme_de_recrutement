<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponses de {{ $enqueteur->nom }}</title>
    <style>
        @page {
            margin: 2cm;

            @bottom-right {
                content: "Page " counter(page) " sur " counter(pages);
                font-size: 10px;
                color: #666;
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #000;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 3px 0;
            font-weight: bold;
        }

        .info-section {
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            padding: 5px 0;
            vertical-align: top;
            border-bottom: 1px solid #eee;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
        }

        .responses-section {
            margin-top: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
        }

        .response-item {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .question-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .question-icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            font-weight: bold;
        }

        .question-title {
            font-weight: bold;
            font-size: 13px;
            flex-grow: 1;
        }

        .required-badge {
            font-size: 9px;
            padding: 2px 5px;
            border-radius: 2px;
            margin-left: 10px;
            background-color: #000;
            color: white;
            font-weight: bold;
        }

        .response-content {
            margin-left: 28px;
            padding: 8px;
            border-left: 2px solid #000;
        }

        .response-text {
            word-wrap: break-word;
        }

        .response-empty {
            color: #666;
            font-style: italic;
        }

        .geo-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .geo-row {
            display: table-row;
        }

        .geo-cell {
            display: table-cell;
            padding: 3px 0;
            vertical-align: top;
        }

        .geo-label {
            font-weight: bold;
            width: 80px;
        }

        .response-image {
            max-width: 100%;
            max-height: 250px;
            margin: 8px 0;
            border: 1px solid #ddd;
        }

        .image-info {
            font-size: 10px;
            margin-top: 3px;
        }

        .file-info {
            display: flex;
            align-items: center;
            padding: 5px 0;
        }

        .file-icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            font-weight: bold;
        }

        .file-details {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        /* Icônes simplifiées */
        .icon-text:before {
            content: "Q:";
            font-weight: bold;
        }

        .icon-email:before {
            content: "@";
            font-weight: bold;
        }

        .icon-long-text:before {
            content: "¶";
            font-weight: bold;
        }

        .icon-number:before {
            content: "#";
            font-weight: bold;
        }

        .icon-list:before {
            content: "→";
            font-weight: bold;
        }

        .icon-multiple:before {
            content: "✓";
            font-weight: bold;
        }

        .icon-image:before {
            content: "IMG";
            font-weight: bold;
            font-size: 10px;
        }

        .icon-file:before {
            content: "PDF";
            font-weight: bold;
            font-size: 10px;
        }

        .icon-geo:before {
            content: "📍";
        }

        .icon-default:before {
            content: "?";
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- En-tête -->
    <div class="header">
        <h1>Réponses du Candidat</h1>
        <p>{{ $enqueteur->nom }}</p>
        <p>{{ $enqueteur->email }}</p>
        <p>Enquête: {{ $offre->nom_enquete }}</p>
    </div>

    <!-- Informations de la candidature -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">Date de candidature:</div>
                <div class="info-cell">
                    {{ \Carbon\Carbon::parse($candidature->date_postule)->format('d/m/Y à H:i') }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Type d'enquêteur:</div>
                <div class="info-cell">{{ ucfirst($candidature->type_enqueteur) }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Statut:</div>
                <div class="info-cell">
                    <span class="status-badge">
                        {{ ucfirst(str_replace('_', ' ', $candidature->status_postule)) }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">Téléphone:</div>
                <div class="info-cell">{{ $enqueteur->telephone ?? 'Non renseigné' }}</div>
            </div>
        </div>
    </div>

    <!-- Section des réponses -->
    <div class="responses-section">
        <h2 class="section-title">Réponses au formulaire ({{ is_countable($responses) ? count($responses) : 0 }}
            questions)</h2>

        @forelse($responses as $index => $response)
            <div class="response-item">
                <div class="question-header">
                    <span
                        class="question-icon
                        @switch($response->questionFormulaire?->type)
                            @case('texte') icon-text @break
                            @case('email') icon-email @break
                            @case('long_texte') icon-long-text @break
                            @case('nombre') icon-number @break
                            @case('liste') icon-list @break
                            @case('choix_multiple') icon-multiple @break
                            @case('champ_multiple') icon-multiple @break
                            @case('image') icon-image @break
                            @case('fichier') icon-file @break
                            @case('geographique') icon-geo @break
                            @default icon-default
                        @endswitch
                    "></span>
                    <span class="question-title">
                        {{ $response->questionFormulaire?->label ?? 'Question supprimée' }}
                    </span>
                    @if ($response->questionFormulaire?->obligation)
                        <span class="required-badge">Obligatoire</span>
                    @endif
                </div>

                <div class="response-content">
                    @switch($response->questionFormulaire?->type)
                        @case('geographique')
                            <div class="geo-grid">
                                @if ($response->region)
                                    <div class="geo-row">
                                        <div class="geo-cell geo-label">Région:</div>
                                        <div class="geo-cell">{{ $response->region->region }}</div>
                                    </div>
                                @endif
                                @if ($response->district)
                                    <div class="geo-row">
                                        <div class="geo-cell geo-label">District:</div>
                                        <div class="geo-cell">{{ $response->district->district }}</div>
                                    </div>
                                @endif
                                @if ($response->commune)
                                    <div class="geo-row">
                                        <div class="geo-cell geo-label">Commune:</div>
                                        <div class="geo-cell">{{ $response->commune->commune }}</div>
                                    </div>
                                @endif
                                @if (!$response->region && !$response->district && !$response->commune)
                                    <div class="response-empty">Aucune localisation renseignée</div>
                                @endif
                            </div>
                        @break

                        @case('image')
                            @if ($response->fichier_path && file_exists(storage_path('app/public/' . $response->fichier_path)))
                                <img src="{{ storage_path('app/public/' . $response->fichier_path) }}"
                                    alt="{{ $response->questionFormulaire->label }}" class="response-image">
                                <div class="image-info">Image: {{ basename($response->fichier_path) }}</div>
                            @else
                                <div class="response-empty">Aucune image téléchargée</div>
                            @endif
                        @break

                        @case('fichier')
                            @if ($response->fichier_path)
                                <div class="file-info">
                                    <span class="file-icon"></span>
                                    <div class="file-details">
                                        Fichier: {{ basename($response->fichier_path) }}
                                    </div>
                                </div>
                            @else
                                <div class="response-empty">Aucun fichier téléchargé</div>
                            @endif
                        @break

                        @default
                            @if ($response->valeur)
                                <div class="response-text">{!! nl2br(e($response->valeur)) !!}</div>
                            @else
                                <div class="response-empty">Aucune réponse</div>
                            @endif
                    @endswitch
                </div>
            </div>

            @if (($index + 1) % 5 === 0 && $index + 1 < is_countable($responses) ? count($responses) : 0)
                <div class="page-break"></div>
            @endif
            @empty
                <div class="response-item">
                    <div class="response-empty" style="text-align: center; padding: 30px;">
                        Aucune réponse trouvée pour cette candidature.
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>Document généré le {{ $generated_at }}</p>
            <p>Système de gestion des enquêtes</p>
        </div>
    </body>

    </html>
