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

        $query = Offre::query()->with(['questionFormulaire', 'postuleOffre']);

        if ($search) {
            $query->where('nom_enquete', 'like', "%{$search}%")
                ->orWhere('details_enquete', 'like', "%{$search}%");

        }

        $offres = $query->paginate(10);
        $viewMode = $request->input('view', 'list');

        return view('frontOffice.pages.offre', compact('offres', 'viewMode', 'search'));
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
                'administrateur_id' => auth('admin')->id(),
                'status_offre' => $form['status_offre'],
                'priorite' => $form['priorite']
            ]);

            foreach ($formulaire as $question) {
                $all_regions = false;
                $all_districts = false;
                $all_communes = false;

                if ($question['type'] === 'geographique') {
                    switch ($question['constraint_level']) {
                        case 'all':
                            $all_regions = true;
                            $all_districts = true;
                            $all_communes = true;
                            break;
                        case 'region_district':
                            $all_regions = true;
                            $all_districts = true;
                            $all_communes = false;
                            break;
                        case 'region':
                            $all_regions = true;
                            $all_districts = false;
                            $all_communes = false;
                            break;
                        case 'district':
                            $all_regions = false;
                            $all_districts = true;
                            $all_communes = false;
                            break;
                        case 'commune':
                            $all_regions = false;
                            $all_districts = false;
                            $all_communes = true;
                            break;
                        case 'district_commune':
                            $all_regions = false;
                            $all_districts = true;
                            $all_communes = true;
                            break;
                    }
                }

                QuestionFormulaire::create([
                    'offre_id' => $offre->id,
                    'label' => $question['label'],
                    'type' => $question['type'],
                    'obligation' => $question['obligation'],
                    'all_regions' => $all_regions,
                    'all_districts' => $all_districts,
                    'all_communes' => $all_communes,
                    'region_id' => $question['type'] === 'geographique' ? ($question['region_id'] ?? null) : null,
                    'district_id' => $question['type'] === 'geographique' ? ($question['district_id'] ?? null) : null,
                    'commune_id' => $question['type'] === 'geographique' ? ($question['commune_id'] ?? null) : null,
                ]);
            }

            return response()->json([
                'message' => 'Offre créée avec succès !',
                'offre' => $offre
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation échouée',
                'messages' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de la création de l\'offre: ' . $th->getMessage());
            return response()->json([
                'error' => 'Erreur serveur: ' . $th->getMessage()
            ], 500);
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

        $offre = Offre::with([
            'questionFormulaire.region',
            'questionFormulaire.district',
            'questionFormulaire.commune'
        ])->findOrFail($offre->id);
        $regions = Region::select(['id', 'region'])->get();
        return view('frontOffice.pages.offre-details', compact('offre', 'regions'));
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
