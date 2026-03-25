<?php

namespace App\Controllers;

class ErrorController extends BaseController
{
    public function notFound()
    {
        // Define o código de resposta HTTP como 404
        http_response_code(404);
        
        // Renderiza a view de erro (certifique-se de que app/Views/pages/404.php existe)
        // Se não existir, crie um arquivo simples ou use 'pages/home' temporariamente para testar
        $this->render('pages/404', [
            'title' => 'Página não encontrada - 404'
        ]);
    }
}