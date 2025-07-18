<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Enquêteur</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
        }

        .form-container {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .input-field:focus {
            border-color: #4fd1c5;
            box-shadow: 0 0 0 3px rgba(79, 209, 197, 0.2);
        }

        .btn-submit {
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-50 to-blue-50">
    <div class="form-container w-full max-w-md mx-4 bg-white rounded-xl overflow-hidden p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Inscription Enquêteur</h1>
            <p class="text-gray-600 mt-2">Créez votre compte pour commencer</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input id="nom" name="nom" type="text" required autofocus
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 input-field focus:outline-none focus:ring-2 focus:ring-teal-200"
                        value="{{ old('nom') }}" placeholder="Votre nom">
                    @error('nom')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                    <input id="prenom" name="prenom" type="text" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 input-field focus:outline-none focus:ring-2 focus:ring-teal-200"
                        value="{{ old('prenom') }}" placeholder="Votre prénom">
                    @error('prenom')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" name="email" type="email" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 input-field focus:outline-none focus:ring-2 focus:ring-teal-200"
                    value="{{ old('email') }}" placeholder="exemple@email.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input id="password" name="password" type="password" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 input-field focus:outline-none focus:ring-2 focus:ring-teal-200"
                    placeholder="••••••••">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirmer le mot de passe
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 input-field focus:outline-none focus:ring-2 focus:ring-teal-200"
                    placeholder="••••••••">
            </div>

            <button type="submit"
                class="w-full bg-teal-600 text-gray-500 px-6 py-3 rounded-lg font-medium btn-submit hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                Créer mon compte
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Vous avez déjà un compte?
                <a href="{{ route('login') }}" class="font-medium text-teal-600 hover:text-teal-500">
                    Connectez-vous
                </a>
            </p>
        </div>
    </div>
</body>

</html>
