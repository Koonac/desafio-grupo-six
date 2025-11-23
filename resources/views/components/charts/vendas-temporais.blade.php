{{-- Componente de gráfico de linhas para Análise Temporal de Vendas --}}

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
        <h2 class="text-2xl sm:text-3xl font-bold text-white">Análise Temporal de Vendas</h2>
    </div>

    {{-- Container do gráfico --}}
    <div class="w-full" style="height: 400px;">
        <canvas id="{{ $chartId }}"></canvas>
    </div>

    {{-- Label com informações do melhor dia e horário --}}
    <div class="mt-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-bold text-green-300">Melhor Período de Vendas</h3>
        </div>
        <p class="text-white/90 text-sm sm:text-base">
            <strong class="text-green-300">{{ $melhorDiaDaSemana['diaDaSemana'] }}</strong> é o melhor dia da semana com
            <strong class="text-green-300">{{ $melhorDiaDaSemana['qtdeVendas'] }}</strong> vendas.
            O melhor horário é <strong
                class="text-green-300">{{ $melhorDiaDaSemana['melhorHorario']['label'] }}</strong>
            com <strong class="text-green-300">{{ $melhorDiaDaSemana['melhorHorario']['qtdeVendas'] }}</strong> vendas.
        </p>
    </div>

    {{-- Script para inicializar o gráfico --}}
    @push('scripts')
        <script>
            (function() {
                const chartId = '{{ $chartId }}';
                const horarios = @json($horarios);
                const datasets = @json($datasets);

                function initVendasTemporaisChart(containerId, horarios, datasets) {
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
                        setTimeout(() => initVendasTemporaisChart(containerId, horarios, datasets), 100);
                        return;
                    }

                    // Cria o novo gráfico de linhas
                    ctx.chart = new window.Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: horarios,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
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
                                            return context.dataset.label + ': ' + context.parsed.y + ' vendas';
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Horário do Dia',
                                        color: '#ffffff',
                                        font: {
                                            size: 14,
                                            weight: 'bold'
                                        }
                                    },
                                    ticks: {
                                        color: '#ffffff',
                                        font: {
                                            size: 11
                                        },
                                        maxRotation: 45,
                                        minRotation: 45
                                    },
                                    grid: {
                                        color: 'rgba(255, 255, 255, 0.1)',
                                        lineWidth: 1
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Quantidade de Vendas',
                                        color: '#ffffff',
                                        font: {
                                            size: 14,
                                            weight: 'bold'
                                        }
                                    },
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#ffffff',
                                        font: {
                                            size: 12
                                        },
                                        stepSize: 1
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
                        initVendasTemporaisChart(chartId, horarios, datasets);
                    });
                } else {
                    initVendasTemporaisChart(chartId, horarios, datasets);
                }
            })();
        </script>
    @endpush
</div>
