<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow border-0" style="width: 100%; max-width: 450px;">
        <div class="card-body p-5">

            <?php if (isset($hideForm) && $hideForm): ?>
                <!-- Estado de Erro (Token expirado) -->
                <div class="text-center">
                    <i class="fa-regular fa-circle-xmark fa-3x text-danger mb-3"></i>
                    <h3 class="text-danger fw-bold">Link Inválido</h3>
                    <p class="text-muted"><?= $error ?? 'Erro desconhecido.' ?></p>
                    <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/esqueci-senha" class="btn btn-outline-primary mt-3">Solicitar novo link</a>
                </div>

            <?php else: ?>
                <!-- Estado Normal (Formulário) -->
                <div class="text-center mb-4">
                    <i class="fa-solid fa-key fa-3x text-success"></i>
                    <h3 class="mt-3 fw-bold">Nova Senha</h3>
                    <p class="text-muted">Crie uma nova senha segura para sua conta.</p>
                </div>

                <form id="form-reset">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                    <div class="mb-3">
                        <label for="senha" class="form-label fw-bold">Nova Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="senha" name="senha" required minlength="6">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('senha')"><i class="fa-regular fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="confirma_senha" class="form-label fw-bold">Confirmar Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('confirma_senha')"><i class="fa-regular fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">Alterar Senha</button>
                    </div>
                </form>

            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function togglePass(id) {
        const input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>