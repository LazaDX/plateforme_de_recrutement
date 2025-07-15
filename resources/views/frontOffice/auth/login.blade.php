@extends('frontOffice.layouts.app')

@section('title', 'Connexion')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4 text-center">Connexion Enquêteur</h2>

        @if (session('status'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" required autofocus
                    class="w-full border border-gray-300 rounded p-2 mt-1" value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input id="password" name="password" type="password" required
                    class="w-full border border-gray-300 rounded p-2 mt-1">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Se connecter
                </button>

                <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">
                    Créer un compte
                </a>
            </div>
        </form>
    </div>
@endsection
