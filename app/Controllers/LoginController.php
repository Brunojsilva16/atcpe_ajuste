<?php

namespace App\Controllers;

use App\Models\AssociadoModel;

class LoginControllerController extends BaseController
{

    public function login()
    {
        $this->render('pages/editar-perfil', [
            'title' => 'Editar perfil - ATCPE',
            'active' => 'editar-perfil',
            'pageScriptsHeader' => [
                'https://cdn.tailwindcss.com'
            ],
            'pageStyles' => [
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
                'css/home.css',
                'css/navbar.css',
                'css/editar-perfil.css',
                'css/footer.css'
            ],
            'pageScripts' => [
                'js/navbar.js'
            ]
        ]);
    }
}
