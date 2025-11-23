<?php

namespace App\View\Components\Charts;

use Illuminate\View\Component;

class FaturamentoVariantes extends Component
{
	/**
	 * Array com os produtos e suas variantes
	 *
	 * @var array
	 */
	public array $produtos;

	/**
	 * Cria uma nova instância do componente
	 *
	 * @param array $produtos
	 */
	public function __construct(array $produtos)
	{
		$this->produtos = $produtos;
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
	 * Prepara os dados das variantes para o gráfico de pizza
	 *
	 * @param array $variants
	 * @return array
	 */
	public function prepareVariantsData(array $variants): array
	{
		$labels = [];
		$values = [];
		$colors = [];

		// Cores para as variantes (tons de azul/verde)
		$colorPalette = [
			'rgba(15, 117, 189, 0.8)',
			'rgba(16, 185, 129, 0.8)',
			'rgba(34, 197, 94, 0.8)',
			'rgba(74, 222, 128, 0.8)',
			'rgba(134, 239, 172, 0.8)',
			'rgba(59, 130, 246, 0.8)',
			'rgba(10, 90, 148, 0.8)',
			'rgba(5, 150, 105, 0.8)',
		];

		// Ordena os variants por valor faturado
		usort($variants, function ($a, $b) {
			return $b['price'] - $a['price'];
		});

		$index = 0;
		foreach ($variants as $variant) {
			$variantTitle = $variant['variant_title'];
			$labels[] = mb_strlen($variantTitle) > 30 ? mb_substr($variantTitle, 0, 30) . '...' : $variantTitle;
			$values[] = (float) $variant['price'];
			$colors[] = $colorPalette[$index % count($colorPalette)];
			$index++;
		}

		return [
			'labels' => $labels,
			'values' => $values,
			'colors' => $colors,
		];
	}

	/**
	 * Obtém a view do componente
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return view('components.charts.faturamento-variantes');
	}
}

