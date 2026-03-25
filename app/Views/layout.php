<?php
use App\Core\Auth;

Auth::init();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ATCPE' ?></title>

    <!-- Define a Base URL para o JavaScript usar a constante do PHP -->
    <script>
        const BASE_URL = "<?= defined('URL_BASE') ? URL_BASE : '' ?>";
    </script>

    <!-- Favicon -->
    <link rel="icon" href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/assets/img/favicon.png" sizes="32x32">

    <!-- Fontes e Ícones -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos Específicos da Página -->
    <?php if (isset($pageStyles) && is_array($pageStyles)): ?>
        <?php foreach ($pageStyles as $style): ?>
            <!-- Verifica se é URL externa ou arquivo local -->
            <?php $href = (strpos($style, 'http') === 0) ? $style : (defined('URL_BASE') ? URL_BASE : '') . '/' . $style; ?>
            <link href="<?= $href ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Scripts no Header (Ex: Tailwind, Analytics) -->
    <?php if (isset($pageScriptsHeader) && is_array($pageScriptsHeader)): ?>
        <?php foreach ($pageScriptsHeader as $script): ?>
            <?php $src = (strpos($script, 'http') === 0) ? $script : (defined('URL_BASE') ? URL_BASE : '') . '/' . $script; ?>
            <script src="<?= $src ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>

    <!-- NAVBAR -->
    <?php 
        // Ajuste para carregar parciais corretamente
        $navbarPath = __DIR__ . '/partials/navbar.phtml';
        if (file_exists($navbarPath)) require_once $navbarPath;
    ?>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="container-fluid p-0">
        <?= $content ?>
    </main>

    <!-- FOOTER -->
    <?php 
        $footerPath = __DIR__ . '/partials/footer.phtml';
        if (file_exists($footerPath)) {
            require_once $footerPath;
        } elseif (file_exists(__DIR__ . '/partials/footer.php')) {
            require_once __DIR__ . '/partials/footer.php';
        }
    ?>

    <!-- Scripts Globais -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts Específicos da Página -->
    <?php if (isset($pageScripts) && is_array($pageScripts)): ?>
        <?php foreach ($pageScripts as $script): ?>
            <?php $src = (strpos($script, 'http') === 0) ? $script : (defined('URL_BASE') ? URL_BASE : '') . '/' . $script; ?>
            <script src="<?= $src ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>