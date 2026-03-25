<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow border-0" style="width: 100%; max-width: 450px;">
        <div class="card-body p-5 text-center">
            
            <div class="mb-4">
                <i class="fa-solid fa-lock fa-3x text-primary"></i>
            </div>

            <h3 class="mb-3 fw-bold text-dark">Esqueceu a Senha?</h3>
            <p class="text-muted mb-4">
                Digite seu e-mail cadastrado e enviaremos um link para você redefinir sua senha.
            </p>

            <form id="form-forgot">
                <div class="form-floating mb-3 text-start">
                    <input type="email" class="form-control" id="email" name="email" placeholder="nome@exemplo.com" required>
                    <label for="email">Seu E-mail</label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg" id="btn-send">
                        Enviar Link
                    </button>
                </div>
            </form>

            <div class="mt-4">
                <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/login" class="text-decoration-none text-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Voltar para o Login
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>