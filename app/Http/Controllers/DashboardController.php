<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\PostuleOffre;
use App\Models\Enqueteur;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'offres_count' => Offre::count(),
            'offres_qui_expirent' => Offre::where('date_limite', '<=', now()->addDays(7))->count(),
            'candidatures_count' => PostuleOffre::count(),
            'candidatures_cette_semaine' => PostuleOffre::where('created_at', '>=', now()->subWeek())->count(),
            'enqueteurs_count' => Enqueteur::count(),
            'nouveaux_enqueteurs' => Enqueteur::where('created_at', '>=', now()->startOfMonth())->count(),

        ];

        $recentActivities = [
            '5 nouvelles candidatures reçues aujourd\'hui',
            '2 offres ont été publiées cette semaine',
            '1 offre expire dans 3 jours',
            'Nouvel enquêteur inscrit: Jean Dupont',
            'Mise à jour du profil administrateur'
        ];

        // Récupérer les offres expirant bientôt
        $offres_expirant_bientot = Offre::where('date_limite', '<=', now()->addDays(7))
            ->orderBy('date_limite')
            ->take(3)
            ->get();

        // Pour Chart.js (7 derniers jours)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = Carbon::parse($date)->format('d/m');
            $chartData[] = PostuleOffre::whereDate('created_at', $date)->count();
        }

        return view('backOffice.pages.dashboard', compact('stats', 'recentActivities', 'offres_expirant_bientot', 'chartLabels', 'chartData'));
    }
}
