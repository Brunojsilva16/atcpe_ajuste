<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\AssociadoModel;

class AdminController extends BaseController
{
    private $associadoModel;

    public function __construct()
    {
        $this->associadoModel = new AssociadoModel();
    }

    /**
     * Verifica permissão: Apenas nível > 2
     */
    private function checkPermission()
    {
        Auth::init();
        if (!Auth::check()) {
            $this->jsonResponse(['success' => false, 'message' => 'Não autenticado.'], 401);
        }

        $userId = Auth::id();
        $user = $this->associadoModel->findById($userId);

        if (!$user || (int)$user['user_tipo'] <= 2) {
            $this->jsonResponse(['success' => false, 'message' => 'Acesso negado. Nível insuficiente.'], 403);
        }
    }

    /**
     * Resposta JSON segura que limpa buffers de erro anteriores
     */
    protected function jsonResponse($data, $status = 200)
    {
        // Limpa qualquer saída anterior (como warnings PHP) que quebraria o JSON
        if (ob_get_length()) ob_clean();
        
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function toggleStatus()
    {
        $this->checkPermission();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        // Recebe o status atual para poder inverter
        $currentStatus = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

        if (!$id || $currentStatus === null || $currentStatus === false) {
            $this->jsonResponse(['success' => false, 'message' => 'Dados inválidos ou incompletos.']);
        }

        try {
            if ($this->associadoModel->toggleStatus($id, $currentStatus)) {
                $this->jsonResponse(['success' => true]);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Erro ao atualizar no banco.']);
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        $this->checkPermission();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID inválido.']);
        }

        if ($id == Auth::id()) {
            $this->jsonResponse(['success' => false, 'message' => 'Você não pode excluir seu próprio usuário.']);
        }

        try {
            if ($this->associadoModel->delete($id)) {
                $this->jsonResponse(['success' => true]);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Erro ao excluir associado.']);
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
        }
    }
}