<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'backOffice')</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 min-h-screen">

    {{-- Navbar --}}
    @include('backOffice.components.navbar')

    {{-- Sidebar --}}

    {{-- Contenu principal --}}
    <main class="p-6">
        @yield('content')
    </main>
</body>

</html>
