<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\AssociadoModel;

class ProfileController extends BaseController
{
    private $associadoModel;

    public function __construct()
    {
        // Centralizamos no AssociadoModel pois ele representa a tabela 'associados_25'
        $this->associadoModel = new AssociadoModel();
    }

    /**
     * Verifica se é Admin (Nível > 2)
     */
    private function isAdmin()
    {
        Auth::init();
        
        // CORREÇÃO: No seu Auth.php, a chave é 'user_role', não 'user_tipo'
        if (isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] > 2) {
            return true;
        }

        return false;
    }

    public function edit()
    {
        Auth::init();
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $currentUserId = Auth::id();
        $targetId = $currentUserId;
        $isAdmin = $this->isAdmin();

        // Verifica se veio um ID pela URL (clique do Dashboard)
        $requestedId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        // LÓGICA CRÍTICA CORRIGIDA:
        // Se tem ID na URL e é diferente do logado...
        if ($requestedId && $requestedId != $currentUserId) {
            if ($isAdmin) {
                // ...e é admin, permite editar o alvo
                $targetId = $requestedId;
            } else {
                // ...se não é admin, força voltar para o próprio perfil (segurança)
                $targetId = $currentUserId;
            }
        }

        // Busca os dados usando o AssociadoModel
        $userData = $this->associadoModel->findById($targetId);

        if (!$userData) {
            // Se o ID não existe, volta pro dashboard com erro
            $this->redirect('/dashboard');
            return;
        }

        $this->render('pages/edit-profile', [
            'title' => 'Editar Perfil - ATCPE',
            'user' => $userData,
            'isAdmin' => $isAdmin, // Passa para a view para mostrar botão "Voltar"
            'pageStyles' => [
                'css/navbar.css',
                'css/edit-profile.css',
                'css/footer.css'
            ],
            'pageScripts' => [
                'js/edit_profile.js', // Certifique-se que o nome do arquivo JS está correto
                'js/navbar.js'
            ]
        ]);
    }

    public function update()
    {
        Auth::init();
        
        // Limpa qualquer saída anterior para garantir JSON limpo
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        if (!Auth::check()) {
            echo json_encode(['status' => 'error', 'message' => 'Sessão expirada.']);
            exit;
        }

        $currentUserId = Auth::id();
        $formUserId = filter_input(INPUT_POST, 'id_associados', FILTER_SANITIZE_NUMBER_INT);
        $targetId = $currentUserId;
        $isAdmin = $this->isAdmin();

        // Validação de segurança no POST
        if ($formUserId && $formUserId != $currentUserId) {
            if ($isAdmin) {
                $targetId = $formUserId;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Sem permissão para editar este usuário.']);
                exit;
            }
        }

        try {
            // 1. Upload de Foto
            if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
                $file = $_FILES['foto'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($ext, $allowed)) {
                    throw new \Exception('Formato de imagem inválido.');
                }

                $projectRoot = dirname(__DIR__);
                $uploadDir = $projectRoot . 'public/assets/foto/';
                
                // Cria diretório se não existir
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $newFileName = $targetId . '_' . time() . '.' . $ext;
                
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
                    $this->associadoModel->updatePhoto($targetId, $newFileName);
                    
                    // Só atualiza a sessão se estiver editando o PRÓPRIO perfil
                    if ($targetId == $currentUserId) {
                        $_SESSION['user_foto'] = $newFileName;
                    }
                }
            }

            // 2. Senha
            if (!empty($_POST['senha'])) {
                $novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $this->associadoModel->updatePassword($targetId, $novaSenha);
            }

            // 3. Dados Gerais
            $data = [
                'nome_completo'     => $_POST['nome_completo'] ?? '',
                'nomever'           => $_POST['nomever'] ?? '',
                'celular'           => $_POST['celular'] ?? '',
                'rede_social'       => $_POST['rede_social'] ?? '',
                'cep'               => $_POST['cep'] ?? '',
                'endereco'          => $_POST['endereco'] ?? '',
                'numero'            => $_POST['numero'] ?? '',
                'bairro_at'         => $_POST['bairro_at'] ?? '',
                'cidade_at'         => $_POST['cidade_at'] ?? '',
                'uf'                => $_POST['uf'] ?? '',
                'publico_atend'     => $_POST['publico_atend'] ?? '',
                'acomp_terapeutico' => $_POST['acomp_terapeutico'] ?? 'Não',
                'mini_curr'         => $_POST['mini_curr'] ?? '',
                'tipo_ass'          => $_POST['tipo_ass'] ?? ''
            ];

            if ($this->associadoModel->updateProfile($targetId, $data)) {
                if ($targetId == $currentUserId) {
                    $_SESSION['user_name'] = $data['nomever'];
                }
                echo json_encode(['status' => 'success', 'message' => 'Perfil atualizado com sucesso!']);
            } else {
                throw new \Exception('Erro ao atualizar banco de dados.');
            }

        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
}