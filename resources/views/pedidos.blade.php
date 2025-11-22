@extends('layouts.app')

@section('title', 'Pedidos - ' . config('app.name'))

@section('content')
<div class="flex flex-col items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
    <!-- Header -->
    <div class="mb-8 w-full">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-4">
            <h1 class="text-3xl sm:text-4xl font-bold text-white">Pedidos</h1>
            <x-button text="← Voltar ao Dashboard" href="{{ route('dashboard') }}" class="px-4 sm:px-6 py-2 text-sm sm:text-base whitespace-nowrap" />
        </div>
        <p class="text-white/80 text-base sm:text-lg">Total de {{ $pagination['total'] }} pedidos encontrados</p>
    </div>

    <x-tabela-pedidos :pedidos="$pedidos" :pagination="$pagination" />
</div>
@endsection
