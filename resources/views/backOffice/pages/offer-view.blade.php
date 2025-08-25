@extends('backOffice.layouts.admin')

@section('title', 'Candidatures - ' . $offre->nom_enquete)

@section('content')
    <div id="app" class="container mx-auto p-4">
        <div class="flex flex-col justify-between mb-8">
            <a href="{{ route('admin.offers') }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Retour aux offres
            </a>
            <div class="flex gap-3">
                <h1 class="text-2xl font-bold text-gray-500">Candidatures pour : "{{ $offre->nom_enquete }}"</h1>
                <button @click="showOfferDetails = true"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition-colors flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Détails
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12">
            <div class="bg-white rounded-lg shadow p-4 ">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total candidatures</p>
                        <p class="text-2xl font-semibold text-gray-800"
                            v-text="filteredCandidatures.length || {{ $offre->postuleOffre->count() }}"></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    +{{ $offre->postuleOffre->where('created_at', '>=', now()->subDays(7))->count() }} cette semaine</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4 ">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Visites</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $offre->visites ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    +{{ round(($offre->visites ?? 1) / max(1, $offre->postuleOffre->count())) }}x plus de visites que de
                    candidatures</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4 ">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Taux de conversion</p>
                        <p class="text-2xl font-semibold text-gray-800">
                            {{ $offre->visites ? round(($offre->postuleOffre->count() / $offre->visites) * 100, 1) : 0 }}%
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Pourcentage de visiteurs qui ont postulé</p>
            </div>
        </div>

        <!-- Candidatures Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Liste des candidatures</h2>

                    <div class="flex gap-2 w-full sm:w-auto">
                        <div class="relative w-full sm:w-48">
                            <input type="text" v-model="searchQuery" @input="filterCandidates"
                                placeholder="Rechercher..."
                                class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 w-full transition-all text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3 top-3"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <select v-model="statusFilter" @change="filterCandidates"
                            class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente">En attente</option>
                            <option value="accepte">Accepté</option>
                            <option value="rejete">Rejeté</option>
                        </select>

                        <button @click="exportToExcel" :disabled="exporting"
                            class="bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white px-4 py-2 rounded-lg transition-colors flex items-center text-sm">
                            <i :class="exporting ? 'fas fa-spinner fa-spin' : 'fas fa-file-excel'" class="mr-2"></i>
                            <span v-if="exporting">Export...</span>
                            <span v-else>Excel</span>
                        </button>
                    </div>
                </div>

                <div v-if="loading" class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
                    <p class="mt-2 text-gray-600">Chargement...</p>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Enquêteur</th>
                                <th scope="col"
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th scope="col"
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th scope="col"
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th scope="col"
                                    class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template v-if="filteredCandidatures.length > 0">
                                <tr v-for="candidature in filteredCandidatures" :key="candidature.id"
                                    class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-medium"
                                                    v-text="candidature.enqueteur.nom.charAt(0)"></span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900" v-text="candidature.enqueteur.nom">
                                                </p>
                                                <p class="text-xs text-gray-500" v-text="candidature.enqueteur.email"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-gray-900" v-text="formatDate(candidature.date_postule)"></div>
                                        <div class="text-xs text-gray-500"
                                            v-text="formatDateRelative(candidature.date_postule)"></div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 capitalize"
                                            v-text="candidature.type_enqueteur"></span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span :class="getStatusClass(candidature.status_postule)"
                                            class="px-2 py-1 text-xs rounded-full font-medium"
                                            v-text="candidature.status_postule"></span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-1">
                                            <button @click="viewResponses(candidature.id)"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors"
                                                title="Voir les réponses">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button
                                                class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors"
                                                title="Accepter" @click="changeStatus(candidature.id, 'accepte')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button
                                                class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors"
                                                title="Rejeter" @click="changeStatus(candidature.id, 'rejete')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template v-else-if="!loading">
                                @foreach ($offre->postuleOffre as $candidature)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span
                                                        class="text-blue-600 font-medium">{{ substr($candidature->enqueteur->nom, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="font-medium text-gray-900">{{ $candidature->enqueteur->nom }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $candidature->enqueteur->email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-gray-900">
                                                {{ \Carbon\Carbon::parse($candidature->date_postule)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($candidature->date_postule)->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 capitalize">{{ $candidature->type_enqueteur }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                                    'accepte' => 'bg-green-100 text-green-800',
                                                    'rejete' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs rounded-full font-medium {{ $statusClasses[$candidature->status_postule] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $candidature->status_postule }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <div class="flex justify-end gap-1">
                                                <button @click="viewResponses({{ $candidature->id }})"
                                                    class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors"
                                                    title="Voir les réponses">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd"
                                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <button
                                                    class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors"
                                                    title="Accepter"
                                                    @click="changeStatus({{ $candidature->id }}, 'accepte')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <button
                                                    class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors"
                                                    title="Rejeter"
                                                    @click="changeStatus({{ $candidature->id }}, 'rejete')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Message si aucun résultat -->
                <div v-if="!loading && filteredCandidatures.length === 0 && (searchQuery || statusFilter)"
                    class="text-center py-8">
                    <i class="fas fa-search text-3xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Aucune candidature ne correspond à vos critères de recherche.</p>
                    <button @click="clearFilters" class="mt-2 text-blue-600 hover:text-blue-800">
                        Effacer les filtres
                    </button>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de <span class="font-medium">1</span> à
                                <span class="font-medium"
                                    v-text="filteredCandidatures.length || {{ $offre->postuleOffre->count() }}"></span>
                                sur
                                <span class="font-medium"
                                    v-text="totalCandidatures || {{ $offre->postuleOffre->count() }}"></span> résultats
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Détails de l'offre -->
        <div v-show="showOfferDetails"
            class="fixed inset-0 z-[1000] bg-black bg-opacity-60 flex items-center justify-center p-4 transition-opacity duration-300"
            :class="{ 'opacity-0 pointer-events-none': !showOfferDetails, 'opacity-100': showOfferDetails }">
            <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all duration-300"
                :class="{ 'translate-y-10 opacity-0': !showOfferDetails, 'translate-y-0 opacity-100': showOfferDetails }">
                <div
                    class="border-b border-gray-200 px-6 py-4 flex justify-between items-center bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-t-2xl">
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Détails de l'offre
                    </h2>
                    <button @click="showOfferDetails = false" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ $offre->nom_enquete }}</h3>
                            <div class="space-y-3">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar-alt mr-3 text-blue-600"></i>
                                    <span class="font-medium mr-2">Créé le:</span>
                                    {{ optional($offre->created_at)->format('d/m/Y à H:i') ?? 'Non défini' }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock mr-3 text-orange-600"></i>
                                    <span class="font-medium mr-2">Date limite:</span>
                                    {{ optional(\Carbon\Carbon::parse($offre->date_limite))->format('d/m/Y') ?? 'Non défini' }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-exclamation-triangle mr-3 text-red-600"></i>
                                    <span class="font-medium mr-2">Priorité:</span>
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $offre->priorite === 'haute' ? 'bg-red-100 text-red-800' : ($offre->priorite === 'moyenne' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($offre->priorite) }}
                                    </span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-flag mr-3 text-green-600"></i>
                                    <span class="font-medium mr-2">Statut:</span>
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $offre->status_offre === 'publiee' ? 'bg-green-100 text-green-800' : ($offre->status_offre === 'brouillon' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $offre->status_offre)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-md font-semibold text-gray-800 mb-3">Statistiques</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <p class="text-xs text-blue-600 font-medium">Candidatures</p>
                                    <p class="text-xl font-bold text-blue-800">{{ $offre->postuleOffre->count() }}</p>
                                </div>
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <p class="text-xs text-green-600 font-medium">Visites</p>
                                    <p class="text-xl font-bold text-green-800">{{ $offre->visites ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold text-gray-800 mb-3">Description de l'enquête</h4>
                        <div class="prose prose-sm max-w-none text-gray-700 p-4 bg-gray-50 rounded-lg">
                            {!! $offre->details_enquete !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Réponses de l'enquêteur -->
        <div v-show="showResponsesModal"
            class="fixed inset-0 z-[1000] bg-black bg-opacity-60 flex items-center justify-center p-4 transition-opacity duration-300"
            :class="{ 'opacity-0 pointer-events-none': !showResponsesModal, 'opacity-100': showResponsesModal }">
            <div class="bg-white rounded-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all duration-300"
                :class="{ 'translate-y-10 opacity-0': !showResponsesModal, 'translate-y-0 opacity-100': showResponsesModal }">
                <div
                    class="border-b border-gray-200 px-6 py-4 flex justify-between items-center bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-t-2xl">
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>
                        Réponses de @{{ currentCandidate?.enqueteur?.nom }}
                    </h2>
                    <div class="flex items-center gap-3">
                        <button @click="downloadResponsesPdf" :disabled="downloadingPdf"
                            class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white px-4 py-2 rounded-lg transition-colors flex items-center text-sm">
                            <i :class="downloadingPdf ? 'fas fa-spinner fa-spin' : 'fas fa-file-pdf'" class="mr-2"></i>
                            <span v-if="downloadingPdf">Génération...</span>
                            <span v-else>PDF</span>
                        </button>
                        <button @click="showResponsesModal = false"
                            class="text-white hover:text-gray-200 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div v-if="loadingResponses" class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
                        <p class="text-gray-600">Chargement des réponses...</p>
                    </div>
                    <div v-else-if="candidateResponses.length === 0" class="text-center py-8">
                        <i class="fas fa-inbox text-3xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Aucune réponse trouvée pour cette candidature.</p>
                    </div>
                    <div v-else class="space-y-6">
                        <div v-for="response in candidateResponses" :key="response.id"
                            class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-medium text-gray-800 flex items-center">
                                    <i :class="getQuestionIcon(response.question_formulaire?.type)"
                                        class="mr-2 text-blue-600"></i>
                                    @{{ response.question_formulaire?.label || 'Question supprimée' }}
                                </h3>
                                <span v-if="response.question_formulaire?.obligation"
                                    class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full font-medium">
                                    Obligatoire
                                </span>
                            </div>

                            <div class="ml-6">
                                <!-- Réponse texte normale -->
                                <div v-if="response.question_formulaire?.type !== 'image' && response.question_formulaire?.type !== 'fichier' && response.question_formulaire?.type !== 'geographique'"
                                    class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-gray-700" v-if="response.valeur">@{{ response.valeur }}</p>
                                    <p class="text-gray-400 italic" v-else>Aucune réponse</p>
                                </div>

                                <!-- Réponse géographique -->
                                <div v-if="response.question_formulaire?.type === 'geographique'"
                                    class="bg-blue-50 p-3 rounded-lg">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                        <div v-if="response.region">
                                            <span class="font-medium text-blue-700">Région:</span>
                                            <p class="text-gray-700">@{{ response.region.region }}</p>
                                        </div>
                                        <div v-if="response.district">
                                            <span class="font-medium text-blue-700">District:</span>
                                            <p class="text-gray-700">@{{ response.district.district }}</p>
                                        </div>
                                        <div v-if="response.commune">
                                            <span class="font-medium text-blue-700">Commune:</span>
                                            <p class="text-gray-700">@{{ response.commune.commune }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Réponse image -->
                                <div v-if="response.question_formulaire?.type === 'image' && response.fichier_path"
                                    class="bg-green-50 p-3 rounded-lg">
                                    <div class="flex items-center gap-4">
                                        <img :src="'/storage/' + response.fichier_path"
                                            :alt="response.question_formulaire.label"
                                            class="w-24 h-24 object-cover rounded-lg shadow-md cursor-pointer"
                                            @click="openImagePreview(response.fichier_path)">
                                        <div>
                                            <p class="text-sm font-medium text-green-700 mb-1">Image téléchargée</p>
                                            <p class="text-xs text-gray-600">Cliquez sur l'image pour agrandir</p>
                                            <a :href="'/storage/' + response.fichier_path" download
                                                class="inline-flex items-center text-xs text-green-600 hover:text-green-800 mt-1">
                                                <i class="fas fa-download mr-1"></i>Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Réponse fichier -->
                                <div v-if="response.question_formulaire?.type === 'fichier' && response.fichier_path"
                                    class="bg-red-50 p-3 rounded-lg">
                                    <div class="flex items-center gap-4">
                                        <i class="fas fa-file-pdf text-4xl text-red-600"></i>
                                        <div>
                                            <p class="text-sm font-medium text-red-700 mb-1">Fichier PDF</p>
                                            <p class="text-xs text-gray-600 mb-2">@{{ response.fichier_path.split('/').pop() }}</p>
                                            <div class="flex gap-2">
                                                <a :href="'/storage/' + response.fichier_path" target="_blank"
                                                    class="inline-flex items-center text-xs bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                                    <i class="fas fa-eye mr-1"></i>Voir
                                                </a>
                                                <a :href="'/storage/' + response.fichier_path" download
                                                    class="inline-flex items-center text-xs bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700">
                                                    <i class="fas fa-download mr-1"></i>Télécharger
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="(response.question_formulaire?.type === 'image' || response.question_formulaire?.type === 'fichier') && !response.fichier_path"
                                    class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-gray-400 italic">Aucun fichier téléchargé</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal aperçu image -->
        <div v-show="imagePreview"
            class="fixed inset-0 z-[1100] bg-black bg-opacity-80 flex items-center justify-center p-4"
            @click="imagePreview = null">
            <div class="max-w-4xl max-h-full">
                <img :src="imagePreview" alt="Aperçu"
                    class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const {
            createApp,
            ref
        } = Vue;
        const notyf = new Notyf({
            duration: 3000,
            position: {
                x: 'right',
                y: 'top'
            }
        });

        createApp({
            setup() {
                // État réactif
                const searchQuery = ref('');
                const statusFilter = ref('');
                const showOfferDetails = ref(false);
                const showResponsesModal = ref(false);
                const currentCandidate = ref(null);
                const candidateResponses = ref([]);
                const loadingResponses = ref(false);
                const imagePreview = ref(null);
                const loading = ref(false);
                const exporting = ref(false);
                const filteredCandidatures = ref([]);
                const allCandidatures = ref(@json($offre->postuleOffre->toArray()));
                const totalCandidatures = ref({{ $offre->postuleOffre->count() }});
                const downloadingPdf = ref(false);

                // Filtrage des candidatures
                const filterCandidates = () => {
                    let filtered = allCandidatures.value;

                    // Filtre de recherche
                    if (searchQuery.value) {
                        const search = searchQuery.value.toLowerCase();
                        filtered = filtered.filter(candidature =>
                            candidature.enqueteur.nom.toLowerCase().includes(search) ||
                            candidature.enqueteur.email.toLowerCase().includes(search) ||
                            candidature.type_enqueteur.toLowerCase().includes(search)
                        );
                    }

                    // Filtre de statut
                    if (statusFilter.value) {
                        filtered = filtered.filter(candidature =>
                            candidature.status_postule === statusFilter.value
                        );
                    }

                    filteredCandidatures.value = filtered;
                };

                // Effacer les filtres
                const clearFilters = () => {
                    searchQuery.value = '';
                    statusFilter.value = '';
                    filterCandidatures();
                };

                // Export vers Excel
                const exportToExcel = async () => {
                    exporting.value = true;

                    try {
                        const response = await fetch(`/admin/offers/{{ $offre->id }}/export-excel`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                search: searchQuery.value.trim() || null,
                                status: statusFilter.value || null
                            })
                        });

                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({}));
                            throw new Error(errorData.error || `Erreur HTTP: ${response.status}`);
                        }

                        // Vérifier le type de contenu
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes(
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                            throw new Error('Format de fichier incorrect reçu du serveur');
                        }

                        // Obtenir le blob
                        const blob = await response.blob();
                        if (blob.size === 0) {
                            throw new Error('Fichier vide reçu du serveur');
                        }

                        // Extraire le nom de fichier depuis les en-têtes
                        let filename = 'candidatures_export.xlsx';
                        const disposition = response.headers.get('content-disposition');
                        if (disposition && disposition.includes('filename=')) {
                            const matches = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                            if (matches && matches[1]) {
                                filename = matches[1].replace(/['"]/g, '');
                            }
                        }

                        // Créer et déclencher le téléchargement
                        const url = window.URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = filename;
                        link.style.display = 'none';

                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // Nettoyer l'URL
                        setTimeout(() => window.URL.revokeObjectURL(url), 1000);

                        notyf.success('Exportation Excel réussie !');

                    } catch (error) {
                        console.error('Erreur lors de l\'export Excel:', error);
                        notyf.error(error.message || 'Erreur lors de l\'exportation Excel');
                    } finally {
                        exporting.value = false;
                    }
                };

                // Changer le statut d'une candidature
                const changeStatus = async (id, status) => {
                    try {
                        const response = await fetch(`/admin/postule-offre/${id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                status_postule: status
                            })
                        });

                        if (!response.ok) throw new Error('Erreur lors de la mise à jour');

                        const data = await response.json();
                        notyf.success(data.message || 'Statut mis à jour avec succès');

                        // Mettre à jour localement
                        const candidature = allCandidatures.value.find(c => c.id === id);
                        if (candidature) {
                            candidature.status_postule = status;
                            filterCandidates();
                        }
                    } catch (error) {
                        notyf.error(error.message || 'Une erreur est survenue');
                    }
                };

                // Voir les réponses d'un candidat
                const viewResponses = async (candidatureId) => {
                    loadingResponses.value = true;
                    showResponsesModal.value = true;
                    candidateResponses.value = [];

                    try {
                        const response = await fetch(`/admin/candidatures/${candidatureId}/responses`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                            }
                        });

                        if (!response.ok) throw new Error('Erreur lors du chargement');

                        const data = await response.json();
                        currentCandidate.value = data.candidature;
                        candidateResponses.value = data.responses;
                    } catch (error) {
                        notyf.error('Impossible de charger les réponses');
                        showResponsesModal.value = false;
                    } finally {
                        loadingResponses.value = false;
                    }
                };

                // Utilitaires
                const getQuestionIcon = (type) => {
                    const icons = {
                        'texte': 'fas fa-font',
                        'email': 'fas fa-envelope',
                        'long_texte': 'fas fa-align-left',
                        'nombre': 'fas fa-hashtag',
                        'liste': 'fas fa-list',
                        'choix_multiple': 'fas fa-check-square',
                        'image': 'fas fa-image',
                        'fichier': 'fas fa-file-pdf',
                        'geographique': 'fas fa-map-marker-alt'
                    };
                    return icons[type] || 'fas fa-question-circle';
                };

                const getStatusClass = (status) => {
                    const classes = {
                        'en_attente': 'bg-yellow-100 text-yellow-800',
                        'accepte': 'bg-green-100 text-green-800',
                        'rejete': 'bg-red-100 text-red-800'
                    };
                    return classes[status] || 'bg-gray-100 text-gray-800';
                };

                const formatDate = (date) => {
                    return new Date(date).toLocaleDateString('fr-FR');
                };

                const formatDateRelative = (date) => {
                    const now = new Date();
                    const targetDate = new Date(date);
                    const diffTime = Math.abs(now - targetDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (diffDays === 1) return 'Hier';
                    if (diffDays < 7) return `Il y a ${diffDays} jours`;
                    if (diffDays < 30) return `Il y a ${Math.floor(diffDays/7)} semaines`;
                    return `Il y a ${Math.floor(diffDays/30)} mois`;
                };

                const openImagePreview = (imagePath) => {
                    imagePreview.value = '/storage/' + imagePath;
                };

                // Initialisation
                filterCandidates();

                const downloadResponsesPdf = async () => {
                    if (!currentCandidate.value?.id) {
                        notyf.error('Aucune candidature sélectionnée');
                        return;
                    }

                    downloadingPdf.value = true;

                    try {
                        const response = await fetch(
                            `/admin/candidatures/${currentCandidate.value.id}/pdf`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/pdf',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                }
                            });

                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({}));
                            throw new Error(errorData.message || `Erreur HTTP: ${response.status}`);
                        }

                        // Vérifier le type de contenu
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/pdf')) {
                            throw new Error('Format de fichier incorrect reçu du serveur');
                        }

                        // Obtenir le blob
                        const blob = await response.blob();
                        if (blob.size === 0) {
                            throw new Error('Fichier vide reçu du serveur');
                        }

                        // Extraire le nom de fichier depuis les en-têtes ou créer un nom par défaut
                        let filename =
                            `reponses_${currentCandidate.value.enqueteur.nom.replace(/[^a-zA-Z0-9]/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;

                        const disposition = response.headers.get('content-disposition');
                        if (disposition && disposition.includes('filename=')) {
                            const matches = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                            if (matches && matches[1]) {
                                filename = matches[1].replace(/['"]/g, '');
                            }
                        }

                        // Créer et déclencher le téléchargement
                        const url = window.URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = filename;
                        link.style.display = 'none';

                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // Nettoyer l'URL
                        setTimeout(() => window.URL.revokeObjectURL(url), 1000);

                        notyf.success('PDF téléchargé avec succès !');

                    } catch (error) {
                        console.error('Erreur lors du téléchargement PDF:', error);
                        notyf.error(error.message || 'Erreur lors du téléchargement du PDF');
                    } finally {
                        downloadingPdf.value = false;
                    }
                };

                return {
                    searchQuery,
                    downloadingPdf,
                    downloadResponsesPdf,
                    statusFilter,
                    showOfferDetails,
                    showResponsesModal,
                    currentCandidate,
                    candidateResponses,
                    loadingResponses,
                    imagePreview,
                    loading,
                    exporting,
                    filteredCandidatures,
                    totalCandidatures,
                    filterCandidates,
                    clearFilters,
                    exportToExcel,
                    changeStatus,
                    viewResponses,
                    getQuestionIcon,
                    getStatusClass,
                    formatDate,
                    formatDateRelative,
                    openImagePreview
                };
            }
        }).mount('#app');
    </script>
@endsection
