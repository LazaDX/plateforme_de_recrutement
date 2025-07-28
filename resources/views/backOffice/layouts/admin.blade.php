<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Administrateur')</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>



<body class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    <!-- Mobile backdrop -->
    <div x-show="sidebarOpen && window.innerWidth < 1024" x-transition.opacity
        class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-cloak>
    </div>

    <!-- Sidebar -->
    @include('backOffice.components.sidebar')

    <div class="lg:pl-64">
        <!-- Header -->
        @include('backOffice.components.navbar')

        <!-- Main Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    @livewireScripts
</body>
</body>

</html>
