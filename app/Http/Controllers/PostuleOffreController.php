<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Offre;
use App\Models\PostuleOffre;
use App\Models\ReponseFormulaire;
use App\Models\QuestionFormulaire;
use Illuminate\Support\Facades\Log;

class PostuleOffreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Offre $offre)
    {
         return response()->json($offre->postuleOffre);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Offre $offre)
    {
        // $data = $request->validate([
        //     'enqueteur_id'  => 'required|exists:enqueteurs,id',
        //     'date_postule' => 'nullable|date',
        //     'type_enqueteur'=> 'nullable|string',
        //     'status_postule'=> 'nullable|string',
        // ]);

        // $postule = $offre->postuleOffre()->create($data);
        // return response()->json($postule, 201);

       if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour postuler.');
        }

        try {
            // Valider les données
            $validated = $request->validate([
                'offre_id' => 'required|exists:offres,id',
                'reponses' => 'required|array',
                'reponses.*.valeur' => 'nullable|string',
                'reponses.*.region_id' => 'nullable|exists:regions,id',
                'reponses.*.district_id' => 'nullable|exists:districts,id',
                'reponses.*.commune_id' => 'nullable|exists:communes,id',
            ]);

            // Vérifier que l'offre_id correspond à l'offre
            if ($offre->id != $request->offre_id) {
                return redirect()->route('enqueteur.offre.show', $offre->id)
                    ->with('error', 'Offre non valide.');
            }

            // Créer une entrée dans postules_offres
            $postule = PostuleOffre::create([
                'offre_id' => $offre->id,
                'enqueteur_id' => auth()->id(),
                'date_postule' => now(),
                'type_enqueteur' => $request->input('type_enqueteur', 'standard'),
                'status_postule' => 'en_attente',
            ]);

            // Enregistrer les réponses
            foreach ($request->reponses as $questionId => $reponse) {
                $question = QuestionFormulaire::findOrFail($questionId);

                $data = [
                    'postule_offre_id' => $postule->id,
                    'question_id' => $questionId,
                    'valeur' => $reponse['valeur'] ?? '',
                ];

                if ($question->type === 'geographique') {
                    $data['region_id'] = $reponse['region_id'] ?? null;
                    $data['district_id'] = $reponse['district_id'] ?? null;
                    $data['commune_id'] = $reponse['commune_id'] ?? null;

                    // Validation supplémentaire pour les champs géographiques obligatoires
                    if ($question->obligation) {
                        if ($question->all_regions && !$question->region_id && !$data['region_id']) {
                            return redirect()->route('enqueteur.offre.show', $offre->id)
                                ->with('error', 'La région est requise pour la question "' . $question->label . '".');
                        }
                        if ($question->all_districts && !$question->district_id && !$data['district_id']) {
                            return redirect()->route('enqueteur.offre.show', $offre->id)
                                ->with('error', 'Le district est requis pour la question "' . $question->label . '".');
                        }
                        if ($question->all_communes && !$question->commune_id && !$data['commune_id']) {
                            return redirect()->route('enqueteur.offre.show', $offre->id)
                                ->with('error', 'La commune est requise pour la question "' . $question->label . '".');
                        }
                    }
                }

                ReponseFormulaire::create($data);
            }

           return response()->json([
            'message' => 'Candidature envoyée avec succès !',
        ], 200);
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

    // public function show(PostuleOffre $postuleOffre)
    // {
    //     //$postuleOffre->load('reponseFormulaire');
    //     // return response()->json($postuleOffre);

    //     dd($postuleOffre);
    // }

    public function show(PostuleOffre $postule)
    {
        // Load the postule with its related reponses
        return response()->json($postule->load('reponseFormulaire'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostuleOffre $postule)
    {

        $data = $request->validate([
            'enqueteur_id'  => 'required|exists:enqueteurs,id',
            'date_postule' => 'nullable|date',
            'type_enqueteur'=> 'nullable|string',
            'status_postule'=> 'nullable|string',
        ]);

        $postule->update($data);
        return response()->json($postule);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostuleOffre $postule)
    {
        $postule->delete();
        return response()->json(['message' => 'Candidature supprimée']);
    }
}
