{{-- Componente de tabela Top 10 Cidades em Vendas --}}

<div class="bg-white/10 backdrop-blur-md rounded-xl shadow-2xl border border-white/25 p-6">
    <div class="flex items-center gap-3 mb-6">
        <div
            class="w-12 h-12 bg-linear-to-br from-blue-500/30 to-green-500/30 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-white">Top 10 Cidades em Vendas</h2>
    </div>

    @if (count($cidades) > 0)
        {{-- Tabela de cidades --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-linear-to-r from-[#0f75bd] to-[#062d4b] text-white">
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Posição
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Cidade
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Pedidos
                        </th>
                        <th
                            class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">
                            Faturamento
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach ($cidades as $index => $cidade)
                        <tr class="hover:bg-white/5 transition-colors duration-200">
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm
                                        {{ $index === 0 ? 'bg-yellow-500/30 border-2 border-yellow-400' : '' }}
                                        {{ $index === 1 ? 'bg-gray-400/30 border-2 border-gray-300' : '' }}
                                        {{ $index === 2 ? 'bg-orange-600/30 border-2 border-orange-500' : '' }}
                                        {{ $index > 2 ? 'bg-blue-500/20 border border-blue-400/30' : '' }}">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="text-white">
                                    <div class="font-medium text-sm sm:text-base">
                                        {{ $cidade['name'] }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="text-white font-semibold text-sm sm:text-base">
                                    {{ number_format($cidade['quantity'], 0) }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="text-green-400 font-semibold text-sm sm:text-base">
                                    {{ number_format($cidade['price'], 2) }}
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
                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
            <p class="text-white/60 text-lg">Nenhuma cidade encontrada</p>
        </div>
    @endif
</div>
