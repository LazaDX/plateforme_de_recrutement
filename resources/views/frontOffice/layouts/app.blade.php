<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Enquêteur')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</head>

<body class="w-full bg-gray-50">

    {{-- Navbar --}}
    @include('frontOffice.components.navbar')


    {{-- Contenu principal --}}
    <main class="pb-16 lg:pb-0">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('frontOffice.components.footer')


    {{-- Scripts --}}
    <script src="{{ mix('js/navbarApp.js') }}"></script>
    {{-- <script src="{{ mix('js/modalFormOfferApp.js') }}"></script> --}}

</body>

</html>
