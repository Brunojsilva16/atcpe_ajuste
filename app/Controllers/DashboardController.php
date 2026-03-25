<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\AssociadoModel;

class DashboardController extends BaseController
{
    private $associadoModel;

    public function __construct()
    {
        $this->associadoModel = new AssociadoModel();
    }

    public function index()
    {
        Auth::init();
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $userId = $_SESSION['user_id'];
        $currentUser = $this->associadoModel->findById($userId);

        if (!$currentUser) {
            Auth::logout();
            $this->redirect('/login');
            return;
        }

        // --- LÓGICA DE ADMIN (Nível > 2) ---
        if (Auth::level() > 2) {
            $filter = $_GET['status'] ?? 'geral';
            
            // --- AJUSTE: Configurações de Paginação Dinâmica ---
            // Captura o limite da URL ou define 10 como padrão
            $itemsPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            // Proteção para aceitar apenas valores permitidos
            if (!in_array($itemsPerPage, [10, 25, 50, 80, 100, 200])) {
                $itemsPerPage = 10;
            }

            $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $offset = ($currentPage - 1) * $itemsPerPage;

            // Busca total de registros para o filtro atual
            $totalItems = $this->associadoModel->countAllAssociates($filter);
            $totalPages = max(1, ceil($totalItems / $itemsPerPage));

            // Garante que a página atual não ultrapasse o total após mudança de limite
            if ($currentPage > $totalPages) {
                $currentPage = $totalPages;
                $offset = ($currentPage - 1) * $itemsPerPage;
            }

            // Busca os associados da página atual
            $associados = $this->associadoModel->getAllAssociates($filter, $itemsPerPage, $offset);

            $this->render('pages/dashboard', [
                'title' => 'Gestão Administrativa - ATCPE',
                'user' => $currentUser,
                'associados' => $associados,
                'filter' => $filter,
                'pagination' => [
                    'current' => $currentPage,
                    'total_pages' => $totalPages,
                    'total_items' => $totalItems,
                    'items_per_page' => $itemsPerPage // Passamos o limite atual para a view
                ],
                'active' => 'dashboard',
                'pageStyles' => ['css/navbar.css', 'css/admin.css', 'css/dashboard.css', 'css/footer.css'],
                'pageScripts' => ['js/admin.js', 'js/navbar.js']
            ]);
        } else {
            // --- ASSOCIADO COMUM ---
            $this->render('pages/associate_dashboard', [
                'title' => 'Área do Associado - ATCPE',
                'user' => $currentUser,
                'active' => 'associate_dashboard',
                'pageStyles' => ['css/home.css', 'css/navbar.css', 'css/dashboard.css', 'css/footer.css'],
                'pageScripts' => ['js/navbar.js']
            ]);
        }
    }
}