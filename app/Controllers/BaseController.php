<?php

namespace App\Controllers;

class BaseController
{
    /**
     * Renderiza uma view com dados opcionais
     */
    protected function render($viewPath, $data = [])
    {
        extract($data);

        // Inicia buffer para capturar o conteúdo da view
        ob_start();
        
        // Verifica se o arquivo da view existe
        $viewFile = __DIR__ . "/../Views/{$viewPath}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            // Fallback para .phtml se necessário
            $viewFilePhtml = __DIR__ . "/../Views/{$viewPath}.phtml";
            if (file_exists($viewFilePhtml)) {
                require $viewFilePhtml;
            } else {
                echo "View não encontrada: {$viewPath}";
            }
        }
        
        $content = ob_get_clean();

        // Carrega o layout principal e injeta o conteúdo
        require __DIR__ . "/../Views/layout.php";
    }

    /**
     * Redireciona para uma URL interna ou externa
     */
    protected function redirect($url)
    {
        // Se for uma URL completa (http/https), redireciona direto
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            header("Location: " . $url);
            exit;
        }

        // Limpa barras duplicadas no início
        $url = ltrim($url, '/');

        // Usa a constante URL_BASE definida no index.php
        $base = defined('URL_BASE') ? URL_BASE : '';

        // Redireciona concatenando a base do projeto
        header("Location: " . $base . '/' . $url);
        exit;
    }

    /**
     * Retorna resposta JSON (útil para APIs/AJAX)
     */
    protected function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}