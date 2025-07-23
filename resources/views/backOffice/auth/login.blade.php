<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Administrateur')</title>
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

<body>
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4 text-center">Connexion Admin</h2>

    <!-- Background image floue et sombre -->
    <div class="absolute inset-0 z-0 bg-cover bg-center filter blur-sm brightness-75"
         style="background-image: url('{{ asset('img/instat-img-login.jpg') }}');">
    </div>

        <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
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
