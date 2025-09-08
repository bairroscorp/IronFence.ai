# IronFence.ai - Gerenciamento Avançado de Tarefas

## Visão Geral

Este projeto é uma aplicação de **gerenciamento de tarefas** avançada, construída com:

- **Backend:** Laravel 12 / PHP 8.3
- **Frontend:** Vue.js 3 + Inertia.js
- **Banco de Dados:** SQLite (simples e fácil de testar)
- **Cache:** Redis
- **WebSockets:** Pusher
- **Node.js:** v22
- **Dependências Frontend:** Vite, npm

O sistema inclui autenticação, CRUD de tarefas, dashboard Kanban interativo, notificações em tempo real e alertas inteligentes de feriados via Redis.

---

## Pré-requisitos

Certifique-se de ter instalado:

- **PHP 8.3** com extensões: `pdo_sqlite`, `curl`, `mbstring`, `redis`
- **Composer**
- **Node.js v22** e **npm**
- **Redis** (local ou remoto)
- **Servidor Web**: Apache / Nginx (ou Laravel Sail)

---

## Instalação

1. Clone o repositório:

```bash
git clone https://github.com/seu-usuario/ironfence.ai.git
cd ironfence.ai
Instale dependências PHP via Composer:

bash
Copiar código
composer install
Instale dependências frontend:

bash
Copiar código
npm install
Build do frontend:

bash
Copiar código
npm run build
# ou para desenvolvimento com hot reload
npm run dev
Copie o arquivo .env.example para .env:

bash
Copiar código
cp .env.example .env
Gere a chave do aplicativo Laravel:

bash
Copiar código
php artisan key:generate
Configuração do .env
Exemplo completo do .env para este projeto:

env
Copiar código
APP_NAME=IronFence.ai
APP_ENV=local
APP_KEY=base64:gerada_pelo_key:generate
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=/caminho/para/database.sqlite

BROADCAST_DRIVER=pusher
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Pusher
PUSHER_APP_ID=59966734-f8b8-472e-9c6e-4f40b92da751
PUSHER_APP_KEY=B567FDF498363505595F5DEDACE1F85DB286AE422A5969C203D481AE809F37FB
PUSHER_APP_SECRET=ABDC383CE9376EEBDE3A8DEF5E624B37EE5A96DE92FA6B7AE9B706323F275613
PUSHER_APP_CLUSTER=mt1
PUSHER_APP_USE_TLS=true

# Variáveis expostas para frontend (Vite)
VITE_PUSHER_APP_KEY=${PUSHER_APP_KEY}
VITE_PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}

# API de feriados
INVERTEXTO_API_KEY=SEU_TOKEN_AQUI
Certifique-se de criar o arquivo SQLite vazio ou apontar para o caminho correto.
Sempre rode php artisan config:clear e php artisan cache:clear após editar o .env.

Configuração Backend
Cache Redis: Laravel já está configurado para usar Redis (CACHE_DRIVER=redis)

Queue Redis: para notificações assíncronas (QUEUE_CONNECTION=redis)

Broadcasting: Pusher configurado no config/broadcasting.php

Eventos: TarefaAtribuida envia notificações via WebSocket e e-mail

Serviço de alerta de feriados: FeriadoService consulta API e usa cache Redis para evitar chamadas desnecessárias.

Configuração Frontend
No arquivo resources/js/app.ts:

ts
Copiar código
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});
O Echo escuta canais privados para notificações de tarefas atribuídas.

O Vue.js está configurado com Inertia.js, i18n (pt/en) e tema light/dark.

Funcionalidades
Autenticação: registro, login e proteção de rotas.

Tarefas CRUD: título, descrição, data de vencimento, status, prioridade, usuário responsável.

Dashboard Kanban: arrastar e soltar, alterar status via select/combobox.

Notificações em tempo real: via Pusher/Echo.

Emails automáticos: quando uma tarefa é atribuída a outro usuário.

Serviço de alerta inteligente: verifica feriados via API e cache Redis.

Testando Redis e API de Feriados
Teste Redis:

bash
Copiar código
php artisan tinker
Cache::store('redis')->put('teste', 'ok', 60);
Cache::store('redis')->get('teste'); // deve retornar 'ok'
Teste API de feriados:

php
Copiar código
$service = new \App\Services\FeriadoService();
$feriado = $service->isFeriado('2025-09-07', 'SP'); // true/false
O serviço garante sempre retornar um array, mesmo que a API falhe, evitando erros de foreach().

Rodando o servidor
bash
Copiar código
php artisan serve
npm run dev
Laravel rodará em http://127.0.0.1:8000

Vite dev server para hot reload em http://localhost:5173 (ou porta configurada)

Notas Finais
Use Redis para cache de feriados e fila de notificações.

Use Pusher para eventos em tempo real.

Certifique-se de que variáveis com VITE_ estão no .env para o frontend.

Limpe cache/config sempre que alterar .env:

bash
Copiar código
php artisan config:clear
php artisan cache:clear
Para produção, ajuste APP_ENV=production e APP_DEBUG=false.

A API de feriados exige uma chave válida (INVERTEXTO_API_KEY) no .env.

O frontend Vue usa i18n para suporte multilíngue (pt/en) e alternância de temas.

Licença
MIT
