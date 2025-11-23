<?php

namespace App\Services\GrupoSix;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GrupoSixApiService
{
	/**
	 * Tempo de cache padrão em minutos
	 */
	private const CACHE_TTL = 60; // 1 hora

	/**
	 * Chave do cache
	 */
	private const CACHE_KEY = 'gruposix_orders';

	/**
	 * Busca os pedidos da API Cartpanda
	 * 
	 * Implementa cache para otimizar performance e evitar chamadas repetidas à API.
	 * 
	 * @param bool $forceRefresh Força a atualização do cache, ignorando dados em cache
	 * @return array Dados brutos dos pedidos retornados pela API
	 * @throws Exception Se a requisição falhar
	 */
	public function getOrders(bool $forceRefresh = false): array
	{
		try {
			// Verifica se há dados em cache e não está forçando refresh
			if (!$forceRefresh && $this->hasCache()) {
				Log::info('GrupoSixApiService: Retornando dados do cache');
				return $this->getCache();
			}

			Log::info('GrupoSixApiService: Fazendo requisição à API', ['url' => config('app.grupo_six_api_url')]);

			$response = Http::timeout(30)
				->retry(3, 100) // 3 tentativas com 100ms de delay entre elas
				->get(config('app.grupo_six_api_url'));

			// Verifica se a requisição foi bem-sucedida
			if (!$response->successful()) {
				Log::error('GrupoSixApiService: Erro na requisição à API', [
					'status' => $response->status(),
					'body' => $response->body()
				]);

				// Se houver cache antigo, retorna ele em caso de erro
				if ($this->hasCache()) {
					Log::warning('GrupoSixApiService: Retornando cache antigo devido a erro na API');
					return $this->getCache();
				}

				throw new Exception(
					"Erro ao buscar dados da API. Status: {$response->status()}"
				);
			}

			$data = $response->json();

			// Valida se a resposta contém a estrutura esperada
			if (!isset($data['orders']) || !is_array($data['orders'])) {
				Log::error('GrupoSixApiService: Estrutura de dados inválida', ['data' => $data]);

				// Se houver cache antigo, retorna ele
				if ($this->hasCache()) {
					Log::warning('GrupoSixApiService: Retornando cache antigo devido a estrutura inválida');
					return $this->getCache();
				}

				throw new Exception('Resposta da API não contém a estrutura esperada de pedidos');
			}

			// Armazena no cache
			Cache::put(self::CACHE_KEY, $data, now()->addMinutes(self::CACHE_TTL));

			Log::info('GrupoSixApiService: Dados obtidos e armazenados no cache', [
				'orders_count' => count($data['orders'])
			]);

			return $data;
		} catch (Exception $e) {
			Log::error('GrupoSixApiService: Exceção ao buscar dados', [
				'message' => $e->getMessage(),
				'trace' => $e->getTraceAsString()
			]);

			// Se houver cache antigo, retorna ele em caso de exceção
			if ($this->hasCache()) {
				Log::warning('GrupoSixApiService: Retornando cache antigo devido a exceção');
				return $this->getCache();
			}

			throw $e;
		}
	}

	/**
	 * Limpa o cache dos pedidos
	 * 
	 * @return array
	 */
	public function getCache(): array
	{
		return Cache::get(self::CACHE_KEY);
	}

	/**
	 * Verifica se há dados em cache
	 * 
	 * @return bool
	 */
	public function hasCache(): bool
	{
		return Cache::has(self::CACHE_KEY);
	}
}
