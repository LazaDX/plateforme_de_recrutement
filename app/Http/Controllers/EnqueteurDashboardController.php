<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PostuleOffre;
use App\Models\Offre;

class EnqueteurDashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    public function candidatures(Request $request)
    {
        $enqueteur = Auth::user();

        $query = PostuleOffre::with('offre')
            ->where('enqueteur_id', $enqueteur->id)
            ->orderBy('date_postule', 'desc');

        if ($request->status && $request->status !== 'all') {
            $query->where('status_postule', $request->status);
        }

        $candidatures = $query->paginate(10);

        // Statistiques
        $stats = PostuleOffre::where('enqueteur_id', $enqueteur->id)
            ->selectRaw('status_postule, count(*) as count')
            ->groupBy('status_postule')
            ->pluck('count', 'status_postule')
            ->toArray();

               return view('frontOffice.pages.applications', compact('candidatures', 'stats'));
   }

 public function dashboard()
   {
       $enqueteur = Auth::user();

       // Statistiques générales
       $stats = [
           'total' => PostuleOffre::where('enqueteur_id', $enqueteur->id)->count(),
           'en_attente' => PostuleOffre::where('enqueteur_id', $enqueteur->id)->where('status_postule', 'en_attente')->count(),
           'accepte' => PostuleOffre::where('enqueteur_id', $enqueteur->id)->where('status_postule', 'accepte')->count(),
           'refuse' => PostuleOffre::where('enqueteur_id', $enqueteur->id)->where('status_postule', 'refuse')->count(),
           'termine' => PostuleOffre::where('enqueteur_id', $enqueteur->id)->where('status_postule', 'termine')->count(),
           'ce_mois' => PostuleOffre::where('enqueteur_id', $enqueteur->id)
               ->whereMonth('date_postule', now()->month)
               ->whereYear('date_postule', now()->year)
               ->count()
       ];

       // Candidatures récentes
       $recentCandidatures = PostuleOffre::with('offre')
           ->where('enqueteur_id', $enqueteur->id)
           ->orderBy('date_postule', 'desc')
           ->limit(5)
           ->get();

       // Nouvelles offres (pas encore postulées)
       $postuleOffreIds = PostuleOffre::where('enqueteur_id', $enqueteur->id)
           ->pluck('offre_id')
           ->toArray();

       $nouvellesOffres = Offre::whereNotIn('id', $postuleOffreIds)
           ->where('date_limite', '>', now())
           ->orderBy('created_at', 'desc')
           ->limit(5)
           ->get();

       return view('frontOffice.pages.dashboard', compact('stats', 'recentCandidatures', 'nouvellesOffres'));
   }

   public function cancelCandidature($id, Request $request)
{
    $enqueteur = Auth::user();

    $candidature = PostuleOffre::where('id', $id)
        ->where('enqueteur_id', $enqueteur->id)
        ->where('status_postule', 'en_attente')
        ->first();

    if ($candidature) {
        $candidature->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Candidature annulée avec succès']);
        }

        return redirect()->route('enqueteur.candidatures')
            ->with('success', 'Candidature annulée avec succès.');
    }

    if ($request->ajax()) {
        return response()->json(['error' => 'Impossible d\'annuler cette candidature'], 400);
    }

    return redirect()->route('enqueteur.candidatures')
        ->with('error', 'Impossible d\'annuler cette candidature.');
}

}
