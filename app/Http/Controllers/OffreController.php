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
        
        try {
            $form = $request->input("form");
            $formulaire = $request->input("formulaire");
        
            $offre = Offre::create([
                'nom_enquete' => $form['nom_enquete'],
                'details_enquete' => $form['details_enquete'],
                'date_debut' => $form['date_debut'],
                'date_limite' => $form['date_limite'],
                // 'administrateur_id' => $form['administrateur_id'],
                 'administrateur_id' => 1,
                'status_offre' => $form['status_offre'],
                'priorite' => $form['priorite']
            ]); 

            $offre_id = $offre->id;
            if ($offre_id && $offre) {
                foreach ($formulaire as $question) {
                    QuestionFormulaire::create([
                        'offre_id' => $offre->id,
                        'label' => $question['label'],
                        'type' => $question['type'],
                        'obligation' => $question['obligation']
                    ]);
                }   
            }
            
            return response()->json($offre, 201);

         } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
         }
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
