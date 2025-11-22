<?php

namespace App\Http\Controllers;

use App\Services\Pedidos\MetricasPedidosService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PedidosController extends Controller
{
	/**
	 * @var MetricasPedidosService
	 */
	private MetricasPedidosService $metricasPedidosService;

	/**
	 * Construtor da classe
	 */
	public function __construct(MetricasPedidosService $metricasPedidosService)
	{
		$this->metricasPedidosService = $metricasPedidosService;
	}

	/**
	 * Exibe a tabela de pedidos com paginação
	 * 
	 * @param Request $request
	 * @return View
	 */
	public function index(Request $request): View
	{
		$pedidos = $this->metricasPedidosService->getPedidos();

		// Ordena os pedidos por data (mais recentes primeiro)
		usort($pedidos, function ($a, $b) {
			$dateA = isset($a['created_at']) ? strtotime($a['created_at']) : 0;
			$dateB = isset($b['created_at']) ? strtotime($b['created_at']) : 0;
			return $dateB - $dateA;
		});

		// Configuração de paginação
		$perPage = $request->get('perPage', 10);
		$total = count($pedidos);
		$currentPage = (int) $request->get('page', 1);
		$totalPages = (int) ceil($total / $perPage);

		// Garante que a página atual está dentro dos limites
		if ($currentPage < 1) {
			$currentPage = 1;
		}
		if ($currentPage > $totalPages && $totalPages > 0) {
			$currentPage = $totalPages;
		}

		// Calcula os itens da página atual
		$offset = ($currentPage - 1) * $perPage;
		$pedidosPaginated = array_slice($pedidos, $offset, $perPage);

		// Calcula firstItem e lastItem
		$firstItem = $total > 0 ? $offset + 1 : 0;
		$lastItem = min($offset + $perPage, $total);

		// Constrói as URLs de paginação
		$baseUrl = $request->url();
		$queryParams = $request->except('page');


		/****************************/
		/*********** URLs ***********/
		/****************************/
		// URL anterior
		$previousUrl = null;
		if ($currentPage > 1) {
			$prevQuery = array_merge($queryParams, ['page' => $currentPage - 1]);
			$previousUrl = $baseUrl . '?' . http_build_query($prevQuery);
		}

		// URL próxima
		$nextUrl = null;
		if ($currentPage < $totalPages) {
			$nextQuery = array_merge($queryParams, ['page' => $currentPage + 1]);
			$nextUrl = $baseUrl . '?' . http_build_query($nextQuery);
		}

		// URLs das páginas
		$pageUrls = [];
		$startPage = max(1, $currentPage - 2);
		$endPage = min($totalPages, $currentPage + 2);

		for ($page = $startPage; $page <= $endPage; $page++) {
			$pageQuery = array_merge($queryParams, ['page' => $page]);
			$pageUrls[$page] = $baseUrl . '?' . http_build_query($pageQuery);
		}

		return view('pedidos', [
			'pedidos' => $pedidosPaginated,
			'pagination' => [
				'currentPage' => $currentPage,
				'totalPages' => $totalPages,
				'total' => $total,
				'perPage' => $perPage,
				'firstItem' => $firstItem,
				'lastItem' => $lastItem,
				'hasPages' => $totalPages > 1,
				'onFirstPage' => $currentPage == 1,
				'hasMorePages' => $currentPage < $totalPages,
				'previousUrl' => $previousUrl,
				'nextUrl' => $nextUrl,
				'pageUrls' => $pageUrls,
			],
		]);
	}
}
