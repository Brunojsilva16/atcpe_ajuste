<?php

use App\Core\Router;
use App\Controllers\SiteController;
use App\Controllers\AuthController;
use App\Controllers\ErrorController;
use App\Controllers\CarouselController;
use App\Controllers\SearchController;
use App\Controllers\ProfileController;
use App\Controllers\DashboardController;
use App\Controllers\AdminController;

// --- ROTAS PÚBLICAS ---
Router::get('/', [SiteController::class, 'home']);
Router::get('/home', [SiteController::class, 'home']);
Router::get('/quem-somos', [SiteController::class, 'quemSomos']);
Router::get('/beneficios', [SiteController::class, 'beneficios']);
Router::get('/gestao', [SiteController::class, 'gestao']);
Router::get('/pesquisa', [SiteController::class, 'pesquisa']);
Router::get('/associados/api', [SiteController::class, 'pesquisa']); // API JSON
Router::get('/associe-se', [SiteController::class, 'associeSe']);

// --- APIs ---
Router::post('/api/carousel', [CarouselController::class, 'getSlides']);
Router::post('/api/pesquisa', [SearchController::class, 'search']);

// --- LOGIN / LOGOUT ---
Router::get('/login', [AuthController::class, 'login']);
Router::post('/login/auth', [AuthController::class, 'authenticate']);
Router::get('/logout', [AuthController::class, 'logout']);

// --- RECUPERAÇÃO DE SENHA (NOVO) ---
Router::get('/esqueci-senha', [AuthController::class, 'forgotPassword']);           // Exibe form de e-mail
Router::post('/esqueci-senha/enviar', [AuthController::class, 'sendResetLink']);    // Processa envio do e-mail
Router::get('/redefinir-senha', [AuthController::class, 'resetPasswordForm']);      // Exibe form de nova senha (valida token na URL)
Router::post('/redefinir-senha/atualizar', [AuthController::class, 'updatePassword']); // Salva nova senha
Router::post('/api/check-email', [AuthController::class, 'apiCheckEmail']);

// --- ÁREA DO ASSOCIADO (PERFIL) ---
Router::get('/edit-profile', [ProfileController::class, 'edit']);
Router::post('/update-profile', [ProfileController::class, 'update']);
// Mapeando rota antiga para compatibilidade se necessário
// Rotas de cadastro (se necessário)
Router::get('/cadastro', [ProfileController::class, 'register']);
Router::post('/register/store', [ProfileController::class, 'store']);

// Mantendo compatibilidade caso existam links antigos
// Router::get('/editar', [ProfileController::class, 'edit']);
// Router::post('/perfil/update', [ProfileController::class, 'update']);

// Rota da Dashboard (Inteligente: Admin ou Associado)
Router::get('/dashboard', [DashboardController::class, 'index']);

// Rotas da API Administrativa (Admin Actions)
Router::post('/admin/toggle-status', [AdminController::class, 'toggleStatus']);
Router::post('/admin/delete', [AdminController::class, 'delete']);


// --- ERROS ---
Router::get('/404', [ErrorController::class, 'notFound']);
