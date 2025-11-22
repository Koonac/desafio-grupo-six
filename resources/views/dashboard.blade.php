<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @endif
</head>
<body class="bg-linear-to-r from-[#0f75bd] to-[#041f33] text-white flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex w-full ">
            <h1>Olá mundo</h1>
            <pre class="w-full whitespace-pre-wrap">
            {{ dd($produtoMaisFaturado) }}
            {{ $totalPedidos }}
            {{ $totalReembolsos }}
            {{ $totalClientesUnicos }}
            </pre>
        </main>
    </div>
</body>
</html>
