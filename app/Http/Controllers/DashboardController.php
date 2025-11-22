<?php

namespace App\Http\Controllers;

use App\Services\Pedidos\MetricasPedidosService;
use App\Services\Pedidos\MetricasProdutosPedidosService;
use Illuminate\View\View;

class DashboardController extends Controller
{
	/**
	 * @var MetricasPedidosService
	 */
	private MetricasPedidosService $metricasPedidosService;

	/**
	 * @var MetricasProdutosPedidosService
	 */
	private MetricasProdutosPedidosService $metricasProdutosPedidosService;

	/**
	 * Construtor da classe
	 */
	public function __construct(
		MetricasPedidosService $metricasPedidosService,
		MetricasProdutosPedidosService $metricasProdutosPedidosService
	) {
		$this->metricasPedidosService = $metricasPedidosService;
		$this->metricasProdutosPedidosService = $metricasProdutosPedidosService;
	}

	/**
	 * Exibe o dashboard
	 * 
	 * @return View
	 */
	public function index(): View
	{
		$pedidos = $this->metricasPedidosService->getPedidos();
		$totalPedidos = $this->metricasPedidosService->getTotalPedidos();
		$totalReceitaUSD = $this->metricasPedidosService->getTotalReceitaUSD();
		$totalReceitaBRL = $this->metricasPedidosService->getTotalReceitaBRL();
		$totalPedidosEntregues = $this->metricasPedidosService->getTotalPedidosEntregues();
		$taxaPedidosEntregues = $this->metricasPedidosService->getTaxaPedidosEntregues();
		$totalClientesUnicos = $this->metricasPedidosService->getTotalClientesUnicos();
		$mediaPedidosPorCliente = $this->metricasPedidosService->getMediaPedidosPorCliente();
		$totalReembolsosUSD = $this->metricasPedidosService->getTotalReembolsosUSD();
		$totalReembolsosBRL = $this->metricasPedidosService->getTotalReembolsosBRL();
		$taxaReembolso = $this->metricasPedidosService->getTaxaReembolso();
		$totalPedidosReembolsados = $this->metricasPedidosService->getTotalPedidosReembolsados();
		$receitaLiquidaUSD = $this->metricasPedidosService->getReceitaLiquidaUSD();
		$receitaLiquidaBRL = $this->metricasPedidosService->getReceitaLiquidaBRL();
		$produtoMaisVendido = $this->metricasProdutosPedidosService->getProdutoMaisVendido();

		$produtoMaisFaturado = $this->metricasProdutosPedidosService->getProdutoMaisFaturado();

		return view('dashboard', compact(
			'pedidos',
			'totalPedidos',
			'totalReceitaUSD',
			'totalReceitaBRL',
			'totalPedidosEntregues',
			'taxaPedidosEntregues',
			'totalClientesUnicos',
			'mediaPedidosPorCliente',
			'totalReembolsosBRL',
			'totalReembolsosUSD',
			'taxaReembolso',
			'totalPedidosReembolsados',
			'receitaLiquidaUSD',
			'receitaLiquidaBRL',
			'produtoMaisVendido',

			'produtoMaisFaturado'
		));
	}
}
