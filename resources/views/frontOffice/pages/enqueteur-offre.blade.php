@extends('frontOffice.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Bienvenue {{ Auth::user()->nom }}</h1>
        <p>Tu es connecté en tant qu’enquêteur.</p>
    </div>
@endsection
