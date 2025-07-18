<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion Enquêteur</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    @livewireStyles
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-100">
    <div class="flex flex-col md:flex-row h-screen">
        <!-- Left Column - Image -->
        <div class="hidden md:block md:w-1/2 bg-gray-800 relative overflow-hidden">
            <img src="{{ asset('img/instat-img-login.jpg') }}" alt="Enquêteur au travail"
                class="w-full h-full object-cover opacity-50">

            <div class="absolute inset-0 bg-blue-900 opacity-30"></div>

            <div class="absolute inset-0 z-10 flex flex-col justify-center px-12 text-white">
                <div class="mb-8">
                    <div class="text-4xl font-sans font-bold mb-2">INSTAT Madagascar</div>
                    {{-- <img src="{{ asset('img/instat-logo.png') }}" class="h-40 mb-2" alt=""> --}}
                    {{-- <div class="text-xl">Portail Enquêteur</div> --}}
                </div>

                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white bg-opacity-20 rounded-full shadow-md">
                            <i class="fas fa-clipboard-check text-white "></i>
                        </div>
                        <p class="text-white text-xl" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Collecte de
                            données
                            sécurisée</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white bg-opacity-20 rounded-full">
                            <i class="fas fa-cogs text-white"></i>
                        </div>
                        <span class="text-white text-xl" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Gestion
                            simplifiée</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white bg-opacity-20 rounded-full">
                            <i class="fas fa-cloud-upload-alt text-white"></i>
                        </div>
                        <span class="text-white text-xl"
                            style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Synchronisation des données</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-6 bg-white">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">

                    {{-- <h2 class="text-2xl font-bold text-gray-400 mb-2">Connexion</h2> --}}
                    {{-- <p class="text-gray-600">Accédez à votre espace de travail</p> --}}
                </div>

                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">Identifiant
                            Enquêteur</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-tag text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" required autofocus
                                class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1.5 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                value="{{ old('email') }}" placeholder="votre@email.com">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="w-full pl-10 pr-3 py-3 border border-gray-300 focus:outline-none rounded-lg focus:ring-1.5 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Rester connecté
                            </label>
                        </div>

                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Mot de passe oublié?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 shadow-md">
                        <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                    </button>

                    <div class="text-center text-sm text-gray-500">
                        Vous n'avez pas de compte?
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-800">
                            Créer un compte
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
