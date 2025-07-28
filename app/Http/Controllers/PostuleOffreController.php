<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Offre;
use App\Models\PostuleOffre;
use App\Models\ReponseFormulaire;

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

        $request->validate([
        'reponses' => 'required|array',
        ]);

        $postule = PostuleOffre::create([
        'offre_id' => $offre->id,
        'enqueteur_id' => auth()->id(),
        'date_postule' => now(),
        'type_enqueteur' => $request->input('type_enqueteur', 'standard'),
        'status_postule' => 'en attente',
        ]);

        foreach ($request->reponses as $questionId => $valeur) {
        ReponseFormulaire::create([
            'postule_offre_id' => $postule->id,
            'question_id' => $questionId,
            'valeur' => $valeur,
        ]);
        }

       return redirect()->route('enqueteur.offre.show', $offre->id)
            ->with('success', 'Votre candidature a été envoyée avec succès !');
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
