<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostuleOffre;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CandidaturePdfController extends Controller
{

    public function downloadResponses($candidatureId)
    {
        try {
            $candidature = PostuleOffre::with([
                'enqueteur',
                'offre',
                'reponseFormulaire' => function($query) {
                    $query->with([
                        'questionFormulaire',
                        'region',
                        'district',
                        'commune'
                    ])->orderBy('created_at', 'asc');
                }
            ])->findOrFail($candidatureId);
            $data = [
                'candidature' => $candidature,
                'enqueteur' => $candidature->enqueteur,
                'offre' => $candidature->offre,
                'responses' => $candidature->reponseFormulaire,
                'generated_at' => now()->format('d/m/Y à H:i'),
            ];
            $pdf = Pdf::loadView('backOffice.components.export-pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            $filename = 'reponses_' .
                       Str::slug($candidature->enqueteur->nom) . '_' .
                       Str::slug($candidature->offre->nom_enquete) . '_' .
                       now()->format('Y-m-d') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Erreur génération PDF candidature: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
