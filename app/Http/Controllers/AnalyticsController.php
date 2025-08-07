<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAnalytics($timeRange)
    {
        // Convertir la période en jours
        $days = (int) $timeRange;
        $startDate = Carbon::now()->subDays($days);
        $previousStartDate = Carbon::now()->subDays($days * 2);
        $previousEndDate = Carbon::now()->subDays($days);

        // KPI : Nouvelles candidatures
        $applications = DB::table('postules_offres')
            ->where('date_postule', '>=', $startDate)
            ->count();
        $previousApplications = DB::table('postules_offres')
            ->whereBetween('date_postule', [$previousStartDate, $previousEndDate])
            ->count();
        $applicationsChange = $previousApplications > 0 ? (($applications - $previousApplications) / $previousApplications) * 100 : 0;

        // KPI : Offres publiées
        $publishedOffers = DB::table('offres')
            ->where('status_offre', 'publiee')
            ->where('created_at', '>=', $startDate)
            ->count();
        $previousPublishedOffers = DB::table('offres')
            ->where('status_offre', 'publiee')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();
        $offersChange = $previousPublishedOffers > 0 ? (($publishedOffers - $previousPublishedOffers) / $previousPublishedOffers) * 100 : 0;

        // KPI : Taux de conversion (candidatures acceptées / total candidatures)
        $totalApplications = DB::table('postules_offres')
            ->where('date_postule', '>=', $startDate)
            ->count();
        $acceptedApplications = DB::table('postules_offres')
            ->where('date_postule', '>=', $startDate)
            ->where('status_postule', 'acceptee')
            ->count();
        $conversionRate = $totalApplications > 0 ? ($acceptedApplications / $totalApplications) * 100 : 0;
        $previousAcceptedApplications = DB::table('postules_offres')
            ->whereBetween('date_postule', [$previousStartDate, $previousEndDate])
            ->where('status_postule', 'acceptee')
            ->count();
        $previousTotalApplications = DB::table('postules_offres')
            ->whereBetween('date_postule', [$previousStartDate, $previousEndDate])
            ->count();
        $previousConversionRate = $previousTotalApplications > 0 ? ($previousAcceptedApplications / $previousTotalApplications) * 100 : 0;
        $conversionChange = $previousConversionRate > 0 ? (($conversionRate - $previousConversionRate) / $previousConversionRate) * 100 : 0;

        // KPI : Candidats actifs
        $activeCandidates = DB::table('postules_offres')
            ->where('date_postule', '>=', $startDate)
            ->distinct('enqueteur_id')
            ->count('enqueteur_id');
        $previousActiveCandidates = DB::table('postules_offres')
            ->whereBetween('date_postule', [$previousStartDate, $previousEndDate])
            ->distinct('enqueteur_id')
            ->count('enqueteur_id');
        $candidatesChange = $previousActiveCandidates > 0 ? (($activeCandidates - $previousActiveCandidates) / $previousActiveCandidates) * 100 : 0;

        // Top 5 offres
        $topOffers = DB::table('offres')
            ->join('postules_offres', 'offres.id', '=', 'postules_offres.offre_id')
            ->select('offres.id', 'offres.nom_enquete as title', DB::raw('COUNT(postules_offres.id) as applications'), 'offres.created_at as published_at')
            ->where('offres.created_at', '>=', $startDate)
            ->groupBy('offres.id', 'offres.nom_enquete', 'offres.created_at')
            ->orderBy('applications', 'desc')
            ->limit(5)
            ->get();

        // Répartition par statut
        $statusDistribution = DB::table('postules_offres')
            ->select('status_postule', DB::raw('COUNT(*) as count'))
            ->where('date_postule', '>=', $startDate)
            ->groupBy('status_postule')
            ->get();

        // Candidatures par jour
        $applicationsByDay = DB::table('postules_offres')
            ->select(DB::raw('DATE(date_postule) as date'), DB::raw('COUNT(*) as count'))
            ->where('date_postule', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $previousApplicationsByDay = DB::table('postules_offres')
            ->select(DB::raw('DATE(date_postule) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('date_postule', [$previousStartDate, $previousEndDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Activité récente
        $recentActivities = DB::table('postules_offres')
            ->join('offres', 'postules_offres.offre_id', '=', 'offres.id')
            ->join('enqueteurs', 'postules_offres.enqueteur_id', '=', 'enqueteurs.id')
            ->select(
                'postules_offres.id',
                DB::raw("'application' as type"),
                DB::raw("CONCAT('Nouvelle candidature de ', enqueteurs.nom, ' ', enqueteurs.prenom, ' pour ', offres.nom_enquete) as description"),
                'postules_offres.created_at'
            )
            ->where('postules_offres.created_at', '>=', $startDate)
            ->union(
                DB::table('offres')
                    ->select(
                        'offres.id',
                        DB::raw("'offer' as type"),
                        DB::raw("CONCAT('Nouvelle offre publiée: ', offres.nom_enquete) as description"),
                        'offres.created_at'
                    )
                    ->where('offres.created_at', '>=', $startDate)
            )
            ->union(
                DB::table('enqueteurs')
                    ->select(
                        'enqueteurs.id',
                        DB::raw("'user' as type"),
                        DB::raw("CONCAT('Nouveau candidat inscrit: ', enqueteurs.nom, ' ', enqueteurs.prenom) as description"),
                        'enqueteurs.created_at'
                    )
                    ->where('enqueteurs.created_at', '>=', $startDate)
            )
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Sources des candidatures (simulées, car pas de champ dans la base)
        $applicationSources = [
            ['name' => 'Site web', 'percentage' => 45],
            ['name' => 'LinkedIn', 'percentage' => 28],
            ['name' => 'Indeed', 'percentage' => 15],
            ['name' => 'Autres', 'percentage' => 12],
        ];

        return response()->json([
            'kpis' => [
                'applications' => $applications,
                'applicationsChange' => round($applicationsChange, 1),
                'publishedOffers' => $publishedOffers,
                'offersChange' => round($offersChange, 1),
                'conversionRate' => round($conversionRate, 1),
                'conversionChange' => round($conversionChange, 1),
                'activeCandidates' => $activeCandidates,
                'candidatesChange' => round($candidatesChange, 1),
            ],
            'topOffers' => $topOffers,
            'applicationSources' => $applicationSources,
            'recentActivities' => $recentActivities,
            'statusDistribution' => $statusDistribution,
            'applicationsByDay' => $applicationsByDay,
            'previousApplicationsByDay' => $previousApplicationsByDay,
        ]);
    }


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
