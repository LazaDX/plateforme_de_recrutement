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

        <!-- Left Column - Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-4 bg-white">
            <div class="w-full max-w-sm">
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
        <!-- Left Column - Image -->
        <div class="hidden md:block md:w-1/2 relative overflow-hidden bg-gray-900">
            <!-- Image avec flou et animation au survol -->
            <img src="{{ asset('img/instat-img-login.jpeg') }}" alt="Enquêteur au travail"
                class="w-full h-full object-cover opacity-50 blur-md hover:blur-sm hover:opacity-80 transition-all duration-500 ease-in-out transform hover:scale-105">

            <!-- Overlay dégradé pour meilleure lisibilité -->
            <div class="absolute inset-0 bg-gradient-to-t from-blue-850/70 via-blue-700/40 to-transparent"></div>

            <!-- Contenu texte amélioré -->
            <div class="absolute inset-0 z-10 flex flex-col justify-center px-12 text-white space-y-8">
                <!-- Titre avec animation -->
                <div class="mb-8 transform transition-all duration-500 hover:scale-105 hover:pl-4">
                    <div class="text-5xl font-bold font-sans tracking-tight mb-2 text-white drop-shadow-2xl">
                        Instat Madagascar
                    </div>
                    <div class="w-24 h-1.5 bg-blue-400 mt-4 rounded-full"></div>
                </div>

                <!-- Liste des features avec effets -->
                <div class="space-y-6 max-w-md">
                    <!-- Item 1 -->
                    <div class="flex items-start space-x-4 group">
                        <div
                            class="p-3 bg-white/10 rounded-xl backdrop-blur-sm group-hover:bg-blue-500/80 transition-all duration-300 shadow-lg">
                            <i class="fas fa-clipboard-check text-xl text-white"></i>
                        </div>
                        <p
                            class="text-xl font-medium text-white/90 group-hover:text-white transition-all duration-300 pt-1 drop-shadow-lg">
                            Collecte de données sécurisée et <span
                                class="text-blue-300 group-hover:underline">chiffrée</span>
                        </p>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-start space-x-4 group">
                        <div
                            class="p-3 bg-white/10 rounded-xl backdrop-blur-sm group-hover:bg-blue-500/80 transition-all duration-300 shadow-lg">
                            <i class="fas fa-cogs text-xl text-white"></i>
                        </div>
                        <p
                            class="text-xl font-medium text-white/90 group-hover:text-white transition-all duration-300 pt-1 drop-shadow-lg">
                            Interface <span class="text-blue-300">intuitive</span> et gestion simplifiée
                        </p>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-start space-x-4 group">
                        <div
                            class="p-3 bg-white/10 rounded-xl backdrop-blur-sm group-hover:bg-blue-500/80 transition-all duration-300 shadow-lg">
                            <i class="fas fa-cloud-upload-alt text-xl text-white"></i>
                        </div>
                        <p
                            class="text-xl font-medium text-white/90 group-hover:text-white transition-all duration-300 pt-1 drop-shadow-lg">
                            Synchronisation <span class="text-blue-300">en temps réel</span> des données
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </div>
</body>

</html>
