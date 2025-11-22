<?php

namespace App\Http\Controllers;

use App\Services\Pedidos\PedidosService;
use Illuminate\View\View;

class DashboardController extends Controller
{
	/**
	 * @var PedidosService
	 */
	private PedidosService $pedidosService;

	/**
	 * Construtor da classe
	 */
	public function __construct(PedidosService $pedidosService)
	{
		$this->pedidosService = $pedidosService;
	}

	/**
	 * Exibe o dashboard
	 * 
	 * @return View
	 */
	public function index(): View
	{
		$pedidos = $this->pedidosService->getPedidos();
		$totalPedidos = $this->pedidosService->getTotalPedidos();
		$totalReembolsos = $this->pedidosService->getTotalReembolsos();
		$totalClientesUnicos = $this->pedidosService->getTotalClientesUnicos();
		$produtoMaisVendido = $this->pedidosService->getProdutoMaisVendido();
		$produtoMaisFaturado = $this->pedidosService->getProdutoMaisFaturado();
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
