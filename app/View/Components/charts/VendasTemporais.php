<?php

namespace App\View\Components\Charts;

use Illuminate\View\Component;

class VendasTemporais extends Component
{
	/**
	 * Array com as vendas por dia da semana
	 *
	 * @var array
	 */
	public array $vendasPorDiaDaSemana;

	/**
	 * Array com informações do melhor dia da semana
	 *
	 * @var array
	 */
	public array $melhorDiaDaSemana;

	/**
	 * ID único do container do gráfico
	 *
	 * @var string
	 */
	public string $chartId;

	/**
	 * Labels dos horários (00:00 a 23:00)
	 *
	 * @var array
	 */
	public array $horarios;

	/**
	 * Datasets para o gráfico (um para cada dia)
	 *
	 * @var array
	 */
	public array $datasets;

	/**
	 * Cria uma nova instância do componente
	 *
	 * @param array $vendasPorDiaDaSemana
	 * @param array $melhorDiaDaSemana
	 * @param string|null $chartId
	 */
	public function __construct(array $vendasPorDiaDaSemana, array $melhorDiaDaSemana, ?string $chartId = null)
	{
		$this->vendasPorDiaDaSemana = $vendasPorDiaDaSemana;
		$this->melhorDiaDaSemana = $melhorDiaDaSemana;
		$this->chartId = $chartId ?? 'vendas-temporais-chart-' . uniqid();
		$this->horarios = $this->getHorarios();
		$this->datasets = $this->prepareDatasets();
	}

	/**
	 * Obtém os labels dos horários (00:00 a 23:00)
	 *
	 * @return array
	 */
	public function getHorarios(): array
	{
		$horarios = [];
		for ($hora = 0; $hora < 24; $hora++) {
			$horarios[] = sprintf('%02d:00', $hora);
		}
		return $horarios;
	}

	/**
	 * Prepara os datasets para cada dia da semana
	 *
	 * @return array
	 */
	public function prepareDatasets(): array
	{
		$datasets = [];
		$diasDaSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

		// Cores para cada dia da semana
		$cores = [
			'rgba(15, 117, 189, 0.8)',   // Domingo - Azul
			'rgba(16, 185, 129, 0.8)',   // Segunda - Verde
			'rgba(34, 197, 94, 0.8)',    // Terça - Verde claro
			'rgba(74, 222, 128, 0.8)',   // Quarta - Verde médio
			'rgba(134, 239, 172, 0.8)',  // Quinta - Verde claro
			'rgba(59, 130, 246, 0.8)',   // Sexta - Azul claro
			'rgba(10, 90, 148, 0.8)',    // Sábado - Azul escuro
		];

		$bordas = [
			'rgba(15, 117, 189, 1)',
			'rgba(16, 185, 129, 1)',
			'rgba(34, 197, 94, 1)',
			'rgba(74, 222, 128, 1)',
			'rgba(134, 239, 172, 1)',
			'rgba(59, 130, 246, 1)',
			'rgba(10, 90, 148, 1)',
		];

		foreach ($this->vendasPorDiaDaSemana as $index => $dia) {
			$dados = [];
			// Garante que as horas estão ordenadas de 0 a 23
			for ($hora = 0; $hora < 24; $hora++) {
				if (isset($dia['horas'][$hora])) {
					$dados[] = $dia['horas'][$hora]['qtdeVendas'];
				} else {
					$dados[] = 0;
				}
			}

			$datasets[] = [
				'label' => $diasDaSemana[$index],
				'data' => $dados,
				'borderColor' => $bordas[$index],
				'backgroundColor' => $cores[$index],
				'tension' => 0.4,
				'fill' => false,
			];
		}

		return $datasets;
	}

	/**
	 * Obtém a view do componente
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return view('components.charts.vendas-temporais');
	}
}
