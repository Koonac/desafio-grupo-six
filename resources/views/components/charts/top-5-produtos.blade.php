{{-- Componente de gráfico Top 5 Produtos por Receita --}}

<div class="bg-white/10 backdrop-blur-md rounded-xl shadow-2xl border border-white/25 p-6">
    <div class="flex items-center gap-3 mb-6">
        <div
            class="w-12 h-12 bg-linear-to-br from-blue-500/30 to-green-500/30 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">Top 5 Produtos por Receita</h2>
    </div>

    @if (count($produtos) > 0)
        {{-- Container do gráfico --}}
        <div class="w-full" style="height: 400px;">
            <canvas id="{{ $chartId }}"></canvas>
        </div>

        {{-- Script para inicializar o gráfico --}}
        @push('scripts')
            <script>
                (function() {
                    const chartId = '{{ $chartId }}';
                    const produtos = @json($produtos);
                    const labels = @json($labels);
                    const receitas = @json($receitas);

                    function initTop5ProdutosChart(containerId, labels, receitas, produtos) {
                        const ctx = document.getElementById(containerId);

                        if (!ctx) {
                            console.error(`Elemento com ID "${containerId}" não encontrado`);
                            return null;
                        }

                        // Cores do gráfico - Tons de verde do mais forte para o mais claro
                        const backgroundColors = [
                            'rgba(5, 150, 105, 0.8)', // Verde muito forte (teal-600)
                            'rgba(16, 185, 129, 0.8)', // Verde forte (teal-500)
                            'rgba(34, 197, 94, 0.8)', // Verde médio-forte (green-500)
                            'rgba(74, 222, 128, 0.8)', // Verde médio (green-400)
                            'rgba(134, 239, 172, 0.8)' // Verde claro (green-300)
                        ];

                        const borderColors = [
                            'rgba(5, 150, 105, 1)', // Verde muito forte
                            'rgba(16, 185, 129, 1)', // Verde forte
                            'rgba(34, 197, 94, 1)', // Verde médio-forte
                            'rgba(74, 222, 128, 1)', // Verde médio
                            'rgba(134, 239, 172, 1)' // Verde claro
                        ];

                        // Destrói o gráfico anterior se existir
                        if (ctx.chart) {
                            ctx.chart.destroy();
                        }

                        // Aguarda o Chart.js estar disponível
                        if (typeof window.Chart === 'undefined') {
                            setTimeout(() => initTop5ProdutosChart(containerId, labels, receitas, produtos), 100);
                            return;
                        }

                        // Cria o novo gráfico
                        ctx.chart = new window.Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Receita (USD)',
                                    data: receitas,
                                    backgroundColor: backgroundColors,
                                    borderColor: borderColors,
                                    borderWidth: 2,
                                    borderRadius: 8,
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        labels: {
                                            color: '#ffffff',
                                            font: {
                                                size: 14,
                                                weight: 'bold'
                                            },
                                            padding: 15
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
                                                const produto = produtos[context.dataIndex];
                                                const receita = context.parsed.x;
                                                const quantidade = produto.quantity || 0;
                                                return [
                                                    'Produto: ' + produto.name,
                                                    'Receita: $' + receita.toFixed(2),
                                                    'Quantidade: ' + quantidade + ' unidades',
                                                    'SKU: ' + (produto.sku || 'N/A')
                                                ];
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: '#ffffff',
                                            font: {
                                                size: 12
                                            },
                                            callback: function(value) {
                                                return '$' + value.toFixed(0);
                                            }
                                        },
                                        grid: {
                                            color: 'rgba(255, 255, 255, 0.1)',
                                            lineWidth: 1
                                        }
                                    },
                                    y: {
                                        ticks: {
                                            color: '#ffffff',
                                            font: {
                                                size: 12
                                            }
                                        },
                                        grid: {
                                            color: 'rgba(255, 255, 255, 0.1)',
                                            lineWidth: 1
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
                            initTop5ProdutosChart(chartId, labels, receitas, produtos);
                        });
                    } else {
                        initTop5ProdutosChart(chartId, labels, receitas, produtos);
                    }
                })();
            </script>
        @endpush
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
