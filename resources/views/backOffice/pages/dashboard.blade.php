@extends('backOffice.layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <div id="appDashboard" class="space-y-8">
        <!-- Main Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div v-for="card in statsCards" :key="card.id" :class="card.bgClass"
                class="rounded-lg shadow p-6 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">@{{ card.title }}</p>
                        <p class="text-2xl font-semibold text-gray-800">@{{ card.value }}</p>
                    </div>
                    <div :class="card.iconBg" class="p-3 rounded-full">
                        <span v-html="card.icon"></span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">@{{ card.extra }}</p>
            </div>
        </div>

        <!-- Recent Activity & Expiring Offers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Activité récente</h2>
                <div class="space-y-4">
                    <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">@{{ activity.text }}</p>
                            <p class="text-xs text-gray-500 mt-1">@{{ activity.time }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.historiques') }}"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">Voir tout l'historique</a>
                </div>
            </div>

            <!-- Expiring Offers -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Offres expirant bientôt</h2>
                <div class="space-y-4">
                    <div v-for="offer in offresBientot" :key="offer.id" class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">@{{ offer.nom_enquete }}</p>
                                <span class="text-xs font-medium text-red-600">
                                    Expire dans @{{ calculateDaysLeft(offer.date_limite) }}
                                    @{{ calculateDaysLeft(offer.date_limite) > 1 ? 'jours' : 'jour' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Date limite: @{{ dayjs(offer.date_limite).format('DD/MM/YYYY') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.offers') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Voir
                        toutes les offres</a>
                </div>
            </div>
        </div>

        <!-- Applications Chart -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Candidatures par jour (7 derniers jours)</h2>
            <canvas id="candidaturesChart" class="h-64 w-full"></canvas>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs/dayjs.min.js"></script>

    <script>
        const {
            createApp,
            ref,
            onMounted
        } = Vue;

        createApp({
            setup() {
                // Statistics Cards
                const statsCards = ref([{
                        id: 1,
                        title: 'Offres publiées',
                        value: {{ $stats['offres_count'] }},
                        extra: '+{{ $stats['offres_qui_expirent'] }} expirent bientôt',
                        bgClass: 'bg-white p-6',
                        iconBg: 'bg-blue-100',
                        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                               </svg>`
                    },
                    {
                        id: 2,
                        title: 'Candidatures',
                        value: {{ $stats['candidatures_count'] }},
                        extra: '+{{ $stats['candidatures_cette_semaine'] }} cette semaine',
                        bgClass: 'bg-white p-6',
                        iconBg: 'bg-green-100',
                        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                               </svg>`
                    },
                    {
                        id: 3,
                        title: 'Enquêteurs',
                        value: {{ $stats['enqueteurs_count'] }},
                        extra: '+{{ $stats['nouveaux_enqueteurs'] }} nouveaux ce mois',
                        bgClass: 'bg-white p-6',
                        iconBg: 'bg-purple-100',
                        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                               </svg>`
                    },
                    {
                        id: 4,
                        title: 'Taux de conversion',
                        value: '24.5%',
                        extra: '+2.5% vs mois dernier',
                        bgClass: 'bg-white  p-6',
                        iconBg: 'bg-orange-100',
                        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                               </svg>`
                    }
                ]);

                // Recent Activities
                const recentActivities = ref([
                    @foreach ($recentActivities as $activity)
                        {
                            id: {{ $loop->index }},
                            text: "{{ $activity }}",
                            time: "Il y a quelques minutes"
                        },
                    @endforeach
                ]);

                // Expiring Offers
                const offresBientot = ref([
                    @foreach ($offres_expirant_bientot as $offre)
                        {
                            id: {{ $offre->id }},
                            nom_enquete: "{{ $offre->nom_enquete }}",
                            date_limite: "{{ \Carbon\Carbon::parse($offre->date_limite)->format('Y-m-d') }}"
                        },
                    @endforeach
                ]);

                // Calculate Days Left
                const calculateDaysLeft = (date) => {
                    const today = dayjs().startOf('day');
                    const deadline = dayjs(date).endOf('day');
                    const diff = deadline.diff(today, 'day');
                    return diff >= 0 ? diff : 0;
                };

                // Chart Initialization
                onMounted(() => {
                    const ctx = document.getElementById('candidaturesChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($chartLabels) !!},
                            datasets: [{
                                label: 'Candidatures',
                                data: {!! json_encode($chartData) !!},
                                fill: true,
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                });

                return {
                    statsCards,
                    recentActivities,
                    offresBientot,
                    calculateDaysLeft,
                    dayjs
                };
            }
        }).mount('#appDashboard');
    </script>

    <style>
        .fade-enter-active,
        .fade-leave-active {
            transition: all 0.5s ease;
        }

        .fade-enter-from,
        .fade-leave-to {
            opacity: 0;
            transform: translateY(10px);
        }
    </style>
@endsection
