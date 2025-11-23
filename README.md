# 📊 Dashboard de Análise de Pedidos - Grupo Six

## 📝 Sobre o Projeto

Este projeto é um **dashboard de análise de pedidos** desenvolvido como desafio técnico para a vaga de Desenvolvedor Backend Pleno no Grupo Six. O sistema consome dados de uma API externa (Cartpanda) e apresenta análises relevantes sobre pedidos, produtos e clientes em um dashboard interativo.

O sistema processa dados de pedidos da API, calcula métricas financeiras (receita total, reembolsos, receita líquida), analisa produtos (mais vendidos, mais faturados, taxa de reembolso), identifica padrões de vendas por localização e horário, e apresenta tudo isso em um dashboard visual e intuitivo.

---

## 🛠️ Tecnologias Utilizadas

### Backend

-   **PHP 8.2+** - Linguagem de programação
-   **Laravel 12** - Framework PHP
-   **Laravel Cache** - Sistema de cache para otimização de performance

### Frontend

-   **Blade** - Template engine do Laravel
-   **Tailwind CSS 4.0** - Framework CSS utilitário para estilização
-   **Chart.js 4.5** - Biblioteca JavaScript para criação de gráficos e visualizações
-   **Vite 7.0** - Build tool e dev server

### Arquitetura

-   **Service Layer Pattern** - Separação de lógica de negócio em Services
-   **Dependency Injection** - Injeção de dependências para melhor testabilidade
-   **Cache Strategy** - Implementação de cache para otimização de requisições à API externa

---

## 🏗️ Arquitetura do Sistema

O projeto segue uma arquitetura em camadas com separação clara de responsabilidades, facilitando manutenção, testes e escalabilidade.

### Controllers

Camada fina responsável por receber requisições HTTP, coordenar a execução dos serviços e retornar as respostas adequadas. Toda a lógica de negócio é delegada para os Services.

-   **`DashboardController`**: Gerencia as rotas do dashboard principal e atualização de dados
-   **`PedidosController`**: Gerencia a exibição da tabela de pedidos com paginação

### Services

Contêm toda a lógica de negócio do sistema, seguindo o padrão **Service Layer** e utilizando **Dependency Injection**.

-   **`GrupoSixApiService`**: Comunicação com a API externa (Cartpanda) e gerenciamento de cache (TTL de 60 minutos), com tratamento de erros e retry automático
-   **`MetricasPedidosService`**: Cálculo de métricas relacionadas a pedidos, clientes e análises temporais (vendas por dia/horário, ticket médio, top cidades)
-   **`MetricasProdutosPedidosService`**: Cálculo de métricas relacionadas a produtos (mais vendidos, mais faturados, taxa de reembolso, faturamento por variações)

### View/Components

Componentes Blade reutilizáveis que encapsulam lógica de apresentação e renderização.

-   **Componentes de Gráficos**: `Top5Produtos`, `FaturamentoVariantes`, `VendasPorHorario`, `VendasTemporais`
-   **Componentes de UI**: `TabelaPedidos` (com paginação e formatação), `PedidosEntreguesReembolsadosAlerta`

### Fluxo de Dados

```
Requisição HTTP → Controller → Service → GrupoSixApiService (API/Cache)
→ Processamento de Métricas → View/Component → Resposta HTML
```

### Princípios de Design

-   **Single Responsibility**: Cada classe com responsabilidade única
-   **Dependency Inversion**: Controllers dependem de abstrações (Services)
-   **Separation of Concerns**: Lógica, apresentação e roteamento separados
-   **DRY**: Lógica reutilizável encapsulada em Services e Components

---

## 🚀 Como Iniciar o Projeto

### Pré-requisitos

-   PHP 8.2 ou superior
-   Composer
-   Node.js e npm

### Instalação

1.  **Clone o repositório** (se aplicável) ou navegue até o diretório do projeto:

```bash
cd desafio-grupo-six
```

2.  **Instale as dependências do PHP**:

```bash
composer install
```

3.  **Configure o arquivo de ambiente**:

```bash
cp .env.example .env
```

4.  **Gere a chave de aplicação**:

```bash
php artisan key:generate
```

5.  **Instale as dependências do Node.js**:

```bash
npm install
```

6.  **Compile os assets** (CSS e JavaScript):

```bash
npm run build
```

**Alternativa**: Você pode usar o script de setup automatizado que executa todos os passos acima:

```bash
composer run setup
```

### Executando o Projeto

#### Modo Desenvolvimento

Para executar o projeto em modo desenvolvimento com hot-reload:

```bash
composer run dev
```

Este comando inicia simultaneamente:

-   Servidor Laravel (`php artisan serve`)
-   Servidor Vite para hot-reload (`npm run dev`)

### Acessando o Dashboard

Após iniciar o servidor, acesse:

-   **Dashboard**: `http://localhost:8000`
-   **Tabela de Pedidos**: `http://localhost:8000/pedidos`

### Atualizando Dados da API

O sistema utiliza cache para otimizar performance. Para forçar a atualização dos dados da API, acesse:

-   **Atualizar Dados**: `http://localhost:8000/dashboard/refresh`

Ou clique no botão "Atualizar Dados" no dashboard.
