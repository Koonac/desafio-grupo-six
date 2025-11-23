{{-- Componente de tabela Produtos com Alta Taxa de Reembolso --}}

<div class="bg-white/10 backdrop-blur-md rounded-xl shadow-2xl border border-white/25 p-6">
    <div class="flex items-center gap-3 mb-6">
        <div
            class="w-12 h-12 bg-linear-to-br from-red-500/30 to-orange-500/30 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                </path>
            </svg>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">Produtos com Alta Taxa de Reembolso</h2>
    </div>

    @if (count($top10ProdutosComAltaTaxaDeReembolso) > 0)
        {{-- Tabela de produtos --}}
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-white/25 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-linear-to-r from-red-600/20 to-orange-600/20 text-white">
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Posição
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Produto
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Taxa de Reembolso
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Vendidos
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Reembolsados
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Valor Reembolsado
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach ($top10ProdutosComAltaTaxaDeReembolso as $index => $produto)
                        <tr class="hover:bg-white/5 transition-colors duration-200">
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm
                                        {{ $index === 0 ? 'bg-red-500/30 border-2 border-red-400' : '' }}
                                        {{ $index === 1 ? 'bg-orange-500/30 border-2 border-orange-400' : '' }}
                                        {{ $index === 2 ? 'bg-yellow-500/30 border-2 border-yellow-400' : '' }}
                                        {{ $index > 2 ? 'bg-red-500/20 border border-red-400/30' : '' }}">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="text-white">
                                    <div class="font-medium text-sm sm:text-base">
                                        {{ $produto['name'] }}
                                    </div>
                                    <div class="text-xs text-white/60 mt-1">
                                        SKU: {{ $produto['sku'] }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span
                                    class="font-semibold text-sm sm:text-base
                                    {{ $produto['tax_refund_rate'] >= 30 ? 'text-red-400' : ($produto['tax_refund_rate'] >= 15 ? 'text-orange-400' : 'text-yellow-400') }}">
                                    {{ number_format($produto['tax_refund_rate'], 2, ',', '.') }}%
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="text-white font-semibold text-sm sm:text-base">
                                    {{ number_format($produto['quantity'], 0) }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="text-red-300 font-semibold text-sm sm:text-base">
                                    {{ number_format($produto['refund_quantity'], 0) }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="text-red-400 font-semibold text-sm sm:text-base">
                                    ${{ number_format($produto['refund_price'], 2, '.', ',') }}
                                    <span class="text-white/60 text-sm">USD</span>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-12">
            <svg class="w-16 h-16 text-white/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                </path>
            </svg>
            <p class="text-white/60 text-lg">Nenhum produto com reembolso encontrado</p>
        </div>
    @endif
</div>
