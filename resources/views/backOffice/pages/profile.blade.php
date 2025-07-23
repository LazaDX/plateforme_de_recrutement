@extends('backOffice.layouts.admin')

@section('title', 'Profil')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <h1 class="text-xl font-bold mb-4">Mon Profil</h1>
        <p>Bienvenue sur votre page de profil, {{ Auth::guard('admin')->user()->nom }}.</p>
        <p>Vous pouvez gérer vos informations personnelles et paramètres ici.</p>
    </div>
@endsection
