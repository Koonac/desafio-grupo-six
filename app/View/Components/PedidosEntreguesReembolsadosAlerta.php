<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PedidosEntreguesReembolsadosAlerta extends Component
{
	/**
	 * Array com os pedidos entregues e reembolsados
	 *
	 * @var array
	 */
	public array $pedidos;

	/**
	 * Total de pedidos entregues e reembolsados
	 *
	 * @var int
	 */
	public int $total;

	/**
	 * Valor total reembolsado desses pedidos
	 *
	 * @var float
	 */
	public float $valorTotalReembolsado;

	/**
	 * Cria uma nova instância do componente
	 *
	 * @param array $pedidos
	 */
	public function __construct(array $pedidos)
	{
		$this->pedidos = $pedidos;
		$this->total = count($pedidos);
		$this->valorTotalReembolsado = $this->calcularValorTotalReembolsado();
	}

	/**
	 * Calcula o valor total reembolsado dos pedidos entregues
	 *
	 * @return float
	 */
	private function calcularValorTotalReembolsado(): float
	{
		$total = 0;
		foreach ($this->pedidos as $pedido) {
			if (!empty($pedido['refunds']) && is_array($pedido['refunds'])) {
				$total += array_sum(array_column($pedido['refunds'], 'total_amount'));
			}
		}
		return $total;
	}

	/**
	 * Formata o valor monetário
	 *
	 * @param float $amount
	 * @param string $currency
	 * @return string
	 */
	public function formatCurrency(float $amount, string $currency = 'USD'): string
	{
		return number_format($amount, 2, ',', '.') . ' ' . $currency;
	}

	/**
	 * Obtém a view do componente
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return view('components.pedidos-entregues-reembolsados-alerta');
	}
}
