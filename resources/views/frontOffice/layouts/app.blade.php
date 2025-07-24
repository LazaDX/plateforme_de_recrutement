<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Enquêteur')</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="//unpkg.com/alpinejs" defer></script>
    @livewireStyles
</head>

<body class="w-full bg-gray-50">

    {{-- Navbar --}}
    @include('frontOffice.components.navbar')

    {{-- Contenu principal --}}
    <main class="pb-16 lg:pb-0">
        @yield('content')
    </main>

    @include('frontOffice.components.footer')
    {{-- Scripts --}}
    @livewireScripts
    @livewireStyles
</body>

</html>
