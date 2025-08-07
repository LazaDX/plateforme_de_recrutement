@extends('backOffice.layouts.admin')

@section('title', 'Tableau de bord analytique')

@section('content')
    <div id="analytics-app" class="container mx-auto p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-600">Tableau de bord analytique</h1>
            <div class="flex items-center gap-4 mt-4 md:mt-0">
                <div class="relative">
                    <select v-model="timeRange" @change="fetchAnalyticsData"
                        class="appearance-none bg-white pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="7">7 derniers jours</option>
                        <option value="30">30 derniers jours</option>
                        <option value="90">3 derniers mois</option>
                        <option value="365">12 derniers mois</option>
                    </select>
                    <svg class="absolute right-3 top-3 h-4 w-4 text-gray-400 pointer-events-none" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <button @click="refreshData"
                    class="p-2.5 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Candidatures -->
            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Nouvelles candidatures</p>
                        <p class="text-2xl font-bold mt-1">@{{ kpis.applications }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm"
                    :class="kpis.applicationsChange >= 0 ? 'text-green-600' : 'text-red-600'">
                    <span v-if="kpis.applicationsChange >= 0">+</span>@{{ kpis.applicationsChange }}%
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            :d="kpis.applicationsChange >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                    </svg>
                    <span class="text-gray-500 ml-1">vs période précédente</span>
                </div>
            </div>

            <!-- Offres publiées -->
            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Offres publiées</p>
                        <p class="text-2xl font-bold mt-1">@{{ kpis.publishedOffers }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-green-50 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm"
                    :class="kpis.offersChange >= 0 ? 'text-green-600' : 'text-red-600'">
                    <span v-if="kpis.offersChange >= 0">+</span>@{{ kpis.offersChange }}%
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            :d="kpis.offersChange >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                    </svg>
                    <span class="text-gray-500 ml-1">vs période précédente</span>
                </div>
            </div>

            <!-- Taux de conversion -->
            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Rapport candidature accéptées et total des enquêteurs
                        </p>
                        <p class="text-2xl font-bold mt-1">@{{ kpis.conversionRate }}%</p>
                    </div>
                    <div class="p-3 rounded-lg bg-purple-50 text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm"
                    :class="kpis.conversionChange >= 0 ? 'text-green-600' : 'text-red-600'">
                    <span v-if="kpis.conversionChange >= 0">+</span>@{{ kpis.conversionChange }}%
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            :d="kpis.conversionChange >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                    </svg>
                    <span class="text-gray-500 ml-1">vs période précédente</span>
                </div>
            </div>

            <!-- Candidats actifs -->
            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Candidats actifs</p>
                        <p class="text-2xl font-bold mt-1">@{{ kpis.activeCandidates }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-orange-50 text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-sm"
                    :class="kpis.candidatesChange >= 0 ? 'text-green-600' : 'text-red-600'">
                    <span v-if="kpis.candidatesChange >= 0">+</span>@{{ kpis.candidatesChange }}%
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            :d="kpis.candidatesChange >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                    </svg>
                    <span class="text-gray-500 ml-1">vs période précédente</span>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8 mb-8">
            <!-- Candidatures par jour -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800">Candidatures par jour</h3>
                    <div class="flex items-center text-sm">
                        <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                        <span class="text-gray-500">Cette période</span>
                        <span class="w-2 h-2 rounded-full bg-gray-300 ml-4 mr-2"></span>
                        <span class="text-gray-500">Période précédente</span>
                    </div>
                </div>
                <div class="h-80">
                    <canvas ref="applicationsChart"></canvas>
                </div>
            </div>

            <!-- Répartition par statut -->
            <div class="bg-white p-4 rounded-xl shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800">Répartition des candidatures</h3>
                    <div class="relative">
                        <select v-model="statusFilter" @change="updateStatusChart"
                            class="appearance-none bg-white pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="all">Toutes les offres</option>
                            <option value="active">Offres actives</option>
                        </select>
                        <svg class="absolute right-3 top-2.5 h-4 w-4 text-gray-400 pointer-events-none"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="h-80">
                    <canvas ref="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
            <!-- Top offres -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="font-medium text-gray-800 mb-4">Top 5 des offres</h3>
                <div class="space-y-4">
                    <div v-for="offer in topOffers" :key="offer.id" class="flex items-start">
                        <div
                            class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 truncate">@{{ offer.title }}</p>
                                <span
                                    class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">@{{ offer.applications }}
                                    candidatures</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Publiée le @{{ formatDate(offer.published_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sources de candidatures -->
            {{-- <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="font-medium text-gray-800 mb-4">Sources des candidatures</h3>
                <div class="h-64">
                    <canvas ref="sourceChart"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div v-for="source in applicationSources" :key="source.name" class="flex items-center text-sm">
                        <span class="w-2 h-2 rounded-full mr-2"
                            :style="{ backgroundColor: getSourceColor(source.name) }"></span>
                        <span class="text-gray-600 truncate">@{{ source.name }}</span>
                        <span class="ml-auto font-medium">@{{ source.percentage }}%</span>
                    </div>
                </div>
            </div> --}}

            <!-- Activité récente -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="font-medium text-gray-800 mb-4">Activité récente</h3>
                <div class="space-y-4">
                    <div v-for="activity in recentActivities" :key="activity.id" class="flex">
                        <div class="flex-shrink-0">
                            <div :class="getActivityIconBg(activity.type)"
                                class="h-8 w-8 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path v-if="activity.type === 'application'" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    <path v-if="activity.type === 'offer'" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    <path v-if="activity.type === 'user'" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">@{{ activity.description }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">@{{ formatTimeAgo(activity.created_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const {
            createApp,
            ref,
            onMounted
        } = Vue;

        createApp({
            setup() {
                const timeRange = ref('30');
                const statusFilter = ref('all');
                const applicationsChart = ref(null);
                const statusChart = ref(null);
                const sourceChart = ref(null);
                let applicationsChartInstance = null;
                let statusChartInstance = null;
                let sourceChartInstance = null;

                const kpis = ref({
                    applications: 0,
                    applicationsChange: 0,
                    publishedOffers: 0,
                    offersChange: 0,
                    conversionRate: 0,
                    conversionChange: 0,
                    activeCandidates: 0,
                    candidatesChange: 0
                });

                const topOffers = ref([]);
                const applicationSources = ref([]);
                const recentActivities = ref([]);
                const statusDistribution = ref([]);
                const applicationsByDay = ref([]);
                const previousApplicationsByDay = ref([]);

                const getSourceColor = (source) => {
                    const colors = {
                        "Site web": "#3B82F6",
                        "LinkedIn": "#0A66C2",
                        "Indeed": "#2164F4",
                        "Autres": "#94A3B8"
                    };
                    return colors[source] || "#94A3B8";
                };

                const getActivityIconBg = (type) => {
                    const colors = {
                        "application": "bg-green-500",
                        "offer": "bg-blue-500",
                        "user": "bg-purple-500"
                    };
                    return colors[type] || "bg-gray-500";
                };

                const formatDate = (dateString) => {
                    if (!dateString) return '';
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString('fr-FR', options);
                };

                const formatTimeAgo = (dateString) => {
                    const now = new Date();
                    const date = new Date(dateString);
                    const diffInSeconds = Math.floor((now - date) / 1000);

                    if (diffInSeconds < 60) return "À l'instant";
                    if (diffInSeconds < 3600) return `Il y a ${Math.floor(diffInSeconds / 60)} min`;
                    if (diffInSeconds < 86400) return `Il y a ${Math.floor(diffInSeconds / 3600)} h`;
                    if (diffInSeconds < 2592000) return `Il y a ${Math.floor(diffInSeconds / 86400)} j`;
                    return formatDate(dateString);
                };

                const initApplicationsChart = () => {
                    const ctx = applicationsChart.value.getContext('2d');
                    const labels = applicationsByDay.value.map(item => formatDate(item.date));
                    const currentData = applicationsByDay.value.map(item => item.count);
                    const previousData = previousApplicationsByDay.value.map(item => item.count);

                    if (applicationsChartInstance) {
                        applicationsChartInstance.destroy();
                    }

                    applicationsChartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: 'Cette période',
                                    data: currentData,
                                    borderColor: '#3B82F6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                                    borderWidth: 2,
                                    tension: 0.3,
                                    fill: true
                                },
                                {
                                    label: 'Période précédente',
                                    data: previousData,
                                    borderColor: '#E5E7EB',
                                    backgroundColor: 'transparent',
                                    borderWidth: 1,
                                    borderDash: [5, 5],
                                    tension: 0.3
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        drawBorder: false
                                    },
                                    ticks: {
                                        stepSize: 5
                                    }
                                }
                            }
                        }
                    });
                };

                const initStatusChart = () => {
                    const ctx = statusChart.value.getContext('2d');
                    const labels = statusDistribution.value.map(item => item.status_postule);
                    const data = statusDistribution.value.map(item => item.count);
                    const colors = ['#3B82F6', '#6366F1', '#10B981', '#EF4444', '#22C55E'];

                    if (statusChartInstance) {
                        statusChartInstance.destroy();
                    }

                    statusChartInstance = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: colors,
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        padding: 20
                                    }
                                }
                            }
                        }
                    });
                };

                const initSourceChart = () => {
                    const ctx = sourceChart.value.getContext('2d');
                    const labels = applicationSources.value.map(s => s.name);
                    const data = applicationSources.value.map(s => s.percentage);
                    const colors = applicationSources.value.map(s => getSourceColor(s.name));

                    if (sourceChartInstance) {
                        sourceChartInstance.destroy();
                    }

                    sourceChartInstance = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: colors,
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                };

                const fetchAnalyticsData = async () => {
                    try {
                        const response = await axios.get(`/admin/analytics/${timeRange.value}`);
                        kpis.value = response.data.kpis;
                        topOffers.value = response.data.topOffers;
                        applicationSources.value = response.data.applicationSources;
                        recentActivities.value = response.data.recentActivities;
                        statusDistribution.value = response.data.statusDistribution;
                        applicationsByDay.value = response.data.applicationsByDay;
                        previousApplicationsByDay.value = response.data.previousApplicationsByDay;

                        initApplicationsChart();
                        initStatusChart();
                        initSourceChart();
                    } catch (error) {
                        console.error('Error fetching analytics data:', error);
                    }
                };

                const updateStatusChart = () => {
                    const filteredData = statusFilter.value === 'all' ?
                        statusDistribution.value :
                        statusDistribution.value.filter(item => item.status_postule === 'en_attente' || item
                            .status_postule === 'acceptee');
                    const labels = filteredData.map(item => item.status_postule);
                    const data = filteredData.map(item => item.count);
                    const colors = ['#3B82F6', '#6366F1', '#10B981', '#EF4444', '#22C55E'];

                    statusChartInstance.data.labels = labels;
                    statusChartInstance.data.datasets[0].data = data;
                    statusChartInstance.data.datasets[0].backgroundColor = colors.slice(0, labels.length);
                    statusChartInstance.update();
                };

                const refreshData = () => {
                    fetchAnalyticsData();
                };

                onMounted(() => {
                    fetchAnalyticsData();
                });

                return {
                    timeRange,
                    statusFilter,
                    applicationsChart,
                    statusChart,
                    sourceChart,
                    kpis,
                    topOffers,
                    applicationSources,
                    recentActivities,
                    statusDistribution,
                    applicationsByDay,
                    previousApplicationsByDay,
                    getSourceColor,
                    getActivityIconBg,
                    formatDate,
                    formatTimeAgo,
                    fetchAnalyticsData,
                    updateStatusChart,
                    refreshData
                };
            }
        }).mount('#analytics-app');
    </script>
@endsection
