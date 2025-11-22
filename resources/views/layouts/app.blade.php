<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Desafio Grupo Six'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

    </style>

    @stack('styles')
</head>
<body class="bg-linear-to-r from-[#0f75bd] via-[#041f33] to-[#000000] min-h-screen">
    <div class="container mx-auto px-4 py-8">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
