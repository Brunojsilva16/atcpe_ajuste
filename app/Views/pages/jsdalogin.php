<div class="container mt-5 mb-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fa-solid fa-user-pen"></i> Editar Perfil</h3>
        </div>
        <div class="card-body">
            
            <?php if (isset($user) && $user): ?>
                
                <!-- O ID do form deve bater com o seu JS -->
                <form id="form-perfil" action="<?= defined('URL_BASE') ? URL_BASE : '' ?>/update" method="POST" enctype="multipart/form-data">
                    
                    <!-- FOTO DE PERFIL -->
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-4 text-center">
                            <div class="mb-3 position-relative d-inline-block">
                                <?php 
                                    $foto = !empty($user['foto']) ? $user['foto'] : 'sem-foto.png';
                                    // Ajuste de caminho se necessário
                                    $fotoPath = (strpos($foto, 'http') === 0) ? $foto : (defined('URL_BASE') ? URL_BASE : '') . '/assets/foto/' . $foto;
                                ?>
                                <img src="<?= $fotoPath ?>" 
                                     alt="Foto de Perfil" 
                                     class="rounded-circle img-thumbnail photo-preview-wrapper" 
                                     id="preview-foto"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                
                                <label for="foto" class="btn btn-sm btn-light position-absolute bottom-0 end-0 rounded-circle shadow" title="Alterar foto">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                                <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                            </div>
                            <p class="text-muted small">Clique no ícone da câmera para alterar.</p>
                        </div>
                    </div>

                    <div class="row">
                        <!-- DADOS PESSOAIS -->
                        <div class="col-md-6 mb-3">
                            <label for="nomever" class="form-label fw-bold">Nome Completo</label>
                            <input type="text" class="form-control" id="nomever" name="nomever" value="<?= htmlspecialchars($user['nomever'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">E-mail (Login)</label>
                            <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly title="O e-mail não pode ser alterado por aqui.">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="celular" class="form-label fw-bold">Celular / WhatsApp</label>
                            <input type="text" class="form-control" id="celular" name="celular" value="<?= htmlspecialchars($user['celular'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rede_social" class="form-label fw-bold">Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control" id="rede_social" name="rede_social" value="<?= htmlspecialchars($user['rede_social'] ?? '') ?>" placeholder="seu.perfil">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3">Endereço</h5>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($user['cep'] ?? '') ?>" onblur="buscarCep(this.value)">
                        </div>
                        <div class="col-md-7 mb-3">
                            <label for="endereco" class="form-label">Rua / Logradouro</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars($user['endereco'] ?? '') ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($user['numero'] ?? '') ?>">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="bairro_at" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro_at" name="bairro_at" value="<?= htmlspecialchars($user['bairro_at'] ?? '') ?>">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="cidade_at" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade_at" name="cidade_at" value="<?= htmlspecialchars($user['cidade_at'] ?? '') ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="uf" class="form-label">UF</label>
                            <input type="text" class="form-control" id="uf" name="uf" value="<?= htmlspecialchars($user['uf'] ?? '') ?>" maxlength="2">
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3">Dados Profissionais</h5>

                    <div class="mb-3">
                        <label for="publico_atend" class="form-label fw-bold">Público de Atendimento</label>
                        <input type="text" class="form-control" id="publico_atend" name="publico_atend" value="<?= htmlspecialchars($user['publico_atend'] ?? '') ?>" placeholder="Ex: Crianças, Adolescentes, Adultos">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Realiza Acompanhamento Terapêutico (AT)?</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="acomp_terapeutico" id="at_sim" value="Sim" <?= ($user['acomp_terapeutico'] == 'Sim') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="at_sim">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="acomp_terapeutico" id="at_nao" value="Não" <?= ($user['acomp_terapeutico'] == 'Não' || empty($user['acomp_terapeutico'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="at_nao">Não</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mini_curr" class="form-label fw-bold">Mini Currículo / Sobre Mim</label>
                        <textarea class="form-control" id="mini_curr" name="mini_curr" rows="4" placeholder="Fale um pouco sobre sua formação e experiência..."><?= htmlspecialchars($user['mini_curr'] ?? '') ?></textarea>
                    </div>

                    <hr>
                    <h5 class="text-danger mb-3"><i class="fa-solid fa-lock"></i> Alterar Senha</h5>
                    <p class="text-muted small">Preencha apenas se desejar alterar sua senha atual.</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="senha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite a nova senha">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirma_senha" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" placeholder="Repita a nova senha">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/home" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success px-5">Salvar Alterações</button>
                    </div>

                </form>

            <?php else: ?>
                <div class="alert alert-danger">
                    Erro ao carregar dados do usuário. Tente fazer login novamente.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script rápido para preview da foto e CEP -->
<script>
document.getElementById('foto').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-foto').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});

function buscarCep(cep) {
    cep = cep.replace(/\D/g, '');
    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('bairro_at').value = data.bairro;
                    document.getElementById('cidade_at').value = data.localidade;
                    document.getElementById('uf').value = data.uf;
                }
            });
    }
}
</script>