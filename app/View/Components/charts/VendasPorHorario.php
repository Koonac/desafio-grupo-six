<?php

namespace App\View\Components\Charts;

use Illuminate\View\Component;

class VendasPorHorario extends Component
{
	/**
	 * Array com as vendas por horário
	 *
	 * @var array
	 */
	public array $vendasPorHorario;

	/**
	 * Array com informações do melhor horário
	 *
	 * @var array
	 */
	public array $melhorHorario;

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
	 * Dados de vendas por horário
	 *
	 * @var array
	 */
	public array $vendas;

	/**
	 * Cria uma nova instância do componente
	 *
	 * @param array $vendasPorHorario
	 * @param array $melhorHorario
	 * @param string|null $chartId
	 */
	public function __construct(array $vendasPorHorario, array $melhorHorario, ?string $chartId = null)
	{
		$this->vendasPorHorario = $vendasPorHorario;
		$this->melhorHorario = $melhorHorario;
		$this->chartId = $chartId ?? 'vendas-por-horario-chart-' . uniqid();
		$this->horarios = $this->getHorarios();
		$this->vendas = $this->getVendas();
	}

	/**
	 * Obtém os labels dos horários (00:00 a 23:00)
	 *
	 * @return array
	 */
	public function getHorarios(): array
	{
		$horarios = [];
		// Garante que as horas estão ordenadas de 0 a 23
		for ($hora = 0; $hora < 24; $hora++) {
			if (isset($this->vendasPorHorario[$hora])) {
				$horarios[] = $this->vendasPorHorario[$hora]['label'];
			} else {
				$horarios[] = sprintf('%02d:00', $hora);
			}
		}
		return $horarios;
	}

	/**
	 * Obtém os dados de vendas por horário
	 *
	 * @return array
	 */
	public function getVendas(): array
	{
		$vendas = [];
		// Garante que as horas estão ordenadas de 0 a 23
		for ($hora = 0; $hora < 24; $hora++) {
			if (isset($this->vendasPorHorario[$hora])) {
				$vendas[] = $this->vendasPorHorario[$hora]['qtdeVendas'];
			} else {
				$vendas[] = 0;
			}
		}
		return $vendas;
	}

	/**
	 * Obtém a view do componente
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return view('components.charts.vendas-por-horario');
	}
}
