<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Offre;
use App\Models\PostuleOffre;
use App\Models\QuestionFormulaire;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
    public function update(Request $request, $id)
    {
        //  $data = $request->validate([
        //     'nom_enquete'  => 'sometimes|required|string|max:255',
        //     'details'      => 'nullable|string',
        //     'date_debut'   => 'nullable|date',
        //     'date_limite'  => 'sometimes|required|date|after_or_equal:date_debut',
        //     'status_offre' => 'nullable|string',
        //     'priorite'     => 'nullable|string',
        // ]);

        // $offre->update($data);
        // return response()->json($offre);

        try {
            $offre = Offre::find($id);
        if (!$offre) {
                    \Log::error('Offre non trouvée ou ID non valide', ['offre_id' => $id ?? 'null']);
                    return response()->json([
                        'error' => 'Offre non trouvée ou ID non valide'
                    ], 404);
        }

        $form = $request->input("form");
        $formulaire = $request->input("formulaire");

        // $validated = $request->validate([
        //     'form.nom_enquete' => 'nullable|string|max:255',
        //     'form.details_enquete' => 'nullable|string',
        //     'form.date_debut' => 'nullable|date',
        //     'form.date_limite' => 'required|date',
        //     'form.priorite' => 'required|string',
        //     'form.status_offre' => 'required|in:brouillon,publiee,fermee',
        //     'formulaire' => 'required|array|min:1',
        // ]);

        $offreId = $id;

        // Mettre à jour l'offre SANS affecter l'ID
        $offre->update([
            'nom_enquete' => $form['nom_enquete'],
            'details_enquete' => $form['details_enquete'],
            'date_debut' => $form['date_debut'],
            'date_limite' => $form['date_limite'],
            'status_offre' => $form['status_offre'],
            'priorite' => $form['priorite']
        ]);

        // Supprimer les anciennes questions en utilisant l'ID stocké
        QuestionFormulaire::where('offre_id', $offreId)->delete();

        // Recréer les questions avec l'ID stocké
        foreach ($formulaire as $question) {
            $all_regions = false;
            $all_districts = false;
            $all_communes = false;

            if ($question['type'] === 'geographique') {
                switch ($question['constraint_level'] ?? null) {
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
                'offre_id' => $offreId,
                'label' => $question['label'],
                'type' => $question['type'],
                'obligation' => $question['obligation'] ?? false,
                'all_regions' => $all_regions,
                'all_districts' => $all_districts,
                'all_communes' => $all_communes,
                'region_id' => ($question['type'] === 'geographique' && isset($question['region_id'])) ? $question['region_id'] : null,
                'district_id' => ($question['type'] === 'geographique' && isset($question['district_id'])) ? $question['district_id'] : null,
                'commune_id' => ($question['type'] === 'geographique' && isset($question['commune_id'])) ? $question['commune_id'] : null,
            ]);
        }

        return response()->json([
            'message' => 'Offre mise à jour avec succès',
            'offre' => $offre->fresh() // Recharger l'offre depuis la base
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation échouée',
            'messages' => $e->errors()
        ], 422);
    } catch (\Throwable $th) {
        \Log::error('Erreur lors de la mise à jour de l\'offre: ' . $th->getMessage());
        return response()->json([
            'error' => 'Erreur serveur: ' . $th->getMessage()
        ], 500);
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offre $offre)
    {
       $offer = Offre::find($offre->id);
        if (!$offer) {
            return response()->json(['error' => 'Offer not found'], 404);
        }

        $offer->delete();
        return response()->json(['success' => true]);
    }

    /* ADMIN */
    public function getOffer(Offre $offre)
    {
        try {
            $offre = Offre::with([
                'questionFormulaire.region',
                'questionFormulaire.district',
                'questionFormulaire.commune'
            ])->findOrFail($offre->id);

            $response = [
                'nom_enquete' => $offre->nom_enquete,
                'details_enquete' => $offre->details_enquete,
                'created_at' => Carbon::parse($offre->created_at)->format('Y-m-d'),
                'date_limite' => Carbon::parse($offre->date_limite)->format('Y-m-d'),
                'priorite' => $offre->priorite ?? 'moyenne',
                'status_offre' => $offre->status_offre ?? 'brouillon',
                'formulaire' => $offre->questionFormulaire ? $offre->questionFormulaire->map(function ($question) {
                    $constraint_level = null;
                    if ($question->all_regions && $question->all_districts && $question->all_communes) {
                        $constraint_level = 'all';
                    } elseif ($question->all_regions && $question->all_districts && !$question->all_communes) {
                        $constraint_level = 'region_district';
                    } elseif ($question->all_regions && !$question->all_districts && !$question->all_communes) {
                        $constraint_level = 'region';
                    } elseif (!$question->all_regions && $question->all_districts && !$question->all_communes) {
                        $constraint_level = 'district';
                    } elseif (!$question->all_regions && !$question->all_districts && $question->all_communes) {
                        $constraint_level = 'commune';
                    } elseif (!$question->all_regions && $question->all_districts && $question->all_communes) {
                        $constraint_level = 'district_commune';
                    }

                    return [
                        'type' => $question->type,
                        'label' => $question->label,
                        'obligation' => $question->obligation,
                        'constraint_level' => $constraint_level,
                        'region_id' => $question->region_id,
                        'district_id' => $question->district_id,
                        'commune_id' => $question->commune_id,
                        'show_region' => in_array($constraint_level, ['all', 'region_district', 'district', 'commune', 'district_commune']),
                        'show_district' => in_array($constraint_level, ['all', 'region_district', 'district', 'commune', 'district_commune']),
                        'show_commune' => in_array($constraint_level, ['all', 'commune', 'district_commune']),
                    ];
                })->toArray() : [],
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Erreur dans getOffer: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur interne'], 500);
        }
    }

    public function showCandidatures(Offre $offre)
    {
        $offre->load(['postuleOffre.enqueteur', 'postuleOffre.reponseFormulaire']);
        return view('admin.offers.candidatures', compact('offre'));
    }

    public function viewOffer(Offre $offre)
    {
        return view('backOffice.pages.offer-view', compact('offre'));
    }

    public function edit(Offre $offer)
    {
        return view('backOffice.pages.offer-edit', compact('offer'));
    }

    public function getAllOffers()
    {
        try {
            $offers = Offre::all(); // Fetch all offers from the database
            return response()->json($offers, 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching offers: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch offers'], 500);
        }
    }
}
