<div id="edite" class="row justify-content-center my-5">
    <div class="col-md-8 col-lg-6">

        <div class="card shadow border-0" style="border-radius: 12px; overflow: hidden;">

            <div class="card-header py-4 px-4 text-start" style="background-color: #527d76; color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold"><i class="fa-solid fa-user-pen me-2"></i> Editar Perfil</h4>
                        <p class="mb-0 small text-white-50">
                            <?= ($user['id_associados'] == $_SESSION['user_id']) ? 'Mantenha seus dados atualizados.' : 'Editando usuário ID: ' . $user['id_associados'] ?>
                        </p>
                    </div>
                    <?php if (isset($isAdmin) && $isAdmin): ?>
                        <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/dashboard" class="btn btn-sm btn-outline-light">
                            <i class="fa-solid fa-arrow-left me-1"></i> Voltar Dashboard
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">

                <?php if (isset($user) && $user): ?>

                    <!-- Adicionado autocomplete="off" no formulário para desencorajar preenchimento automático geral -->
                    <form id="form-perfil" action="<?= defined('URL_BASE') ? URL_BASE : '' ?>/update-profile" method="POST" enctype="multipart/form-data" autocomplete="off">

                        <!-- Truque para evitar autofill do navegador em alguns casos -->
                        <input type="text" style="display:none" name="fakeusernameremembered">
                        <input type="password" style="display:none" name="fakepasswordremembered">

                        <!-- SEÇÃO DA FOTO -->
                        <div class="d-flex justify-content-center mb-4">
                            <div class="position-relative d-inline-block">
                                <div class="photo-preview-wrapper" style="width: 160px; height: 160px; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                    <?php
                                    $foto = !empty($user['foto']) ? $user['foto'] : 'sem-foto.png';
                                    $fotoPath = (strpos($foto, 'http') === 0) ? $foto : (defined('URL_BASE') ? URL_BASE : '') . '/assets/foto/' . $foto;
                                    ?>
                                    <img src="<?= $fotoPath ?>"
                                        alt="Foto de Perfil"
                                        class="rounded-circle img-fluid w-100 h-100"
                                        id="preview-foto"
                                        style="object-fit: cover;"
                                        onerror="this.onerror=null;this.src='<?= defined('URL_BASE') ? URL_BASE : '' ?>/assets/foto/sem-foto.png';">
                                </div>
                                <label for="foto" class="btn btn-sm btn-light position-absolute bottom-0 end-0 rounded-circle shadow-sm p-2" style="width: 36px; height: 36px; border: 2px solid #fff;" title="Alterar foto">
                                    <i class="fas fa-camera text-secondary"></i>
                                    <input type="file" id="foto" name="foto" class="d-none" accept="image/*">
                                </label>
                            </div>
                        </div>

                        <!-- ID Oculto -->
                        <input type="hidden" name="id_associados" value="<?= $user['id_associados'] ?>">

                        <!-- DADOS PESSOAIS -->
                        <h5 class="mb-3 border-bottom pb-2 text-muted uppercase-title text-center" style="font-size: 0.85rem; letter-spacing: 1px;"><i class="fa-regular fa-id-card me-1"></i> Dados Pessoais</h5>

                        <div class="mb-3">
                            <label for="nome_completo" class="form-label fw-medium small text-secondary">Nome Completo</label>
                            <input type="text" class="form-control bg-light" id="nome" name="nome_completo"
                                value="<?= htmlspecialchars($user['nome_completo'] ?? $user['nome_completo'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomever" class="form-label fw-medium small text-secondary">Nome para visualização no site</label>
                            <input type="text" class="form-control bg-light" id="nomever" name="nomever"
                                value="<?= htmlspecialchars($user['nomever'] ?? $user['nome'] ?? '') ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="celular" class="form-label fw-medium small text-secondary">Celular / WhatsApp</label>
                                <input type="tel" class="form-control bg-light" id="celular" name="celular"
                                    placeholder="(00) 00000-0000"
                                    autocomplete="off"
                                    maxlength="15"
                                    onkeyup="mascaraCelular(this)"
                                    value="<?= htmlspecialchars($user['celular'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rede_social" class="form-label fw-medium small text-secondary">Instagram (Opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">@</span>
                                    <input type="text" class="form-control bg-light border-start-0 ps-0" id="rede_social" name="rede_social"
                                        placeholder="seu.perfil"
                                        value="<?= htmlspecialchars(str_replace('@', '', $user['rede_social'] ?? '')) ?>">
                                </div>
                            </div>
                        </div>

                        <!-- ENDEREÇO -->
                        <h5 class="mb-3 mt-4 border-bottom pb-2 text-muted uppercase-title text-center" style="font-size: 0.85rem; letter-spacing: 1px;"><i class="fa-solid fa-map-location-dot me-1"></i> Endereço Profissional</h5>

                        <div class="row">
                            <div class="col-4 mb-3">
                                <label for="cep" class="form-label fw-medium small text-secondary">CEP</label>
                                <input type="text" class="form-control bg-light" id="cep" name="cep"
                                    placeholder="00000-000"
                                    maxlength="9"
                                    onkeyup="mascaraCep(this)"
                                    onblur="buscarCep(this.value)"
                                    value="<?= htmlspecialchars($user['cep'] ?? '') ?>">
                            </div>
                            <div class="col-8 mb-3">
                                <label for="cidade_at" class="form-label fw-medium small text-secondary">Cidade</label>
                                <input type="text" class="form-control bg-light" id="cidade_at" name="cidade_at"
                                    value="<?= htmlspecialchars($user['cidade_at'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8 mb-3">
                                <label for="endereco" class="form-label fw-medium small text-secondary">Rua / Logradouro</label>
                                <input type="text" class="form-control bg-light" id="endereco" name="endereco"
                                    value="<?= htmlspecialchars($user['endereco'] ?? '') ?>">
                            </div>
                            <div class="col-4 mb-3">
                                <label for="numero" class="form-label fw-medium small text-secondary">Número</label>
                                <input type="text" class="form-control bg-light" id="numero" name="numero"
                                    value="<?= htmlspecialchars($user['numero'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bairro_at" class="form-label fw-medium small text-secondary">Bairro</label>
                                <input type="text" class="form-control bg-light" id="bairro_at" name="bairro_at"
                                    value="<?= htmlspecialchars($user['bairro_at'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="uf" class="form-label fw-medium small text-secondary">Estado (UF)</label>
                                <input type="text" class="form-control bg-light" id="uf" name="uf" maxlength="2"
                                    value="<?= htmlspecialchars($user['uf'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- PROFISSIONAL -->
                        <h5 class="mb-3 mt-4 border-bottom pb-2 text-muted uppercase-title text-center" style="font-size: 0.85rem; letter-spacing: 1px;"><i class="fa-solid fa-briefcase me-1"></i> Atuação</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="publico_atend" class="form-label fw-medium small text-secondary">Público de Atendimento</label>
                                <input type="text" class="form-control bg-light" id="publico_atend" name="publico_atend"
                                    placeholder="Ex: Crianças, Adolescentes, Adultos..."
                                    value="<?= htmlspecialchars($user['publico_atend'] ?? '') ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="modalidade" class="form-label fw-medium small text-secondary">Modalidade de atendimento</label>
                                <select class="form-select bg-light" id="modalidade" name="modalidade">
                                    <option value="" <?= !in_array(($user['modalidade'] ?? ''), ['Presencial', 'On-line', 'Ambos']) ? 'selected' : '' ?> disabled>
                                        Selecione uma opção
                                    </option>
                                    <option value="Presencial" <?= ($user['modalidade'] ?? '') == 'Presencial' ? 'selected' : '' ?>>Presencial</option>
                                    <option value="On-line" <?= ($user['modalidade'] ?? '') == 'On-line' ? 'selected' : '' ?>>On-line</option>
                                    <option value="Ambos" <?= ($user['modalidade'] ?? '') == 'Ambos' ? 'selected' : '' ?>>Ambos</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <?php if (isset($isAdmin) && $isAdmin): ?>
                                <div class="col-md-6 mb-3">
                                    <label for="tipo_ass" class="form-label fw-medium small text-secondary">Tipo de Associado</label>
                                    <select class="form-select bg-light" id="tipo_ass" name="tipo_ass">
                                        <option value="Estudante" <?= ($user['tipo_ass'] ?? '') == 'Estudante' ? 'selected' : '' ?>>Estudante</option>
                                        <option value="Profissional" <?= ($user['tipo_ass'] ?? '') == 'Profissional' ? 'selected' : '' ?>>Profissional</option>
                                        <option value="Psiquiatra" <?= ($user['tipo_ass'] ?? '') == 'Psiquiatra' ? 'selected' : '' ?>>Psiquiatra</option>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-6 mb-3">
                                <label for="crp-crm" class="form-label fw-medium small text-secondary">CRP / CRM</label>
                                <input type="text" class="form-control bg-light" id="crp-crm" name="crp-crm" placeholder="Ex: 123456789" value="<?= htmlspecialchars($user['crp_crm'] ?? '') ?>">
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label fw-medium small text-secondary d-block">Realiza Acompanhamento Terapêutico?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="acomp_terapeutico" id="at_sim" value="Sim" <?= ($user['acomp_terapeutico'] ?? '') == 'Sim' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="at_sim">Sim</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="acomp_terapeutico" id="at_nao" value="Não" <?= ($user['acomp_terapeutico'] ?? 'Não') == 'Não' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="at_nao">Não</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mini_curr" class="form-label fw-medium small text-secondary">Mini Currículo</label>
                            <textarea class="form-control bg-light" id="mini_curr" name="mini_curr" rows="8"
                                placeholder="Um breve resumo sobre sua formação e experiência..."><?= htmlspecialchars($user['mini_curr'] ?? '') ?></textarea>
                        </div>

                        <!-- SEGURANÇA -->
                        <h5 class="mb-3 mt-4 border-bottom pb-2 text-muted uppercase-title text-center" style="font-size: 0.85rem; letter-spacing: 1px;"><i class="fa-solid fa-lock me-1"></i> Alterar Senha</h5>
                        <div class="alert alert-light border small text-muted">
                            <i class="fas fa-info-circle me-1"></i> Preencha abaixo <strong>apenas</strong> se desejar alterar sua senha atual.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="senha" class="form-label fw-medium small text-secondary">Nova Senha</label>
                                <!-- CORREÇÃO AQUI: autocomplete="new-password" e value vazio -->
                                <input type="password" class="form-control bg-light" id="senha" name="senha"
                                    placeholder="Deixe em branco para manter a atual"
                                    autocomplete="new-password" value="">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirma_senha" class="form-label fw-medium small text-secondary">Confirmar Nova Senha</label>
                                <input type="password" class="form-control bg-light" id="confirma_senha" name="confirma_senha"
                                    placeholder="Repita a nova senha"
                                    autocomplete="new-password" value="">
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3">
                            <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/home" class="btn btn-outline-secondary btn-sm px-3">Cancelar</a>
                            <button type="submit" class="btn text-white px-4 py-2 fw-bold shadow-sm" style="background-color: #527d76;">Salvar Alterações</button>
                        </div>

                    </form>

                <?php else: ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i><br>
                        Erro ao carregar dados. Tente fazer login novamente.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Scripts permanecem os mesmos -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function mascaraCelular(input) {
        let v = input.value.replace(/\D/g, "");
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
        v = v.replace(/(\d)(\d{4})$/, "$1-$2");
        input.value = v;
    }

    function mascaraCep(input) {
        let v = input.value.replace(/\D/g, "");
        v = v.replace(/^(\d{5})(\d)/, "$1-$2");
        input.value = v;
    }
    // Preview de Imagem
    document.getElementById('foto').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-foto').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Busca de CEP
    function buscarCep(cep) {
        cep = cep.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(res => res.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('endereco').value = data.logradouro;
                        document.getElementById('bairro_at').value = data.bairro;
                        document.getElementById('cidade_at').value = data.localidade;
                        document.getElementById('uf').value = data.uf;
                        document.getElementById('numero').focus();
                    }
                })
                .catch(() => console.log('Erro ao buscar CEP'));
        }
    }
</script>