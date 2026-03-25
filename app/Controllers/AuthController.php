<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\AssociadoModel;
use App\Services\MailService;

class AuthController extends BaseController
{
    private $associadoModel;

    public function __construct()
    {
        $this->associadoModel = new AssociadoModel();
    }

    public function login()
    {
        if (Auth::check()) {
            $this->redirectHome();
            return;
        }

        $this->render('pages/login', [
            'title' => 'Login - ATCPE',
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

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'senha');

        if (!$email || !$password) {
            $this->redirect('/login?error=campos_vazios');
            return;
        }

        $user = $this->associadoModel->findByEmail($email);

        if ($user && password_verify($password, $user['senha'])) {
            if (isset($user['id_status']) && $user['id_status'] != 1) {
                $this->redirect('/login?error=conta_inativa');
                return;
            }

            Auth::login(
                $user['id_associados'],
                $user['nomever'] ?? $user['nome_completo'],
                $user['email'],
                $user['user_tipo'] ?? 0,
                $user['foto'] ?? 'sem-foto.png'

            );

            $this->redirectHome();
        } else {
            $this->redirect('/login?error=credenciais_invalidas');
        }
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect('/login');
    }

    // --- ESQUECI A SENHA ---``
    public function forgotPassword()
    {
        $this->render('pages/forgot_password', [
            'title' => 'Recuperar Senha - ATCPE',
            'active' => 'quem-somos',
            'pageStyles' => [
                'css/forgot.css',
                'css/navbar.css',
                'css/footer.css'
            ],
            'pageScripts' => [
                'js/forgot_password.js',
                'js/navbar.js'
            ]
        ]);
    }


    /**
     * Processa o cadastro (Restrito > Nível 2)
     */

        public function checkEmail()
    {
        header('Content-Type: application/json');
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $email = $data['email'] ?? '';

        if (empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email vazio']);
            exit;
        }
        $exists = $this->associadoModel->findByEmail($email);
        if ($exists) {
            echo json_encode(['status' => 'exists', 'message' => 'Email já cadastrado']);
        } else {
            echo json_encode(['status' => 'available', 'message' => 'Email disponível']);
        }
        exit;
    }

    public function sendResetLink()
    {
        // 1. Limpeza de Buffer: Garante que nada foi impresso antes
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        try {
            // Verifica se é POST realmente
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Método inválido. Use POST.');
            }

            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            if (!$email) {
                throw new \Exception('E-mail inválido ou não informado.');
            }

            $user = $this->associadoModel->findByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Tenta salvar o token
                if (!$this->associadoModel->setResetToken($email, $token, $expires)) {
                    throw new \Exception('Erro de Banco: Não foi possível salvar o token.');
                }

                // $baseUrl = defined('URL_BASE') ? URL_BASE : 'https://www.atcpe.org.br';
                // $baseUrl = rtrim($baseUrl, '/');
                $link = "https://www.atcpe.org.br/redefinir-senha?token=$token&email=" . urlencode($email);

                $subject = "Redefinição de Senha - ATCPE";
                $body = "
                    <div style='font-family: Arial, sans-serif; color: #333;'>
                        <h2 style='color: #0056b3;'>Olá, " . ($user['nomever'] ?? 'Associado') . "</h2>
                        <p>Recebemos uma solicitação para redefinir a senha da sua conta.</p>
                        <p>Clique no botão abaixo para criar uma nova senha:</p>
                        <p>
                            <a href='$link' style='background-color: #0d6efd; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>
                                Redefinir Minha Senha
                            </a>
                        </p>
                        <p style='margin-top: 20px; font-size: 12px; color: #777;'>Este link é válido por 1 hora.</p>
                    </div>
                ";

                // Verifica se a classe MailService existe antes de chamar
                if (!class_exists(MailService::class)) {
                    throw new \Exception('Erro Interno: Classe MailService não encontrada.');
                }

                $mailService = new MailService();
                $enviou = $mailService->send($email, $subject, $body, 'ATCPE - Recuperação');

                if (!$enviou) {
                    throw new \Exception('O sistema tentou enviar o e-mail, mas o servidor SMTP falhou. Verifique as configurações de e-mail.');
                }
            }

            // Sucesso (ou fingimos sucesso por segurança se o email não existir)
            echo json_encode([
                'status' => 'success',
                'message' => 'Se o e-mail estiver cadastrado, as instruções foram enviadas.'
            ]);
        } catch (\Throwable $e) {
            // 2. Captura TUDO (Exceptions e Erros Fatais do PHP 7+)
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Erro no Servidor: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public function resetPasswordForm()
    {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';

        if (!$token || !$email) {
            $this->redirect('/login');
            return;
        }

        $user = $this->associadoModel->getUserByToken($email, $token);

        if (!$user) {
            $this->render('pages/reset_password', [
                'title' => 'Link Inválido',
                'error' => 'Link inválido ou expirado.',
                'hideForm' => true,
                'pageStyles' => [
                    'css/navbar.css',
                    'css/footer.css'
                ],
                'pageScripts' => [
                    'js/navbar.js',
                    'js/reset_password.js'
                ]
            ]);
            return;
        }

        $this->render('pages/reset_password', [
            'title' => 'Nova Senha',
            'token' => $token,
            'email' => $email,
            'pageStyles' => [
                'css/navbar.css',
                'css/footer.css'
            ],
            'pageScripts' => [
                'js/navbar.js',
                'js/reset_password.js'
            ]
        ]);
    }

    public function updatePassword()
    {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        try {
            $token = $_POST['token'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $confirma = $_POST['confirma_senha'] ?? '';

            if ($senha !== $confirma) {
                throw new \Exception('As senhas não coincidem.');
            }

            if (strlen($senha) < 6) {
                throw new \Exception('A senha deve ter no mínimo 6 caracteres.');
            }

            $user = $this->associadoModel->getUserByToken($email, $token);

            if ($user) {
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                if ($this->associadoModel->updatePasswordAndClearToken($user['id_associados'], $hash)) {
                    echo json_encode(['status' => 'success', 'message' => 'Senha alterada com sucesso!']);
                } else {
                    throw new \Exception('Erro ao atualizar senha no banco.');
                }
            } else {
                throw new \Exception('Token inválido ou expirado.');
            }
        } catch (\Throwable $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    // private function redirectUrl($path)
    // {
    //     $base = defined('URL_BASE') ? URL_BASE : '';
    //     $this->redirect($base . $path);
    // }
    private function redirectHome()
    {
        $this->redirect('/home');
    }
}
