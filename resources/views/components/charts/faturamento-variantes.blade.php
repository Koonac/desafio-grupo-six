{{-- Componente de gráfico Faturamento por Variante --}}

<div class="space-y-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-linear-to-br from-blue-500/30 to-green-500/30 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">Faturamento por Variantes</h2>
    </div>

    @if (count($produtos) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($produtos as $produtoKey => $produto)
                @php
                    $variantsData = $prepareVariantsData(array_values($produto['variants']));
                    $chartId = 'faturamento-variantes-' . md5($produto['title']) . '-' . uniqid();
                @endphp

                <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-2xl border border-white/25 p-6">
                    {{-- Cabeçalho do produto --}}
                    <div class="mb-4">
                        <h3 class="text-xl sm:text-2xl font-bold text-white mb-2">{{ $produto['title'] }}</h3>
                        <div class="flex items-center gap-4">
                            <div class="bg-green-500/20 rounded-lg px-4 py-2 border border-green-500/30">
                                <p class="text-xs text-white/60 mb-1">Faturamento Total</p>
                                <p class="text-lg sm:text-xl font-bold text-green-400">
                                    {{ $formatCurrency($produto['price']) }}
                                </p>
                            </div>
                            <div class="bg-blue-500/20 rounded-lg px-4 py-2 border border-blue-500/30">
                                <p class="text-xs text-white/60 mb-1">Vendas Totais</p>
                                <p class="text-lg sm:text-xl font-bold text-blue-400">
                                    {{ number_format($produto['quantity'], 0) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if (count($variantsData['labels']) > 0)
                        {{-- Container do gráfico de pizza --}}
                        <div class="w-full" style="height: 300px;">
                            <canvas id="{{ $chartId }}"></canvas>
                        </div>

                        {{-- Script para inicializar o gráfico --}}
                        @push('scripts')
                            <script>
                                (function() {
                                    const chartId = '{{ $chartId }}';
                                    const labels = @json($variantsData['labels']);
                                    const values = @json($variantsData['values']);
                                    const colors = @json($variantsData['colors']);
                                    const variants = @json(array_values($produto['variants']));

                                    function initFaturamentoVariantesChart(containerId, labels, values, colors, variants) {
                                        const ctx = document.getElementById(containerId);

                                        if (!ctx) {
                                            console.error(`Elemento com ID "${containerId}" não encontrado`);
                                            return null;
                                        }

                                        // Destrói o gráfico anterior se existir
                                        if (ctx.chart) {
                                            ctx.chart.destroy();
                                        }

                                        // Aguarda o Chart.js estar disponível
                                        if (typeof window.Chart === 'undefined') {
                                            setTimeout(() => initFaturamentoVariantesChart(containerId, labels, values, colors, variants), 100);
                                            return;
                                        }

                                        // Cores das bordas (mesmas cores, mas com opacidade total)
                                        const borderColors = colors.map(color => color.replace('0.8', '1'));

                                        // Cria o novo gráfico de pizza
                                        ctx.chart = new window.Chart(ctx, {
                                            type: 'pie',
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    label: 'Faturamento (USD)',
                                                    data: values,
                                                    backgroundColor: colors,
                                                    borderColor: borderColors,
                                                    borderWidth: 2,
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                plugins: {
                                                    legend: {
                                                        display: true,
                                                        position: 'bottom',
                                                        labels: {
                                                            color: '#ffffff',
                                                            font: {
                                                                size: 12,
                                                                weight: 'normal'
                                                            },
                                                            padding: 10,
                                                            usePointStyle: true,
                                                        }
                                                    },
                                                    tooltip: {
                                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                                        titleColor: '#ffffff',
                                                        bodyColor: '#ffffff',
                                                        borderColor: 'rgba(255, 255, 255, 0.2)',
                                                        borderWidth: 1,
                                                        padding: 12,
                                                        callbacks: {
                                                            label: function(context) {
                                                                const variant = variants[context.dataIndex];
                                                                const value = context.parsed;
                                                                const percentage = ((value / context.dataset.data.reduce((a, b) => a + b, 0)) * 100).toFixed(1);
                                                                return [
                                                                    'Variante: ' + (variant.variant_title || 'N/A'),
                                                                    'Faturamento: $' + value.toFixed(2),
                                                                    'Quantidade: ' + (variant.quantity || 0) + ' unidades',
                                                                    'Percentual: ' + percentage + '%',
                                                                    'SKU: ' + (variant.sku || 'N/A')
                                                                ];
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });

                                        return ctx.chart;
                                    }

                                    // Inicializa o gráfico quando o DOM estiver pronto
                                    if (document.readyState === 'loading') {
                                        document.addEventListener('DOMContentLoaded', () => {
                                            initFaturamentoVariantesChart(chartId, labels, values, colors, variants);
                                        });
                                    } else {
                                        initFaturamentoVariantesChart(chartId, labels, values, colors, variants);
                                    }
                                })();
                            </script>
                        @endpush
                    @else
                        <div class="flex flex-col items-center justify-center py-8">
                            <p class="text-white/60 text-sm">Nenhuma variante encontrada</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-12">
            <svg class="w-16 h-16 text-white/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <p class="text-white/60 text-lg">Nenhum produto encontrado</p>
        </div>
    @endif
</div>

