<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Offre;
use App\Models\PostuleOffre;
use App\Models\ReponseFormulaire;
use App\Models\QuestionFormulaire;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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
        try {
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour postuler.'
                ], 401);
            }

            $offre->load('questionFormulaire');

            // Vérifier si l'enquêteur a déjà postulé
            $existingApplication = PostuleOffre::where('offre_id', $offre->id)
                ->where('enqueteur_id', auth()->id())
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà postulé pour cette offre.'
                ], 422);
            }

            // Validation des réponses
            $rules = [];
            foreach ($offre->questionFormulaire as $question) {
                if ($question->obligation) {
                    if (in_array($question->type, ['image', 'fichier'])) {
                        $rules["reponses.{$question->id}.fichier"] = 'required|file';
                    } elseif ($question->type === 'geographique') {
                        if ($question->all_regions && !$question->region_id) {
                            $rules["reponses.{$question->id}.region_id"] = 'required|integer|exists:regions,id';
                        }
                        if ($question->all_districts && !$question->district_id) {
                            $rules["reponses.{$question->id}.district_id"] = 'required|integer|exists:districts,id';
                        }
                        if ($question->all_communes && !$question->commune_id) {
                            $rules["reponses.{$question->id}.commune_id"] = 'required|integer|exists:communes,id';
                        }
                    } else {
                        $rules["reponses.{$question->id}.valeur"] = 'required';
                    }
                }
            }

            $request->validate($rules);
            $candidature = PostuleOffre::create([
                'offre_id' => $offre->id,
                'enqueteur_id' => auth()->id(),
                'date_postule' => now(),
                'type_enqueteur' => 'externe',
                'status_postule' => 'en_attente'
            ]);

            foreach ($offre->questionFormulaire as $question) {
                $reponseData = [
                    'postule_offre_id' => $candidature->id,
                    'question_id' => $question->id,
                ];

                if ($question->type === 'choix_avec_condition') {
                    $reponseData['valeur'] = $request->input("reponses.{$question->id}.valeur") ?? '';

                    if ($request->has("reponses.{$question->id}.conditions")) {
                        $conditions = $request->input("reponses.{$question->id}.conditions");
                        $conditionsData = [];
                        foreach ($conditions as $key => $value) {
                            $conditionsData[$key] = [
                                'value' => $value,
                                'file_path' => null
                            ];
                        }
                    }

                    if ($request->hasFile("reponses.{$question->id}.condition_files")) {
                        foreach ($request->file("reponses.{$question->id}.condition_files") as $fileKey => $uploadedFile) {
                            if ($uploadedFile) {
                                $fileName = time() . '_' . $fileKey . '_' . $uploadedFile->getClientOriginalName();
                                $path = $uploadedFile->storeAs('uploads/conditions', $fileName, 'public');
                                $conditionFile = new ReponseFormulaire();
                                $conditionFile->postule_offre_id = $candidature->id;
                                $conditionFile->question_id      = $question->id;
                                $conditionFile->valeur           = $path;
                                $conditionFile->condition_key    = $fileKey;
                                $conditionFile->save();
                            }
                        }
                    }
                    $reponseData['conditions_data'] = json_encode($conditionsData);

                } elseif ($question->type === 'image' || $question->type === 'fichier') {
                    if ($request->hasFile("reponses.{$question->id}.fichier")) {
                        $file = $request->file("reponses.{$question->id}.fichier");
                        $folder = $question->type === 'image' ? 'ImageEnqueteur' : 'FichierEnqueteur';
                        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs($folder, $filename, 'public');
                        $reponseData['fichier_path'] = $path;
                    }
                } elseif ($question->type === 'geographique') {
                    $reponseData['region_id'] = $request->input("reponses.{$question->id}.region_id") ?? null;
                    $reponseData['district_id'] = $request->input("reponses.{$question->id}.district_id") ?? null;
                    $reponseData['commune_id'] = $request->input("reponses.{$question->id}.commune_id") ?? null;
                } else {
                    $reponseData['valeur'] = $request->input("reponses.{$question->id}.valeur") ?? '';
                }

                ReponseFormulaire::create($reponseData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Votre candidature a été envoyée avec succès !'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la candidature: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(PostuleOffre $postule)
    {
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
