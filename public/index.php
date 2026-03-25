<?php

// Inicia a sessão no topo de tudo
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Carrega o Autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// --- CONFIGURAÇÃO DE AMBIENTE (.ENV) ---
// Define a raiz do projeto (um nível acima de public) para encontrar o arquivo .env
define('ROOT_PATH', dirname(__DIR__));

// Carrega as variáveis de ambiente se a biblioteca estiver instalada
if (class_exists('Dotenv\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
    $dotenv->safeLoad();
}

use App\Core\Router;

// --- OBRIGATÓRIO: CÁLCULO DA URL BASE ---
// Isso garante que o site funcione tanto em localhost/projeto quanto em site.com
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = rtrim($scriptName, '/');

// Define a CONSTANTE que o layout.php vai usar
define('URL_BASE', $baseUrl);

// --- ROTAS ---
require_once __DIR__ . '/../app/Routes/routes.php';

// --- DISPARAR ROTEADOR ---
Router::dispatch();