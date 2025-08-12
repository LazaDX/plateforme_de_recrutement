<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enqueteur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EnqueteurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        try {
            $enqueteurs = Enqueteur::all();
            return response()->json($enqueteurs, 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching offers: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch offers'], 500);
        }
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
    public function edit()
    {
        $enqueteur = Auth::user();
        return view('frontOffice.pages.profile', compact('enqueteur'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $enqueteur = Auth::user();

        if (!$enqueteur) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:enqueteurs,email,' . $enqueteur->id,
            'date_de_naissance' => 'nullable|date',
            'diplomes' => 'nullable|string|max:1000',
            'experiences' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $enqueteur->fill($request->except('photo'));

        if ($request->hasFile('photo')) {
            if ($enqueteur->photo && Storage::exists('public/' . $enqueteur->photo)) {
                Storage::delete('public/' . $enqueteur->photo);
            }
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($request->nom . ' ' . $request->prenom) . '-' . time() . '.' . $extension;
            $path = $file->storeAs('profileEnqueteur', $fileName, 'public');
            $validated['photo'] = $path;
        }

        $enqueteur->update($validated);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $enqueteur,
            'photo_url' => $enqueteur->photo ? asset('storage/' . $enqueteur->photo) : null
        ]);
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
