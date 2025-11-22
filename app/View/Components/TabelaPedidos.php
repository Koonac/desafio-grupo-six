<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TabelaPedidos extends Component
{
	/**
	 * Array de pedidos
	 *
	 * @var array
	 */
	public array $pedidos;

	/**
	 * Array de informações de paginação
	 *
	 * @var array
	 */
	public array $pagination;

	/**
	 * Cria uma nova instância do componente
	 *
	 * @param array $pedidos
	 * @param array $pagination
	 */
	public function __construct(array $pedidos, array $pagination)
	{
		$this->pedidos = $pedidos;
		$this->pagination = $pagination;
	}

	/**
	 * Obtém a classe CSS para o status de pagamento
	 *
	 * @param string $status
	 * @return string
	 */
	public function getPaymentStatusClass(string $status): string
	{
		$colors = [
			'1' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
			'3' => 'bg-green-500/20 text-green-300 border-green-500/30',
			'4' => 'bg-red-500/20 text-red-300 border-red-500/30',
			'5' => 'bg-gray-500/20 text-gray-300 border-gray-500/30',
		];

		return $colors[$status] ?? $colors[5]; // 5 = default
	}

	/**
	 * Obtém o label do status de pagamento
	 *
	 * @param string $status
	 * @return string
	 */
	public function getPaymentStatusLabel(string $status): string
	{
		$labels = [
			'1' => 'Pendente',
			'3' => 'Pago',
			'4' => 'Cancelado',
			'5' => 'N/A',
		];

		return $labels[$status] ?? $labels[5]; // 5 = default
	}

	/**
	 * Obtém a classe CSS para o status de entrega
	 *
	 * @param string $status
	 * @return string
	 */
	public function getFulfillmentStatusClass(string $status): string
	{
		$colors = [
			'Fully Fulfilled' => 'bg-green-500/20 text-green-300 border-green-500/30',
			'Partially Fulfilled' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
			'Unfulfilled' => 'bg-red-500/20 text-red-300 border-red-500/30',
		];
		return $colors[$status] ?? 'bg-gray-500/20 text-gray-300 border-gray-500/30';
	}

	/**
	 * Obtém o label do status de entrega
	 *
	 * @param string $status
	 * @return string
	 */
	public function getFulfillmentStatusLabel(string $status): string
	{
		$labels = [
			'Fully Fulfilled' => 'Entregue',
			'Partially Fulfilled' => 'Parcialmente Entregue',
			'Unfulfilled' => 'Não Entregue',
			'N/A' => 'N/A',
		];

		return $labels[$status] ?? $labels['N/A']; // N/A = default
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
	 * Formata a data
	 *
	 * @param string|null $date
	 * @return string
	 */
	public function formatDate(?string $date): string
	{
		if (!$date) {
			return 'N/A';
		}

		try {
			$dateTime = new \DateTime($date);
			return $dateTime->format('d/m/Y H:i');
		} catch (\Exception $e) {
			return $date;
		}
	}

	/**
	 * Obtém a view do componente
	 *
	 */
	public function render()
	{
		return view('components.tabela-pedidos');
	}
}
