@extends('backOffice.layouts.admin')

@section('title', 'Offres d\'enquêtes')

@section('content')
    <div id="app" class="container mx-auto p-6">

        <h1 class="text-xl font-bold mb-4">Offres d'enquêtes</h1>

        <a href="{{ route('admin.offers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Nouvelle offre
        </a>

    @endsection
