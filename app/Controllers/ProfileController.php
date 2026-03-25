<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\AssociadoModel;

class ProfileController extends BaseController
{
    private $associadoModel;

    public function __construct()
    {
        $this->associadoModel = new AssociadoModel();
    }

    private function isAdmin()
    {
        Auth::init();
        // Verifica se a role salva na sessão é de Admin (>= 3)
        // Se você mudou o nível de admin no banco, ajuste esse número
        return isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] >= 3;
    }

    /**
     * Retorna o caminho absoluto do diretório de uploads ou de um arquivo específico.
     * Centraliza a lógica de descoberta de pastas (public_html vs raiz).
     */
    private function getUploadPath($fileName = null)
    {
        $rootPath = realpath(__DIR__ . '/../../'); // Caminho para a raiz do projeto

        // throw new \Exception('Debug 01: ' . $rootPath);
        // exit;

        // Lista de caminhos possíveis para tentar localizar a pasta assets/foto
        $possiblePaths = [
            $rootPath . '/public_html/assets/foto/',
            $rootPath . '/assets/foto/',
            $_SERVER['DOCUMENT_ROOT'] . '/assets/foto/',
            $_SERVER['DOCUMENT_ROOT'] . '/public/assets/foto/'
        ];

        $uploadDir = null;
        foreach ($possiblePaths as $path) {
            if (is_dir($path)) {
                $uploadDir = $path;
                break;
            }
        }

        // throw new \Exception('Debug 02: ' . $uploadDir);
        // exit;

        if (!$uploadDir) {
            return null;
        }

        // Retorna o caminho do arquivo se o nome for passado, senão retorna o diretório
        return $fileName ? $uploadDir . $fileName : $uploadDir;
    }

    /**
     * Método Privado Auxiliar para Processar Upload de Fotos
     */
    private function handlePhotoUpload($idPrefix = 'new')
    {
        // Se não houver arquivo, retorna null tranquilamente
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        // Se houver erro de upload (tamanho excedido, etc), lança exceção
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Erro no upload do arquivo. Código: ' . $_FILES['foto']['error']);
        }

        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new \Exception('Formato de imagem inválido. Use JPG, PNG ou WEBP.');
        }

        // Gera um nome único com timestamp para evitar cache do navegador
        $newFileName = "perfil_" . $idPrefix . "_" . time() . "." . $fileExtension;
        $uploadDir = $this->getUploadPath();

        if (!$uploadDir || !is_writable($uploadDir)) {
            throw new \Exception('A pasta de fotos não foi encontrada ou não possui permissão de escrita no servidor.');
        }

        if (move_uploaded_file($fileTmpPath, $uploadDir . $newFileName)) {
            return $newFileName;
        }

        throw new \Exception('Falha ao mover o arquivo para o destino final.');
    }
    
    /**
     * Exibe o formulário de edição
     */
    public function edit()
    {
        Auth::init();
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $currentUserId = Auth::id();
        $targetId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?: $currentUserId;
        $isAdmin = $this->isAdmin();

        if ($targetId != $currentUserId && !$isAdmin) {
            $targetId = $currentUserId;
        }

        $user = $this->associadoModel->findById($targetId);

        $this->render('pages/edit-profile', [
            'title' => 'Editar Perfil',
            'user' => $user,
            'isAdmin' => $isAdmin,
                'pageStyles' => [
                    'css/navbar.css',
                    'css/edit-profile.css',
                    'css/footer.css'
                ],
                'pageScripts' => ['js/edit_profile.js', 'js/navbar.js']
        ]);
    }

    /**
     * Exibe o formulário de cadastro (Restrito > Nível 2)
     */
    public function register()
    {
        if ($this->isAdmin()) {
            $adminData = $this->associadoModel->findById(Auth::id());

            $this->render('pages/register', [
                'title' => 'Cadastrar Associado - Admin',
                'user' => $adminData,
                'pageStyles' => [
                    'css/navbar.css',
                    'css/edit-profile.css',
                    'css/footer.css'
                ],
                'pageScripts' => ['js/register.js', 'js/navbar.js']
            ]);
        } else {
            $this->redirect('/dashboard');
            return;
        }
    }

    /**
     * REGISTER / STORE: Cria um novo associado no banco de dados.
     * Este método substitui a antiga função store.
     */
    public function store()
    {
        Auth::init();
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new \Exception('Método inválido.');
            if (!$this->isAdmin()) throw new \Exception('Acesso negado.');

            $nome_completo = $_POST['nome_completo'] ?? '';
            $nome = $_POST['nomever'] ?? '';

            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = $_POST['senha'] ?? '';
            $confirma = $_POST['confirma_senha'] ?? '';

            if (empty($email) || empty($nome) || empty($nome_completo)) {
                throw new \Exception("Preencha os campos obrigatórios.");
            }

            if ($this->associadoModel->findByEmail($email)) {
                throw new \Exception("Este e-mail já está cadastrado.");
            }
            if (!empty($_POST['senha'])) {
                if (strlen($senha) < 6) throw new \Exception('A senha deve ter no mínimo 6 caracteres.');
                if ($senha !== $confirma) throw new \Exception('As senhas não coincidem.');
            } else {
                throw new \Exception('A senha é obrigatória para o cadastro.');
            }
            if (!$email) throw new \Exception('E-mail inválido.');
            if (strlen($senha) < 6) throw new \Exception('A senha deve ter no mínimo 6 caracteres.');
            if ($senha !== $confirma) throw new \Exception('As senhas não coincidem.');

            // Processa a foto (usa um prefixo temporário pois ainda não temos o ID do banco)
            $foto = $this->handlePhotoUpload('new') ?: 'sem-foto.png';

            $data = [
                'nome_completo'     => $nome_completo,
                'nomever'           => $nome,
                'email'             => $email,
                'senha'             => password_hash($senha, PASSWORD_DEFAULT),
                'celular'           => $_POST['celular'] ?? '',
                'rede_social'       => $_POST['rede_social'] ?? '',
                'cep'               => $_POST['cep'] ?? '',
                'endereco'          => $_POST['endereco'] ?? '',
                'numero'            => $_POST['numero'] ?? '',
                'bairro_at'         => $_POST['bairro_at'] ?? '',
                'cidade_at'         => $_POST['cidade_at'] ?? '',
                'uf'                => $_POST['uf'] ?? '',
                'publico_atend'     => $_POST['publico_atend'] ?? '',
                'modalidade'        => $_POST['modalidade'] ?? '',
                'acomp_terapeutico' => $_POST['acomp_terapeutico'] ?? 'Não',
                'mini_curr'         => $_POST['mini_curr'] ?? '',
                'id_status'         => 1, // Ativo por padrão
                'tipo_ass'          => $_POST['tipo_ass'] ?? 'Profissional',
                'crp_crm'           => $_POST['crp-crm'] ?? '',
                'foto'              => $foto
            ];

            if ($this->associadoModel->create($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Cadastro realizado com sucesso!']);
            } else {
                throw new \Exception('Erro ao salvar no banco. O e-mail pode já estar em uso.');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * UPDATE: Atualiza os dados de um perfil existente.
     */
    public function update()
    {
        Auth::init();
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        try {

            $currentUserId = Auth::id();
            $targetId = $_POST['id_associados'] ?? null;

            // Validação de segurança
            if (!$targetId || ($targetId != $currentUserId && !$this->isAdmin())) {
                throw new \Exception('Operação não autorizada ou ID inválido.');
            }
            $nome_completo = $_POST['nome_completo'] ?? '';
            $nome = $_POST['nomever'] ?? '';
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = $_POST['senha'] ?? '';

            if (empty($nome) || empty($nome_completo)) {
                throw new \Exception("Preencha os campos obrigatórios.");
            }

            // Senha
            if (!empty($_POST['senha'])) {
                if (strlen($senha) < 6) throw new \Exception('A senha deve ter no mínimo 6 caracteres.');
                $confirma = $_POST['confirma_senha'] ?? '';
                if ($senha !== $confirma) throw new \Exception('As senhas não coincidem.');
                $this->associadoModel->updatePassword($targetId, password_hash($_POST['senha'], PASSWORD_DEFAULT));
            }

            $data = [
                'nome_completo'     => $nome_completo,
                'nomever'           => $nome,
                'email'             => $email,
                'senha'             => password_hash($senha, PASSWORD_DEFAULT),
                'celular'           => $_POST['celular'] ?? '',
                'rede_social'       => $_POST['rede_social'] ?? '',
                'cep'               => $_POST['cep'] ?? '',
                'endereco'          => $_POST['endereco'] ?? '',
                'numero'            => $_POST['numero'] ?? '',
                'bairro_at'         => $_POST['bairro_at'] ?? '',
                'cidade_at'         => $_POST['cidade_at'] ?? '',
                'uf'                => $_POST['uf'] ?? '',
                'publico_atend'     => $_POST['publico_atend'] ?? '',
                'modalidade'        => $_POST['modalidade'] ?? '',
                'acomp_terapeutico' => $_POST['acomp_terapeutico'] ?? 'Não',
                'mini_curr'         => $_POST['mini_curr'] ?? '',
                'id_status'         => 1, // Ativo por padrão
                'tipo_ass'          => $_POST['tipo_ass'] ?? 'Profissional',
                'crp_crm'           => $_POST['crp-crm'] ?? '',
            ];
            // Tenta processar novo upload (se houver arquivo selecionado)
            $novaFoto = $this->handlePhotoUpload($targetId);
            if ($novaFoto) {
                $data['foto'] = $novaFoto;
                $oldUser = $this->associadoModel->findById($targetId);
                if ($oldUser && !empty($oldUser['foto']) && $oldUser['foto'] !== 'sem-foto.png') {
                    $oldPath = $this->getUploadPath($oldUser['foto']);
                    if (file_exists($oldPath)) @unlink($oldPath);
                }
            }

            if ($this->associadoModel->updateProfile($targetId, $data)) {
                if ($targetId == $currentUserId) $_SESSION['user_name'] = $data['nomever'];
                echo json_encode(['status' => 'success', 'message' => 'Perfil atualizado com sucesso!']);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Dados verificados (sem alterações).']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
}
