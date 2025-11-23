<?php

namespace App\View\Components\Charts;

use Illuminate\View\Component;

class Top5Produtos extends Component
{
	/**
	 * Array com os top 5 produtos mais faturados
	 *
	 * @var array
	 */
	public array $produtos;

	/**
	 * ID único do container do gráfico
	 *
	 * @var string
	 */
	public string $chartId;

	/**
	 * Array de labels (nomes dos produtos) para o gráfico
	 *
	 * @var array
	 */
	public array $labels;

	/**
	 * Array de receitas (valores) para o gráfico
	 *
	 * @var array
	 */
	public array $receitas;

	/**
	 * Cria uma nova instância do componente
	 *
	 * @param array $produtos
	 * @param string|null $chartId
	 */
	public function __construct(array $produtos, ?string $chartId = null)
	{
		$this->produtos = $produtos;
		$this->chartId = $chartId ?? 'top5-produtos-chart-' . uniqid();
		$this->labels = $this->getLabels();
		$this->receitas = $this->getReceitas();
	}

	/**
	 * Obtém os labels (nomes dos produtos) para o gráfico
	 * Trunca nomes muito longos para melhor visualização
	 *
	 * @return array
	 */
	public function getLabels(): array
	{
		$labels = [];
		foreach ($this->produtos as $produto) {
			$nome = $produto['name'];
			// Trunca nomes com mais de 30 caracteres
			$labels[] = mb_strlen($nome) > 30 ? mb_substr($nome, 0, 30) . '...' : $nome;
		}
		return $labels;
	}


	/**
	 * Obtém as receitas (valores) dos produtos para o gráfico
	 *
	 * @return array
	 */
	public function getReceitas(): array
	{
		return array_column($this->produtos, 'price');
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
		return view('components.charts.top-5-produtos');
	}
}
