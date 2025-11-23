{{-- Componente de alerta para Pedidos Entregues e Reembolsados --}}

@if ($total > 0)
    <div class="bg-red-500/20 border-2 border-red-400/50 shadow-2xl rounded-xl p-6 backdrop-blur-md">
        <div class="flex items-start gap-4">
            {{-- Ícone de alerta --}}
            <div class="shrink-0">
                <div class="w-12 h-12 bg-red-500/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
            </div>

            {{-- Conteúdo do alerta --}}
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <h2 class="text-2xl sm:text-3xl font-bold text-red-300">Atenção: Pedidos Entregues e Reembolsados
                    </h2>
                </div>
                <p class="text-white/80 mb-4 text-sm sm:text-base">
                    Identificamos <strong class="text-red-300">{{ $total }}</strong> pedido(s) que foram
                    entregues e posteriormente reembolsados.
                    Isso pode indicar problemas de qualidade do produto ou insatisfação do cliente.
                </p>

                {{-- Estatísticas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div class="bg-white/10 rounded-lg p-4 border border-white/20">
                        <p class="text-xs text-white/60 mb-1">Total de Pedidos</p>
                        <p class="text-2xl sm:text-3xl font-bold text-red-300">{{ $total }}</p>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4 border border-white/20">
                        <p class="text-xs text-white/60 mb-1">Valor Total Reembolsado</p>
                        <p class="text-xl sm:text-2xl font-bold text-red-300">
                            {{ $formatCurrency($valorTotalReembolsado) }}
                        </p>
                    </div>
                </div>

                {{-- Aviso adicional --}}
                <div class="mt-4 p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                    <p class="text-xs text-yellow-300">
                        <strong>Recomendação:</strong> Analise esses pedidos para identificar padrões e melhorar a
                        qualidade dos produtos.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif
