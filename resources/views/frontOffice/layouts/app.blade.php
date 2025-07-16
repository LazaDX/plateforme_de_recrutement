<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Enquêteur')</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-50">

    {{-- Navbar --}}
    @include('frontOffice.components.navbar')

    {{-- Contenu principal --}}
    <main class="pb-16 lg:pb-0">
        @yield('content')
    </main>

    @livewireScripts
</body>

</html>
