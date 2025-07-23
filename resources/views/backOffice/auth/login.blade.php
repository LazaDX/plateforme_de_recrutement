<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion | INSTAT-Admin</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body x-data="{ darkMode: false }"
      x-init="darkMode = JSON.parse(localStorage.getItem('darkMode') || false); $watch('darkMode', val => localStorage.setItem('darkMode', JSON.stringify(val)))"
      :class="{ 'dark bg-gray-900 text-white': darkMode }"
      class="relative min-h-screen flex items-center justify-center font-[Poppins] overflow-hidden">

    <!-- Background image floue et sombre -->
    <div class="absolute inset-0 z-0 bg-cover bg-center filter blur-sm brightness-75"
         style="background-image: url('{{ asset('img/instat-img-login.jpg') }}');">
    </div>

    <!-- Contenu principal (formulaire) -->
    <div class="relative z-10 w-full max-w-md mx-auto p-6 bg-white/80 dark:bg-gray-800/80 shadow-lg rounded-lg backdrop-blur-md" style="background-color: #ffff">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold mb-1 dark:text-white">Système de recrutement INSTAT</h1>
        </div>

        <!-- Login Form -->
        <form method="POST" action="/login">
            @csrf
            
            <div class="flex gap-4 mb-4">
                @foreach ($roles as $item)
                    <div class="flex-1">
                        <input type="radio" name="role_id" value="{{ $item->id }}" id="role_{{ $item->id }}" class="hidden peer" />
                        <label for="role_{{ $item->id }}" class="flex items-center justify-center w-full px-4 py-2 bg-gray-100 text-sm rounded cursor-pointer
                            peer-checked:bg-blue-600 peer-checked:text-white hover:bg-gray-200 
                            dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 peer-checked:dark:bg-blue-700 transition-colors">
                            <i class="fas fa-user-shield mr-2"></i> {{ $item->nom_role }}
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center my-4">
                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" id="email" name="email" required placeholder="votreadresse@gmail.com"
                       class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring focus:ring-blue-200 outline-none">
            </div>

            <div class="mb-4" x-data="{ show: false }">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mot de passe</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" id="password" name="password" required
                           placeholder="********"
                           class="w-full px-4 py-2 border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring focus:ring-blue-200 outline-none">
                    <span @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                    <input type="checkbox" class="mr-2"> Se souvenir de moi
                </label>
                <a href="/forgot-password" class="text-sm text-blue-600 hover:underline">Mot de passe oublié?</a>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors">
                Se connecter
            </button>
        </form>
    </div>

</body>
</html>
