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
		$totalReembolsos = $this->metricasPedidosService->getTotalReembolsos();
		$totalClientesUnicos = $this->metricasPedidosService->getTotalClientesUnicos();
		$produtoMaisVendido = $this->metricasProdutosPedidosService->getProdutoMaisVendido();
		$produtoMaisFaturado = $this->metricasProdutosPedidosService->getProdutoMaisFaturado();
		return view('dashboard', compact(
			'pedidos',
			'totalPedidos',
			'totalReembolsos',
			'totalClientesUnicos',
			'produtoMaisVendido',
			'produtoMaisFaturado'
		));
	}
}
