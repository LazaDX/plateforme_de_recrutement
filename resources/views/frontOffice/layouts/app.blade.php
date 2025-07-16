<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'frontOffice')</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="bg-gray-100 min-h-screen">

    {{-- Navbar --}}
    @include('frontOffice.components.navbar')

    {{-- Sidebar --}}

    {{-- Contenu principal --}}
    <main class="p-6">  
        @yield('content')
    </main>
</body>

</html>
