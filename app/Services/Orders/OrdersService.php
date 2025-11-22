<?php

namespace App\Services\Orders;

use App\Services\GrupoSix\GrupoSixApiService;

class OrdersService
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
	public function getOrders(): array
	{
		return $this->apiService->getOrders();
	}

	/**
	 * Obtém o total de pedidos
	 * 
	 * @return int
	 */
	public function getTotalOrders(): int
	{
		$orders = $this->getOrders();
		return count($orders['orders']);
	}
}
