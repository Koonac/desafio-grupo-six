<?php

namespace App\Http\Controllers;

use App\Services\Orders\OrdersService;
use Illuminate\View\View;

class DashboardController extends Controller
{
	/**
	 * @var OrdersService
	 */
	private OrdersService $ordersService;

	/**
	 * Construtor da classe
	 */
	public function __construct(OrdersService $ordersService)
	{
		$this->ordersService = $ordersService;
	}

	/**
	 * Exibe o dashboard
	 * 
	 * @return View
	 */
	public function index(): View
	{
		$orders = $this->ordersService->getOrders();
		$totalOrders = $this->ordersService->getTotalOrders();

		return view('dashboard', compact('orders', 'totalOrders'));
	}
}
