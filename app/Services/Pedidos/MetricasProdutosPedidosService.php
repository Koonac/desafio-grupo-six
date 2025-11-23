<?php

namespace App\Services\Pedidos;

use App\Services\GrupoSix\GrupoSixApiService;

class MetricasProdutosPedidosService
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
	private function getPedidos(): array
	{
		$pedidos = $this->apiService->getOrders();

		return array_column($pedidos['orders'], 'order');
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

	/**
	 * Obtém os 5 produtos mais faturados
	 * 
	 * @return array
	 */
	public function getTop5ProdutosMaisFaturados(): array
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

		// Retorna os 5 produtos mais faturados
		return array_slice($produtos, 0, 5);
	}

	/**
	 * Obtém os produtos por variantes
	 * 
	 * @return array
	 */
	public function getFaturamentoVariacoesPorProdutos(): array
	{
		$faturamentoVariacoesPorProdutos = [];

		foreach ($this->getProdutosVendidos() as $produto) {
			if (!isset($faturamentoVariacoesPorProdutos[$produto['title']])) {
				$faturamentoVariacoesPorProdutos[$produto['title']] = [
					'title' => $produto['title'], // Nome do produto pai
					'quantity' => 0,
					'price' => 0,
					'variants' => [],
				];
			}
			$faturamentoVariacoesPorProdutos[$produto['title']]['quantity'] += $produto['quantity'];
			$faturamentoVariacoesPorProdutos[$produto['title']]['price'] += $produto['local_currency_item_total_price'];

			if (!isset($faturamentoVariacoesPorProdutos[$produto['title']]['variants'][$produto['sku']])) {
				$faturamentoVariacoesPorProdutos[$produto['title']]['variants'][$produto['sku']] = [
					'variant_title' => $produto['variant_title'], // Nome da variante
					'sku' => $produto['sku'], // SKU da variante
					'quantity' => 0,
					'price' => 0
				];
			}
			$faturamentoVariacoesPorProdutos[$produto['title']]['variants'][$produto['sku']]['quantity'] += $produto['quantity'];
			$faturamentoVariacoesPorProdutos[$produto['title']]['variants'][$produto['sku']]['price'] += $produto['local_currency_item_total_price'];
		}

		// Ordena os produtos por nome produto pai e faturamento
		ksort($faturamentoVariacoesPorProdutos);

		return $faturamentoVariacoesPorProdutos;
	}
}
