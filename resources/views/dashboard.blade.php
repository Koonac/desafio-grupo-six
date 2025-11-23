@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
    <div
        class="flex flex-col items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex flex-col w-full gap-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl sm:text-4xl font-bold text-white">Dashboard</h1>
                <x-button text="Ver Pedidos →" href="{{ route('pedidos') }}" />
            </div>

            {{-- RESUMO FINANCEIRO --}}
            <div class="bg-white/10 border border-white/20 shadow-2xl rounded-xl p-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Resumo Financeiro</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 ">
                    {{-- Faturamento USD e BRL --}}
                    <x-card title="Faturamento" class="bg-green-500/20! border-green-500/30! hover:bg-green-500/30!">
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalReceitaUSD }}
                            <span class="text-white/60 text-sm">USD</span>
                        </p>
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalReceitaBRL }}
                            <span class="text-white/60 text-sm">BRL</span>
                        </p>
                    </x-card>

                    {{-- Total de Reembolsos USD e BRL --}}
                    <x-card title="Total de Reembolsos" class="bg-red-500/20! border-red-500/30! hover:bg-red-500/30!">
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalReembolsosUSD }}
                            <span class="text-white/60 text-sm">USD</span>
                        </p>
                        <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalReembolsosBRL }}
                            <span class="text-white/60 text-sm">BRL</span>
                        </p>
                    </x-card>

                    {{-- Receita Líquida USD e BRL --}}
                    <x-card title="Receita Líquida" class="bg-blue-500/20! border-blue-500/30! hover:bg-blue-500/30!">
                        <p
                            class="text-xl sm:text-2xl font-bold {{ $receitaLiquidaUSD > 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $receitaLiquidaUSD }} <span class="text-white/60 text-sm">USD</span></p>
                        <p
                            class="text-xl sm:text-2xl font-bold {{ $receitaLiquidaUSD > 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $receitaLiquidaBRL }} <span class="text-white/60 text-sm">BRL</span></p>
                    </x-card>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                {{-- Total de Pedidos --}}
                <x-card title="Total de Pedidos" :value="$totalPedidos" />
                {{-- Receita Total USD e BRL --}}
                <x-card title="Receita Total" class="bg-green-500/20! border-green-500/30! hover:bg-green-500/30!">
                    <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalReceitaUSD }}
                        <span class="text-white/60 text-sm">USD</span>
                    </p>
                    <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalReceitaBRL }} <span
                            class="text-white/60 text-sm">BRL</span></p>
                </x-card>
                {{-- Pedidos Entregues --}}
                <x-card title="Pedidos Entregues" class="bg-yellow-500/20! border-yellow-500/30! hover:bg-yellow-500/30!">
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-2">{{ $totalPedidosEntregues }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span
                            class="text-lg font-semibold {{ $taxaPedidosEntregues >= 80 ? 'text-green-400' : ($taxaPedidosEntregues >= 50 ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ number_format($taxaPedidosEntregues, 1, ',', '.') }}%
                        </span>
                        <span class="text-sm text-white/60">taxa de entrega</span>
                    </div>
                </x-card>
                {{-- Clientes Únicos --}}
                <x-card title="Clientes Únicos" class="bg-blue-500/20! border-blue-500/30! hover:bg-blue-500/30!">
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-2">{{ $totalClientesUnicos }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-lg font-semibold text-blue-400">
                            {{ number_format($mediaPedidosPorCliente, 2, ',', '.') }}
                        </span>
                        <span class="text-sm text-white/60">média de pedidos/cliente</span>
                    </div>
                </x-card>
                {{-- Taxa de Reembolso --}}
                <x-card title="Taxa de Reembolso" class="bg-orange-500/20! border-orange-500/30! hover:bg-orange-500/30!">
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-2">{{ $totalPedidosReembolsados }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span
                            class="text-lg font-semibold {{ $taxaReembolso <= 5 ? 'text-green-400' : ($taxaReembolso <= 15 ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ number_format($taxaReembolso, 1, ',', '.') }}%
                        </span>
                        <span class="text-sm text-white/60">pedidos reembolsados</span>
                    </div>
                </x-card>
            </div>

            {{-- PRODUTOS DESTAQUE --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- PRODUTO MAIS VENDIDO --}}
                <div class="bg-yellow-500/20 border-2 border-yellow-400/40 shadow-2xl rounded-xl p-6 backdrop-blur-md">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-yellow-400/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">Produto Mais Vendido</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-xl sm:text-2xl font-bold text-yellow-300 mb-2">
                                {{ $produtoMaisVendido['name'] }}
                            </h3>
                            <p class="text-sm text-white/60 mb-4">SKU: {{ $produtoMaisVendido['sku'] }}</p>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-white/10 rounded-lg p-4 border border-white/20">
                                <p class="text-sm text-white/60 mb-1">Quantidade Vendida</p>
                                <p class="text-2xl sm:text-3xl font-bold text-white">
                                    {{ $produtoMaisVendido['quantity'] }}
                                </p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4 border border-white/20">
                                <p class="text-sm text-white/60 mb-1">Receita Gerada</p>
                                <p class="text-xl sm:text-2xl font-bold text-green-400">
                                    ${{ number_format($produtoMaisVendido['price'], 2, '.', ',') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PRODUTO MAIS FATURADO --}}
                <div class="bg-green-500/20 border-2 border-green-400/40 shadow-2xl rounded-xl p-6 backdrop-blur-md">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-green-400/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">Produto Mais Faturado</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-xl sm:text-2xl font-bold text-green-300 mb-2">
                                {{ $produtoMaisFaturado['name'] }}
                            </h3>
                            <p class="text-sm text-white/60 mb-4">SKU: {{ $produtoMaisFaturado['sku'] }}</p>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-white/10 rounded-lg p-4 border border-white/20">
                                <p class="text-sm text-white/60 mb-1">Quantidade Vendida</p>
                                <p class="text-2xl sm:text-3xl font-bold text-white">{{ $produtoMaisFaturado['quantity'] }}
                                </p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4 border border-white/20">
                                <p class="text-sm text-white/60 mb-1">Receita Gerada</p>
                                <p class="text-xl sm:text-2xl font-bold text-green-400">
                                    ${{ number_format($produtoMaisFaturado['price'], 2, '.', ',') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOP 5 PRODUTOS MAIS FATURADOS --}}
            <x-charts.top-5-produtos :produtos="$top5ProdutosMaisFaturados" />

            {{-- TOP 10 CIDADES EM VENDAS --}}
            <x-charts.top-10-cidades :cidades="$top10VendasCidades" />

            {{-- FATURAMENTO POR VARIANTE --}}
            <x-charts.faturamento-variantes :produtos="$faturamentoVariacoesPorProdutos" />

        </main>
    </div>
@endsection
