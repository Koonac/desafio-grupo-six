<?php

namespace App\Services\Pedidos;

use App\Services\GrupoSix\GrupoSixApiService;

class MetricasPedidosService
{
	/**
	 * @var GrupoSixApiService
	 */
	private GrupoSixApiService $apiService;

	/**
	 * Construtor da classe
	 */
	public function __construct(GrupoSixApiService $apiService)
	{
		$this->apiService = $apiService;
	}

	/**
	 * Busca os pedidos da API Cartpanda
	 * 
	 * @return array
	 */
	public function getPedidos(): array
	{
		$pedidos = $this->apiService->getOrders();

		return array_column($pedidos['orders'], 'order');
	}

	/**
	 * Obtém o total de pedidos
	 * 
	 * @return int
	 */
	public function getTotalPedidos(): int
	{
		return count($this->getPedidos());
	}

	/**
	 * Obtém a receita total dos pedidos
	 * 
	 * @return float
	 */
	public function getTotalReceita(): float
	{
		return array_sum(array_column($this->getPedidos(), 'local_currency_amount'));
	}

	/**
	 * Obtém a receita total em USD
	 * 
	 * @return string
	 */
	public function getTotalReceitaUSD(): string
	{
		return '$ ' . number_format($this->getTotalReceita(), 2, '.', ',');
	}

	/**
	 * Obtém a receita total em BRL
	 * 
	 * @return string
	 */
	public function getTotalReceitaBRL(): string
	{
		return 'R$ ' . number_format($this->getTotalReceita() * config('app.cotacao_dolar'), 2, ',', '.');
	}
	/**
	 * Obtém o total de pedidos entregues
	 * 
	 * @return int
	 */
	public function getTotalPedidosEntregues(): int
	{
		return count(array_filter($this->getPedidos(), function ($pedido) {
			return $pedido['fulfillment_status'] === 'Fully Fulfilled';
		}));
	}

	/**
	 * Obtém a taxa de pedidos entregues
	 * 
	 * @return float
	 */
	public function getTaxaPedidosEntregues(): float
	{
		return ($this->getTotalPedidosEntregues() / $this->getTotalPedidos()) * 100;
	}

	/**
	 * Obtém o total de clientes
	 * 
	 * @return int
	 */
	public function getTotalClientesUnicos(): int
	{
		$clientesIds = array_map(function ($pedido) {
			return $pedido['customer']['id'];
		}, $this->getPedidos());

		return count(array_unique($clientesIds));
	}

	/**
	 * Obtém a média de pedidos por cliente
	 * 
	 * @return float
	 */
	public function getMediaPedidosPorCliente(): float
	{
		$qtdClientes = $this->getTotalClientesUnicos();
		$qtdPedidos = $this->getTotalPedidos();
		return $qtdPedidos / $qtdClientes;
	}

	/**
	 * Verifica se um pedido foi reembolsado
	 * 
	 * @param array $pedido
	 * @return bool
	 */
	private function isPedidoReembolsado(array $pedido): bool
	{
		// Verifica se há reembolsos no array refunds
		if (!empty($pedido['refunds']) && is_array($pedido['refunds'])) {
			return true;
		}

		// Verifica se algum item foi reembolsado
		if (isset($pedido['line_items']) && is_array($pedido['line_items'])) {
			foreach ($pedido['line_items'] as $item) {
				if (isset($item['is_refunded']) && $item['is_refunded'] === true) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Obtém o total de pedidos reembolsados
	 * 
	 * @return int
	 */
	public function getTotalPedidosReembolsados(): int
	{
		$pedidosReembolsados = array_filter($this->getPedidos(), function ($pedido) {
			return $this->isPedidoReembolsado($pedido);
		});

		return count($pedidosReembolsados);
	}


	/**
	 * Obtém o total de reembolsos
	 * 
	 * @return float
	 */
	public function getTotalReembolsos(): float
	{
		$totalReembolsos = array_map(function ($pedido) {
			return array_sum(array_column($pedido['refunds'], 'total_amount'));
		}, $this->getPedidos());
		return array_sum($totalReembolsos);
	}

	/**
	 * Obtém a taxa de reembolso (percentual de pedidos reembolsados)
	 * 
	 * @return float
	 */
	public function getTaxaReembolso(): float
	{
		$totalPedidos = $this->getTotalPedidos();

		if ($totalPedidos === 0) {
			return 0.0;
		}

		$pedidosReembolsados = $this->getTotalPedidosReembolsados();

		return ($pedidosReembolsados / $totalPedidos) * 100;
	}

	/**
	 * Obtém o total de reembolsos em USD
	 * 
	 * @return string
	 */
	public function getTotalReembolsosUSD(): string
	{
		return '$ ' . number_format($this->getTotalReembolsos(), 2, '.', ',');
	}

	/**
	 * Obtém o total de reembolsos em BRL
	 * 
	 * @return string
	 */
	public function getTotalReembolsosBRL(): string
	{
		return 'R$ ' . number_format($this->getTotalReembolsos() * config('app.cotacao_dolar'), 2, ',', '.');
	}

	/**
	 * Obtém a receita líquida
	 * 
	 * @return float
	 */
	public function getReceitaLiquida(): float
	{
		return $this->getTotalReceita() - $this->getTotalReembolsos();
	}

	/**
	 * Obtém a receita líquida em USD
	 * 
	 * @return string
	 */
	public function getReceitaLiquidaUSD(): string
	{
		return '$ ' . number_format($this->getReceitaLiquida(), 2, '.', ',');
	}
	/**
	 * Obtém a receita líquida em BRL
	 * 
	 * @return string
	 */
	public function getReceitaLiquidaBRL(): string
	{
		return 'R$ ' . number_format($this->getReceitaLiquida() * config('app.cotacao_dolar'), 2, ',', '.');
	}


	/**
	 * Obtém as top 10 cidades de vendas
	 * 
	 * @return array
	 */
	public function getTop10VendasCidades(): array
	{
		$cidades = [];
		foreach ($this->getPedidos() as $pedido) {
			$cidade = $pedido['shipping_address']['city'];

			if (!isset($cidades[$cidade])) {
				$cidades[$cidade] = [
					'name' => $cidade,
					'quantity' => 0,
					'price' => 0
				];
			}

			$cidades[$cidade]['quantity']++;
			$cidades[$cidade]['price'] += $pedido['local_currency_amount'];
		}

		// Ordena as cidades por faturamento
		usort($cidades, function ($a, $b) {
			return $b['quantity'] - $a['quantity'];
		});

		return array_slice($cidades, 0, 10);
	}

	/**
	 * Obtém os pedidos entregues e reembolsados
	 * 
	 * @return array
	 */
	public function getPedidosEntreguesReembolsados(): array
	{
		$pedidosEntreguesReembolsados = array_filter($this->getPedidos(), function ($pedido) {
			return $pedido['fulfillment_status'] === 'Fully Fulfilled' && $this->isPedidoReembolsado($pedido);
		});
		return $pedidosEntreguesReembolsados;
	}

	/**
	 * Obtém o ticket médio
	 * 
	 * @return float
	 */
	public function getTicketMedio(): float
	{
		return $this->getTotalReceita() / $this->getTotalPedidos();
	}

	/**
	 * Obtém o ticket médio em USD
	 * 
	 * @return string
	 */
	public function getTicketMedioUSD(): string
	{
		return '$ ' . number_format($this->getTicketMedio(), 2, '.', ',');
	}

	/**
	 * Obtém o ticket médio em BRL
	 * 
	 * @return string
	 */
	public function getTicketMedioBRL(): string
	{
		return 'R$ ' . number_format($this->getTicketMedio() * config('app.cotacao_dolar'), 2, ',', '.');
	}

	/**
	 * Obtém as vendas por dia da semana
	 * 
	 * @return array
	 */
	public function getVendasPorDiaDaSemana(): array
	{
		$vendasPorDiaDaSemana = [];
		$diasDaSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
		$vendasPorHorario = [];

		// Inicializa todos os horários de 0 a 23
		for ($hora = 0; $hora < 24; $hora++) {
			$vendasPorHorario[$hora] = [
				'hora' => $hora,
				'label' => sprintf('%02d:00', $hora),
				'qtdeVendas' => 0,
				'valorVendas' => 0,
			];
		}

		/* Inicializa as vendas por dia da semana */
		foreach ($diasDaSemana as $index => $diaDaSemana) {
			$vendasPorDiaDaSemana[$index] = [
				'diaDaSemana' => $diaDaSemana,
				'qtdeVendas' => 0,
				'valorVendas' => 0,
				'horas' => $vendasPorHorario
			];
		}

		foreach ($this->getPedidos() as $pedido) {
			$diaDaSemana = date('w', strtotime($pedido['created_at']));
			$horaDoDia = date('H', strtotime($pedido['created_at']));

			$vendasPorDiaDaSemana[$diaDaSemana]['qtdeVendas']++;
			$vendasPorDiaDaSemana[$diaDaSemana]['valorVendas'] += $pedido['local_currency_amount'];

			$vendasPorDiaDaSemana[$diaDaSemana]['horas'][$horaDoDia]['qtdeVendas']++;
			$vendasPorDiaDaSemana[$diaDaSemana]['horas'][$horaDoDia]['valorVendas'] += $pedido['local_currency_amount'];
		}

		return $vendasPorDiaDaSemana;
	}

	/**
	 * Obtém o melhor dia da semana
	 * 
	 * @return array
	 */
	public function getMelhorDiaDaSemana(): array
	{
		/* CALCULANDO MELHOR DIA DA SEMANA */
		$melhorDiaDaSemana = $this->getVendasPorDiaDaSemana();

		/* Ordena as vendas por dia da semana */
		usort($melhorDiaDaSemana, function ($a, $b) {
			return $b['qtdeVendas'] - $a['qtdeVendas'];
		});

		/* Retorna o melhor dia da semana */
		$melhorDiaDaSemana = $melhorDiaDaSemana[0];
		$melhorHorario = $melhorDiaDaSemana['horas'];

		/* Ordena as horas por dia da semana */
		usort($melhorHorario, function ($a, $b) {
			return $b['qtdeVendas'] - $a['qtdeVendas'];
		});

		/* Retorna o melhor horário */
		$melhorDiaDaSemana['melhorHorario'] = $melhorHorario[0];

		return $melhorDiaDaSemana;
	}

	/**
	 * Obtém o melhor horário
	 * 
	 * @return array
	 */
	public function getVendasPorHorario(): array
	{
		$vendasPorHorario = [];

		// Inicializa todos os horários de 0 a 23
		for ($hora = 0; $hora < 24; $hora++) {
			$vendasPorHorario[$hora] = [
				'hora' => $hora,
				'label' => sprintf('%02d:00', $hora),
				'qtdeVendas' => 0,
				'valorVendas' => 0,
			];
		}

		foreach ($this->getPedidos() as $pedido) {
			$hora = date('H', strtotime($pedido['created_at']));

			$vendasPorHorario[$hora]['qtdeVendas']++;
			$vendasPorHorario[$hora]['valorVendas'] += $pedido['local_currency_amount'];
		}

		return $vendasPorHorario;
	}

	/**
	 * Obtém o melhor horário
	 * 
	 * @return array
	 */
	public function getMelhorHorario(): array
	{
		$vendasPorHorario = $this->getVendasPorHorario();
		usort($vendasPorHorario, function ($a, $b) {
			return $b['qtdeVendas'] - $a['qtdeVendas'];
		});
		return $vendasPorHorario[0];
	}
}
