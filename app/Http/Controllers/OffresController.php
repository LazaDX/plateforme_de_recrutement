<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offres;

class OffresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offres = Offre::with('questionsFormulaires', 'postulesOffres')->get();
        return response()->json($offres);
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
    public function show(Offres $offre)
    {
        return response()->json($offre->load('questionsFormulaires', 'postulesOffres'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offres $offres)
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
    public function destroy($id)
    {
         $offre->delete();
        return response()->json(['message' => 'Offre supprimée']);
    }
}
