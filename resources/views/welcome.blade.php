<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon app Laravel</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <h1 class="text-3xl font-bold text-center text-blue-500">Bienvenue !</h1>
    @yield('content')
</body>
</html>
    