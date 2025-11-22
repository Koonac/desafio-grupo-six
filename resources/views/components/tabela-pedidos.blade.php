{{-- Componente de classe - props vêm da classe TabelaPedidos --}}

<!-- Tabela de Pedidos -->
<div class="bg-white/10 backdrop-blur-md rounded-xl shadow-2xl border border-white/25 overflow-hidden w-full">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-linear-to-r from-[#0f75bd] to-[#062d4b] text-white">
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">ID</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">Cliente</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider hidden md:table-cell">Status Pagamento</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider hidden lg:table-cell">Status Entrega</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">Valor</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider hidden sm:table-cell">Data</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($pedidos as $pedido)
                <tr class="hover:bg-white/5 transition-colors duration-200">
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                        <span class="text-white font-medium text-sm sm:text-base">#{{ $pedido['id'] }}</span>
                    </td>
                    <td class="px-3 sm:px-6 py-3 sm:py-4">
                        <div class="text-white">
                            <div class="font-medium text-sm sm:text-base">
                                {{ $pedido['customer']['first_name'] }} {{ $pedido['customer']['last_name'] }}
                            </div>
                            <div class="text-xs sm:text-sm text-white/70">
                                {{ $pedido['contact_email'] }}
                            </div>
                            {{-- Status para mobile --}}
                            <div class="md:hidden mt-2 flex flex-wrap gap-2">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold border {{ $getPaymentStatusClass($pedido['financial_status']) }}">
                                    {{ $getPaymentStatusLabel($pedido['financial_status']) }}
                                </span>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold border {{ $getFulfillmentStatusClass($pedido['fulfillment_status']) }}">
                                    {{ $getFulfillmentStatusLabel($pedido['fulfillment_status']) }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                        <span class="px-2 sm:px-3 py-1 rounded-full text-xs font-semibold border {{ $getPaymentStatusClass($pedido['financial_status']) }}">
                            {{ $getPaymentStatusLabel($pedido['financial_status']) }}
                        </span>
                    </td>
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">
                        <span class="px-2 sm:px-3 py-1 rounded-full text-xs font-semibold border {{ $getFulfillmentStatusClass($pedido['fulfillment_status']) }}">
                            {{ $getFulfillmentStatusLabel($pedido['fulfillment_status']) }}
                        </span>
                    </td>
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                        <span class="text-white font-semibold text-sm sm:text-base">
                            {{ $formatCurrency($pedido['local_currency_amount'], $pedido['currency']) }}
                        </span>
                        {{-- Data para mobile --}}
                        <div class="sm:hidden mt-1">
                            <span class="text-white/70 text-xs">
                                {{ $formatDate($pedido['created_at']) }}
                            </span>
                        </div>
                    </td>
                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                        <span class="text-white/80 text-sm">
                            {{ $formatDate($pedido['created_at'])}}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-white/60 text-lg">Nenhum pedido encontrado</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($pagination['hasPages'])
    <div class="px-4 sm:px-6 py-4 bg-white/5 border-t border-white/10">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-white/80 text-xs sm:text-sm text-center sm:text-left">
                Mostrando {{ $pagination['firstItem'] }} até {{ $pagination['lastItem'] }} de {{ $pagination['total'] }} pedidos
            </div>
            <div class="flex items-center space-x-1 sm:space-x-2 flex-wrap justify-center">
                {{-- Botão Anterior --}}
                @if($pagination['onFirstPage'])
                <span class="px-3 sm:px-4 py-2 bg-white/5 text-white/40 rounded-lg cursor-not-allowed border border-white/10 text-xs sm:text-sm">
                    Anterior
                </span>
                @else
                <a href="{{ $pagination['previousUrl'] }}" class="px-3 sm:px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all duration-300 border border-white/20 text-xs sm:text-sm">
                    Anterior
                </a>
                @endif

                {{-- Números das páginas --}}
                <div class="flex items-center space-x-1">
                    @foreach($pagination['pageUrls'] as $page => $pageUrl)
                    @if($page == $pagination['currentPage'])
                    <span class="px-3 sm:px-4 py-2 bg-linear-to-r from-[#0f75bd] to-[#0a5a94] text-white rounded-lg font-semibold border border-white/30 text-xs sm:text-sm">
                        {{ $page }}
                    </span>
                    @else
                    <a href="{{ $pageUrl }}" class="px-3 sm:px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all duration-300 border border-white/20 text-xs sm:text-sm">
                        {{ $page }}
                    </a>
                    @endif
                    @endforeach
                </div>

                {{-- Botão Próximo --}}
                @if($pagination['hasMorePages'])
                <a href="{{ $pagination['nextUrl'] }}" class="px-3 sm:px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all duration-300 border border-white/20 text-xs sm:text-sm">
                    Próximo
                </a>
                @else
                <span class="px-3 sm:px-4 py-2 bg-white/5 text-white/40 rounded-lg cursor-not-allowed border border-white/10 text-xs sm:text-sm">
                    Próximo
                </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
