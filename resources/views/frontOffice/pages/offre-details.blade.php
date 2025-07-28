@extends('frontOffice.layouts.app')

@section('title', "Offre d'enquête")

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Bannière sobre -->
        <div class="bg-gradient-to-tr from-gray-700 to-gray-500 text-white">
            <div class="max-w-6xl mx-auto px-4 py-8">
                <div class="mb-2 text-sm font-light flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Institut Statistique de Madagascar
                </div>
                <h1 class="text-3xl font-bold mb-4">{{ $offre->nom_enquete }}</h1>
                <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ $offre->region->nom_region ?? 'Toutes régions' }}
                    </div>
                    <div class="flex items-center">
                        <i class="far fa-calendar-alt mr-2"></i>
                        Début :
                        {{ optional($offre->created_at)->format('d/m/Y') ?? 'Non défini' }}
                    </div>
                    <div class="flex items-center">
                        <i class="far fa-clock mr-2"></i>
                        Clôture :
                        {{ optional($offre->date_limite)->format('d/m/Y') ?? 'Non défini' }}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Priorité :
                        <span
                            class="ml-1 px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $offre->priorite === 'haute'
                            ? 'bg-red-100 text-red-800'
                            : ($offre->priorite === 'normal'
                                ? 'bg-yellow-100 text-yellow-800'
                                : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($offre->priorite) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <main class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Détails de l'enquête -->
                <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200 flex items-center">
                        <i class="fas fa-info-circle text-blue-900 mr-2"></i>
                        Détails de l'enquête
                    </h2>

                    <div class="prose prose-sm max-w-none text-gray-700">
                        <div class="whitespace-pre-line mb-8 space-y-4">
                            {!! nl2br(e($offre->details_enquete)) !!}
                        </div>
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Résumé de l'offre -->
                <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="far fa-calendar-check text-blue-800 mr-2"></i>
                        Calendrier
                    </h2>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-play text-blue-800 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-sm text-gray-500">Date de début</p>
                                <p class="font-medium">
                                    {{ optional($offre->created_at)->format('d/m/Y H:i') ?? 'Non défini' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <i class="fas fa-flag-checkered text-blue-800 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-sm text-gray-500">Date limite</p>
                                <p class="font-medium">
                                    {{ optional($offre->deadline)->format('d/m/Y H:i') ?? 'Non défini' }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-3 mt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium">Progression</span>
                                <span class="text-sm text-gray-500">
                                    @php
                                        $daysTotal = \Carbon\Carbon::parse($offre->date_limite)->diffInDays(now());
                                        $daysPassed = min(
                                            $daysTotal,
                                            \Carbon\Carbon::parse($offre->created_at)->diffInDays(now()),
                                        );
                                        $percentage = $daysTotal > 0 ? round(($daysPassed / $daysTotal) * 100) : 100;
                                    @endphp
                                    {{ $percentage }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Statut -->
                <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tasks text-blue-800 mr-2"></i>
                        Statut de l'enquête
                    </h2>

                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Statut actuel</p>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium
                            {{ $offre->status_offre === 'actif'
                                ? 'bg-green-100 text-green-800'
                                : ($offre->status_offre === 'en_attente'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $offre->status_offre)) }}
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Actions -->
                <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button id="openModalBtn"
                            class="block w-full bg-blue-800 hover:bg-blue-900 text-white py-2 px-4 rounded-md text-sm font-medium text-center transition-colors">
                            Postuler maintenant
                        </button>
                    </div>
                </section>

                <!-- Modal -->
                <div id="applicationModal"
                    class="fixed inset-0 z-50 bg-black bg-opacity-20 hidden flex justify-center items-center p-4">
                    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-xl">
                        <!-- En-tête du modal -->
                        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center text-gray-600">
                            <h2 class="text-xl font-bold">
                                <i class="fas fa-file-alt mr-2"></i>
                                Formulaire de candidature
                            </h2>
                            <button id="closeModalBtn" class="text-gray-800 hover:text-gray-900">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Corps du modal -->
                        <div class="p-6">
                            <form id="applicationForm" action="{{ route('offres.postuler', $offre->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="offre_id" value="{{ $offre->id }}">

                                <!-- Questions dynamiques -->
                                @foreach ($offre->questionFormulaire as $question)
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $question->label }}
                                            @if ($question->required)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>

                                        @if ($question->type === 'texte')
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                    <i class="fas fa-font"></i>
                                                </div>
                                                <input type="text" name="reponses[{{ $question->id }}]"
                                                    class="pl-10 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                                                    @if ($question->required) required @endif>
                                            </div>
                                        @elseif($question->type === 'long_texte')
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 pt-2 flex items-start pointer-events-none text-gray-400">
                                                    <i class="fas fa-align-left"></i>
                                                </div>
                                                <textarea name="reponses[{{ $question->id }}]"
                                                    class="pl-10 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500" rows="3"
                                                    @if ($question->required) required @endif></textarea>
                                            </div>
                                        @elseif($question->type === 'nombre')
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 pt-2 flex items-start pointer-events-none text-gray-400">
                                                    <i class="fas fa-align-left"></i>
                                                </div>
                                              <input type="number" name="reponses[{{ $question->id }}]"
                                                    class="pl-10 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                                                    @if ($question->required) required @endif>
                                            </div>
                                        @elseif($question->type === 'email')
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 pt-2 flex items-start pointer-events-none text-gray-400">
                                                    <i class="fas fa-align-left"></i>
                                                </div>
                                              <input type="email" name="reponses[{{ $question->id }}]"
                                                    class="pl-10 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                                                    @if ($question->required) required @endif>
                                            </div>
                                        @elseif($question->type === 'choix_multiple')
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                    <i class="fas fa-list-ul"></i>
                                                </div>
                                                <select name="reponses[{{ $question->id }}]"
                                                    class="pl-10 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
                                                    @if ($question->required) required @endif>
                                                    <option value="">Sélectionnez une option</option>
                                                    @foreach (explode(',', $question->options) as $option)
                                                        <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @elseif($question->type === 'case_cocher')
                                            <div class="flex items-center">
                                                <input type="checkbox" name="reponses[{{ $question->id }}]"
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                    @if ($question->required) required @endif>
                                                <label class="ml-2 block text-sm text-gray-700">
                                                    {{ $question->options ?: 'Je confirme' }}
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                <!-- Pied du modal -->
                                <div class="border-t border-gray-200 pt-4 mt-6 flex justify-end space-x-3">
                                    <button type="button" id="cancelBtn"
                                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-times mr-2"></i> Annuler
                                    </button>
                                    <button type="submit" 
                                        class="px-4 py-2 bg-blue-800 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-paper-plane mr-2"></i> Envoyer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                
                const openModalBtn = document.getElementById('openModalBtn');
                const closeModalBtn = document.getElementById('closeModalBtn');
                const cancelBtn = document.getElementById('cancelBtn');
                

                // Gestion de l'ouverture du modal
                openModalBtn.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });

                // Gestion de la fermeture du modal
                function closeModal() {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

                closeModalBtn.addEventListener('click', closeModal);
                cancelBtn.addEventListener('click', closeModal);

                // Fermer le modal en cliquant à l'extérieur
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });

                // // Soumission du formulaire
                // applicationForm.addEventListener('submit', function(e) {
                //     e.preventDefault();

                //     // Afficher l'indicateur de chargement
                //     // submitText.classList.add('hidden');
                //     // submitLoading.classList.remove('hidden');
                //     // submitBtn.disabled = true;
                // });
            });
        </script>
    </div>
@endsection
