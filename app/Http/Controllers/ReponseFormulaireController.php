<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PostuleOffre;
use App\Models\ReponseFormulaire;

class ReponseFormulaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PostuleOffre $postuleOffre)
    {
      return response()->json($postuleOffre->reponses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PostuleOffre $postule)
    {
         $data = $request->validate([
            'question_id' => 'required|exists:questions_formulaires,id',
            'valeur'      => 'nullable|string',
        ]);

        $reponse = $postule->reponseFormulaire()->create($data);
        return response()->json($reponse, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ReponseFormulaire $reponse)
    {
        return response()->json($reponse);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReponseFormulaire $reponse)
    {
        $data = $request->validate([
            'valeur'    => 'nullable|string',
        ]);

        $reponse->update($data);
        return response()->json($reponse);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReponseFormulaire $reponse)
    {
         $reponse->delete();
        return response()->json(['message' => 'Réponse supprimée']);
    }
}
