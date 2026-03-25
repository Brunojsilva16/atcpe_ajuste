<?php

namespace App\Controllers;

use App\Models\AssociadoModel;

class SiteController extends BaseController
{
    public function home()
    {
        // Aqui você pode buscar dados para o carrossel ou notícias se necessário
        // Exemplo: $news = $this->newsModel->getLatest();

        $this->render('pages/home', [
            'title' => 'Home - ATCPE',
            'active' => 'home',
            'pageStyles' => [
                'css/home.css',
                'css/navbar.css',
                'css/carousel.css',
                'css/associe-se.css',
                'css/card_correction.css',
                'css/footer.css'
            ],
            'pageScripts' => [
                'js/carousel.js',
                'js/navbar.js'
            ]
        ]);
    }


    public function quemSomos()
    {
        $this->render('pages/quem-somos', [
            'title' => 'Quem Somos - ATCPE',
            'active' => 'quem-somos',
            'pageStyles' => [
                'css/home.css',
                'css/navbar.css',
                'css/quem-somos.css',
                'css/footer.css'
            ],
            'pageScripts' => [
                'js/navbar.js'
            ]
        ]);
    }

    public function beneficios()
    {
        $this->render('pages/beneficios', [
            'title' => 'Benefícios - ATCPE',
            'active' => 'beneficios',
            'pageStyles' => [
                'css/home.css',
                'css/navbar.css',
                'css/beneficios.css',
                'css/footer.css'
            ],
            'pageScripts' => [
                'js/navbar.js'
            ]
        ]);
    }

    public function gestao()
    {
        $this->render('pages/gestao', [
            'title' => 'Gestão - ATCPE',
            'active' => 'gestao',
            'pageStyles' => [
                'css/home.css',
                'css/navbar.css',
                'css/gestao.css',
                'css/footer.css'
            ],
            'pageScripts' => [
                'js/navbar.js'
            ]
        ]);
    }

    public function pesquisa()
    {
        $model = new AssociadoModel();

        // Se for uma requisição API (AJAX do DataTables), retorna JSON
        // Isso substitui o arquivo app/api/fetch_pesquisa.php
        if (isset($_GET['api']) && $_GET['api'] == 'true') {
            $termo = $_GET['search'] ?? '';
            $resultados = $model->getPublicList($termo);

            header('Content-Type: application/json');
            echo json_encode(['data' => $resultados]);
            exit;
        }

        // Renderiza a página normal
        $this->render('pages/pesquisa', [
            'title' => 'Encontre um Profissional - ATCPE',
            'active' => 'pesquisa',
            'pageStyles' => [
                'css/home.css',
                'css/navbar.css',
                'css/carousel.css',
                'css/footer.css'
            ],
            'pageScripts' => [
                'js/pesquisa.js',
                'js/navbar.js'
            ]
        ]);
    }
}
