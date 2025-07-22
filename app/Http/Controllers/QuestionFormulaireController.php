<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\QuestionFormulaire;

class QuestionFormulaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Offre $offre)
    {
        // Return all questions associated with the given offer
        return response()->json($offre->questionFormulaire);
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
            'label'      => 'required|string',
            'type'       => 'required|string',
            'obligation' => 'nullable|boolean',
        ]);

        $question = $offre->questionFormulaire()->create($data);
        return response()->json($question, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionFormulaire $question)
    {
        return response()->json($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionFormulaire $question)
    {
        $data = $request->validate([
            'label'      => 'sometimes|required|string',
            'type'       => 'sometimes|required|string',
            'obligation' => 'sometimes|nullable|boolean',
        ]);

        $question->update($data);
        return response()->json($question);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionFormulaire $question)
    {
        $question->delete();
        return response()->json(['message' => 'Question supprimée']);
    }

}
