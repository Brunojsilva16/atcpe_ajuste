<?php

namespace App\Core;

class Router
{
    private static $routes = [];

    public static function get($url, $controller)
    {
        self::$routes['GET'][$url] = $controller;
    }

    public static function post($url, $controller)
    {
        self::$routes['POST'][$url] = $controller;
    }

    public static function dispatch()
    {
        // 1. Obtém a URL da requisição
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // 2. Obtém o diretório do script atual (para lidar com subpastas)
        // Normaliza as barras para evitar erro no Windows
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        
        // 3. Remove o diretório base da URI se estiver rodando em subpasta
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }

        // 4. Limpeza final da URL
        // Remove barras duplicadas ou vazias e garante que começa com /
        $uri = '/' . ltrim($uri, '/');

        // --- BLOCO DE DEBUG (Descomente se continuar dando erro 404) ---
        // echo "<pre style='background:#f4f4f4; padding:10px; border:1px solid #ccc;'>";
        // echo "<strong>DEBUG ROUTER:</strong><br>";
        // echo "URI Original: " . $_SERVER['REQUEST_URI'] . "<br>";
        // echo "Script Dir: " . $scriptName . "<br>";
        // echo "Rota Calculada: " . $uri . "<br>";
        // echo "Método: " . $_SERVER['REQUEST_METHOD'] . "<br>";
        // echo "Rotas Registradas:<br>";
        // print_r(array_keys(self::$routes[$_SERVER['REQUEST_METHOD']] ?? []));
        // echo "</pre>";
        // exit; 
        // -------------------------------------------------------------

        $method = $_SERVER['REQUEST_METHOD'];

        if (isset(self::$routes[$method][$uri])) {
            $action = self::$routes[$method][$uri];
            self::executeAction($action);
        } else {
            // Tenta achar rota de erro 404 personalizada
            if (isset(self::$routes['GET']['/404'])) {
                self::executeAction(self::$routes['GET']['/404']);
            } else {
                http_response_code(404);
                echo "<h1>Erro 404</h1><p>A rota '<strong>{$uri}</strong>' não foi encontrada.</p>";
            }
        }
    }

    private static function executeAction($action)
    {
        if (is_array($action)) {
            $controllerName = $action[0];
            $methodName = $action[1];
        } elseif (is_string($action)) {
            $parts = explode('@', $action);
            $controllerName = "App\\Controllers\\" . $parts[0];
            $methodName = $parts[1];
        } else {
            die("Formato de rota inválido.");
        }

        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $methodName)) {
                $controller->$methodName();
            } else {
                die("Método {$methodName} não encontrado no controller {$controllerName}");
            }
        } else {
            die("Controller {$controllerName} não encontrado. Verifique o namespace.");
        }
    }
}