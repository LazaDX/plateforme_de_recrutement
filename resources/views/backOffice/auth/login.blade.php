<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>

<body>
    <div class="login-card bg-white mx-4">
        <div class="bg-blue-600 py-6 px-8 text-center">
            <h1 class="text-2xl font-bold text-white">INSTAT ADMIN</h1>
        </div>
        <div class="p-8">
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required autofocus
                            class="input-field pl-10 w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500"
                            value="{{ old('email') }}" placeholder="votre email">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="input-field pl-10 pr-10 w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500"
                            placeholder="••••••••">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                            onclick="togglePassword()">
                            <i id="eye-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>
                    </div>

                    <a href="#" class="text-sm text-blue-600 hover:text-blue-500">Mot de passe oublié?</a>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                </button>
            </form>
        </div>

        <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-200">
            <p class="text-sm text-gray-600">© 2025 INSTAT. Tous droits réservés.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
