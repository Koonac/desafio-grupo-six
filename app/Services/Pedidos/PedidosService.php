<?php

namespace App\Services\Pedidos;

use App\Services\GrupoSix\GrupoSixApiService;

class PedidosService
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
	 * Obtém a receita líquida
	 * 
	 * @return float
	 */
	public function getReceitaLiquida(): float
	{
		return $this->getTotalReceita() - $this->getTotalReembolsos();
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
	 * Obtém os produtos vendidos
	 * 
	 * @return array
	 */
	public function getProdutosVendidos(): array
	{
		$produtos = [];
		foreach ($this->getPedidos() as $pedido) {
			foreach ($pedido['line_items'] as $item) {
				$produtos[] = $item;
			}
		}
		return $produtos;
	}

	/**
	 * Obtém o produto mais vendido
	 * 
	 * @return array
	 */
	public function getProdutoMaisVendido(): array
	{
		$produtos = [];

		// Agrupa os produtos por SKU
		foreach ($this->getProdutosVendidos() as $produto) {
			if (!isset($produtos[$produto['sku']])) {
				$produtos[$produto['sku']] = [
					'name' => $produto['name'],
					'sku' => $produto['sku'],
					'quantity' => 0,
					'price' => 0
				];
			}
			$produtos[$produto['sku']]['quantity'] += $produto['quantity'];
			$produtos[$produto['sku']]['price'] += $produto['local_currency_item_total_price'];
		}

		// Ordena os produtos por quantidade vendida
		usort($produtos, function ($a, $b) {
			return $b['quantity'] - $a['quantity'];
		});

		// Retorna o produto mais vendido
		return $produtos[0];
	}

	/**
	 * Obtém o produto mais faturado
	 * 
	 * @return array
	 */
	public function getProdutoMaisFaturado(): array
	{
		$produtos = [];

		// Agrupa os produtos por SKU
		foreach ($this->getProdutosVendidos() as $produto) {
			if (!isset($produtos[$produto['sku']])) {
				$produtos[$produto['sku']] = [
					'name' => $produto['name'],
					'sku' => $produto['sku'],
					'quantity' => 0,
					'price' => 0
				];
			}
			$produtos[$produto['sku']]['quantity'] += $produto['quantity'];
			$produtos[$produto['sku']]['price'] += $produto['local_currency_item_total_price'];
		}

		// Ordena os produtos por valor faturado
		usort($produtos, function ($a, $b) {
			return $b['price'] - $a['price'];
		});

		// Retorna o produto mais faturado
		return $produtos[0];
	}
}
