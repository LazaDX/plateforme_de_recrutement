<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Offre;
use App\Models\PostuleOffre;

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
        $data = $request->validate([
            'enqueteur_id'  => 'required|exists:enqueteurs,id',
            'date_postule' => 'nullable|date',
            'type_enqueteur'=> 'nullable|string',
            'status_postule'=> 'nullable|string',
        ]);

        $postule = $offre->postuleOffre()->create($data);
        return response()->json($postule, 201);
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
