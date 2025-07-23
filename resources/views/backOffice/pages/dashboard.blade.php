@extends('backOffice.layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <h1 class="text-xl font-bold mb-4">Bienvenue {{ Auth::guard('admin')->user()->nom }}</h1>
        <p>Tu es connecté en tant qu’enquêteur.</p>
    </div>
@endsection
