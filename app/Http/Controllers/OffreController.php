<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Offre;
use App\Models\PostuleOffre;
use App\Models\QuestionFormulaire;
use App\Models\Region;

class OffreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $offres = Offre::with('questionFormulaire', 'postuleOffre')->get();
        // return response()->json($offres);

        $search = $request->input('search');
        $regionId = $request->input('region');

        $query = Offre::query()->with(['questionFormulaire', 'postuleOffre', 'region']);

        if ($search) {
            $query->where('nom_enquete', 'like', "%{$search}%")
                ->orWhere('details_enquete', 'like', "%{$search}%");

        }

        if ($regionId && $regionId !== 'all') {
            $query->where('region_id', $regionId);
        }

        $offres = $query->paginate(10);
        $regions = Region::all();

        $viewMode = $request->input('view', 'list');

        return view('frontOffice.pages.offre', compact('offres', 'regions', 'viewMode', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $data = $request->validate([
            'nom_enquete'       => 'required|string|max:255',
            'details_enquete'   => 'nullable|string',
            'date_debut'        => 'nullable|date',
            'date_limite'       => 'required|date|after_or_equal:date_debut',
            'administrateur_id' => 'required|exists:administrateurs,id',
            'status_offre'      => 'nullable|string',
            'priorite'          => 'nullable|string',
        ]);

        $offre = Offre::create($data);
        return response()->json($offre, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Offre $offre)
    {
        // return response()->json($offre->load('questionFormulaire', 'postuleOffre'));
        // $offre->load('questionFormulaire', 'postuleOffre');
       $offre->load('region', 'questionFormulaire', 'postuleOffre');
        return view('frontOffice.pages.offre-details', compact('offre'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offre $offre)
    {
         $data = $request->validate([
            'nom_enquete'  => 'sometimes|required|string|max:255',
            'details'      => 'nullable|string',
            'date_debut'   => 'nullable|date',
            'date_limite'  => 'sometimes|required|date|after_or_equal:date_debut',
            'status_offre' => 'nullable|string',
            'priorite'     => 'nullable|string',
        ]);

        $offre->update($data);
        return response()->json($offre);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offre $offre)
    {
         $offre->delete();
        return response()->json(['message' => 'Offre supprimée']);
    }
}
