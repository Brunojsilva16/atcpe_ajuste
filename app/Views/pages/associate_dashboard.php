<div class="container my-5">

    <!-- Seção de Boas-vindas -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mt-4">Olá, <?= htmlspecialchars(explode(' ', $user['nomever'])[0]) ?>! 👋</h2>
            <p class="text-muted">Bem-vindo(a) à sua área exclusiva de associado ATC-PE.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge rounded-pill bg-success px-3 py-2 fs-6 shadow-sm">
                <i class="fa-solid fa-check-circle me-1"></i> Associado Ativo
            </span>
        </div>
    </div>

    <div class="row g-4">

        <!-- COLUNA ESQUERDA: Cartão Virtual e Perfil -->
        <div class="col-lg-4">

            <!-- Card Digital (Simulação Visual) -->
            <div class="card border-0 shadow-sm mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #527d76 0%, #3a5c56 100%); border-radius: 15px; min-height: 200px;">
                <!-- Efeito de fundo -->
                <div class="position-absolute top-0 end-0 opacity-10" style="transform: translate(30%, -30%);">
                    <i class="fa-solid fa-brain fa-10x"></i>
                </div>

                <div class="card-body p-4 d-flex flex-column justify-content-between position-relative z-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <img src="<?= defined('URL_BASE') ? URL_BASE : '' ?>/assets/img/logo.png" alt="Logo ATCPE" style="height: 40px; filter: brightness(0) invert(1);">
                    </div>

                    <div class="mt-4">
                        <h5 class="mb-0 fw-bold text-uppercase"><?= htmlspecialchars($user['nomever']) ?></h5>
                        <small class="opacity-75 d-block mb-1">Associado(a)</small>
                        <!-- <small class="letter-spacing-2">●●●● ●●●● ●●●● <?= $user['id_associados'] ?></small> -->
                    </div>
                </div>
            </div>

            <!-- Resumo do Perfil -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center p-4">
                    <div class="mb-3 position-relative d-inline-block">
                        <?php
                        $foto = !empty($user['foto']) ? $user['foto'] : 'sem-foto.png';
                        $fotoUrl = (strpos($foto, 'http') === 0) ? $foto : (defined('URL_BASE') ? URL_BASE : '') . '/assets/foto/' . $foto;
                        ?>
                        <img src="<?= $fotoUrl ?>" alt="Foto" class="rounded-circle border border-3 border-light shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['nomever']) ?></h5>
                    <p class="text-muted small mb-3"><?= htmlspecialchars($user['email']) ?></p>

                    <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/edit-profile" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Editar Meus Dados
                    </a>
                </div>
            </div>

        </div>

        <!-- COLUNA DIREITA: Ações e Conteúdo -->
        <div class="col-lg-8">

            <!-- Atalhos Rápidos -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/beneficios" class="card h-100 border-0 shadow-sm hover-card text-decoration-none">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-light text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-hand-holding-heart fa-lg"></i>
                            </div>
                            <h6 class="text-dark fw-bold mb-0">Benefícios</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/pesquisa" class="card h-100 border-0 shadow-sm hover-card text-decoration-none">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-light text-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                            </div>
                            <h6 class="text-dark fw-bold mb-0">Buscar Profissional</h6>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="http://app.associatec.com.br/AreaAssociados/ATCPE" target="_blank" class="card h-100 border-0 shadow-sm hover-card text-decoration-none">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-light text-warning rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-graduation-cap fa-lg"></i>
                            </div>
                            <h6 class="text-dark fw-bold mb-0">Associatec (Cursos)</h6>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Área de Avisos -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fa-regular fa-bell me-2 text-warning"></i> Mural de Avisos</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-light border-start border-4 border-success" role="alert">
                        <strong><i class="fa-solid fa-calendar-check me-2"></i> Mensagem do Sistema:</strong>
                        <p class="mb-0 mt-1 small">Bem-vindo à nova área do associado!</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    .letter-spacing-2 {
        letter-spacing: 2px;
    }

    .text-primary {
        color: #527d76 !important;
    }

    .btn-outline-primary {
        color: #527d76;
        border-color: #527d76;
    }

    .btn-outline-primary:hover {
        background-color: #527d76;
        color: #fff;
    }
</style>