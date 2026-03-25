<div id="cadastro" class="row justify-content-center my-5">
    <div class="col-md-8 col-lg-6">

        <div class="card shadow border-0" style="border-radius: 12px; overflow: hidden;">

            <!-- Header com a mesma cor do editar perfil -->
            <div class="card-header py-4 px-4 text-start" style="background-color: #527d76; color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold"><i class="fa-solid fa-user-plus me-2"></i> Cadastro Administrativo</h4>
                        <p class="mb-0 small text-white-50">Adicionando novo associado ao sistema.</p>
                    </div>
                    <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/dashboard" class="btn btn-sm btn-outline-light">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">

                <form id="form-registro" action="<?= defined('URL_BASE') ? URL_BASE : '' ?>/register/store" method="POST" enctype="multipart/form-data" novalidate autocomplete="off">

                    <!-- Truque Anti-Autofill -->
                    <input type="text" style="display:none" name="fakeusernameremembered">
                    <input type="password" style="display:none" name="fakepasswordremembered">

                    <!-- SEÇÃO DA FOTO (Vazia por padrão) -->
                    <div class="d-flex justify-content-center mb-4">
                        <div class="position-relative d-inline-block">
                            <div class="photo-preview-wrapper" style="width: 160px; height: 160px; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <img src="<?= defined('URL_BASE') ? URL_BASE : '' ?>/assets/foto/sem-foto.png"
                                    alt="Preview"
                                    class="rounded-circle img-fluid w-100 h-100"
                                    id="preview-foto"
                                    style="object-fit: cover;">
                            </div>
                            <label for="foto" class="btn btn-sm btn-light position-absolute bottom-0 end-0 rounded-circle shadow-sm p-2" style="width: 36px; height: 36px; border: 2px solid #fff;" title="Adicionar foto">
                                <i class="fas fa-camera text-secondary"></i>
                                <input type="file" id="foto" name="foto" class="d-none" accept="image/*">
                            </label>
                        </div>
                    </div>

                    <!-- DADOS DE ACESSO -->
                    <h5 class="mb-3 border-bottom pb-2 text-muted uppercase-title" style="font-size: 0.85rem; letter-spacing: 1px;"><i class="fa-solid fa-key me-1"></i> Dados de Acesso</h5>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium small text-secondary">E-mail (Login)</label>
                        <input type="email" class="form-control bg-light" id="email" name="email" required placeholder="email@exemplo.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="senha" class="form-label fw-medium small text-secondary">Senha</label>
                            <input type="password" class="form-control bg-light" id="senha" name="senha" required autocomplete="new-password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirma_senha" class="form-label fw-medium small text-secondary">Confirmar Senha</label>
                            <input type="password" class="form-control bg-light" id="confirma_senha" name="confirma_senha" required autocomplete="new-password">
                        </div>
                    </div>

                    <!-- DADOS PESSOAIS -->
                    <h5 class="mb-3 mt-4 border-bottom pb-2 text-muted uppercase-title" style="font-size: 0.85rem; letter-spacing: 1px;"><i class="fa-regular fa-id-card me-1"></i> Dados Pessoais</h5>

                    <div class="mb-3">
                        <label for="nome_completo" class="form-label fw-medium small text-secondary">Nome Completo</label>
                        <input type="text" class="form-control bg-light" id="nome_completo" name="nome_completo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomever" class="form-label fw-medium small text-secondary">Nome para visualização no site</label>
                        <input type="text" class="form-control bg-light" id="nomever" name="nomever" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="celular" class="form-label fw-medium small text-secondary">Celular / WhatsApp</label>
                            <input type="tel" class="form-control bg-light" id="celular" name="celular"
                                placeholder="(00) 00000-0000"
                                maxlength="15"
                                onkeyup="mascaraCelular(this)"
                                autocomplete="off">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rede_social" class="form-label fw-medium small text-secondary">Instagram (Opcional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">@</span>
                                <input type="text" class="form-control bg-light border-start-0 ps-0" id="rede_social" name="rede_social" placeholder="seu.perfil">
                            </div>
                        </div>
                    </div>

                    <!-- ENDEREÇO -->
                    <h5 class="mb-3 mt-4 border-bottom pb-2 text-muted uppercase-title" style="font-size: 0.85rem; letter-spacing: 1px;"><i class="fa-solid fa-map-location-dot me-1"></i> Endereço Profissional</h5>

                    <div class="row">
                        <div class="col-4 mb-3">
                            <label for="cep" class="form-label fw-medium small text-secondary">CEP</label>
                            <input type="text" class="form-control bg-light" id="cep" name="cep"
                                placeholder="00000-000"
                                maxlength="9"
                                onkeyup="mascaraCep(this)"
                                onblur="buscarCep(this.value)">
                        </div>
                        <div class="col-8 mb-3">
                            <label for="cidade_at" class="form-label fw-medium small text-secondary">Cidade</label>
                            <input type="text" class="form-control bg-light" id="cidade_at" name="cidade_at">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8 mb-3">
                            <label for="endereco" class="form-label fw-medium small text-secondary">Rua / Logradouro</label>
                            <input type="text" class="form-control bg-light" id="endereco" name="endereco">
                        </div>
                        <div class="col-4 mb-3">
                            <label for="numero" class="form-label fw-medium small text-secondary">Número</label>
                            <input type="text" class="form-control bg-light" id="numero" name="numero">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bairro_at" class="form-label fw-medium small text-secondary">Bairro</label>
                            <input type="text" class="form-control bg-light" id="bairro_at" name="bairro_at">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="uf" class="form-label fw-medium small text-secondary">Estado (UF)</label>
                            <input type="text" class="form-control bg-light" id="uf" name="uf" maxlength="2">
                        </div>
                    </div>

                    <!-- PROFISSIONAL -->
                    <h5 class="mb-3 mt-4 border-bottom pb-2 text-muted uppercase-title" style="font-size: 0.85rem; letter-spacing: 1px;"><i class="fa-solid fa-briefcase me-1"></i> Atuação</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="publico_atend" class="form-label fw-medium small text-secondary">Público de Atendimento</label>
                            <input type="text" class="form-control bg-light" id="publico_atend" name="publico_atend" placeholder="Ex: Crianças, Adolescentes, Adultos...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="modalidade" class="form-label fw-medium small text-secondary">Modalidade de atendimento</label>
                            <select class="form-select bg-light" id="modalidade" name="modalidade">
                                <option value="" disabled selected>Selecione uma opção</option>
                                <option value="Presencial">Presencial</option>
                                <option value="On-line">On-line</option>
                                <option value="Ambos">Ambos</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo_ass" class="form-label fw-medium small text-secondary">Tipo de Associado</label>
                            <select class="form-select bg-light" id="tipo_ass" name="tipo_ass">
                                <option value="Estudante">Estudante</option>
                                <option value="Profissional">Profissional</option>
                                <option value="Psiquiatra">Psiquiatra</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="crp-crm" class="form-label fw-medium small text-secondary">CRP / CRM</label>
                            <input type="text" class="form-control bg-light" id="crp-crm" name="crp-crm" placeholder="Ex: 123456789">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small text-secondary d-block">Realiza Acompanhamento Terapêutico?</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="acomp_terapeutico" id="at_sim" value="Sim">
                            <label class="form-check-label" for="at_sim">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="acomp_terapeutico" id="at_nao" value="Não" checked>
                            <label class="form-check-label" for="at_nao">Não</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mini_curr" class="form-label fw-medium small text-secondary">Mini Currículo</label>
                        <textarea class="form-control bg-light" id="mini_curr" name="mini_curr" rows="4" placeholder="Um breve resumo sobre formação e experiência..."></textarea>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3">
                        <a href="<?= defined('URL_BASE') ? URL_BASE : '' ?>/dashboard" class="btn btn-outline-secondary btn-sm px-3">Cancelar</a>
                        <button type="submit" class="btn text-white px-4 py-2 fw-bold shadow-sm" style="background-color: #527d76;">Cadastrar Associado</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- MÁSCARAS ---
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
    // -----------------

    // Lógica de Registro (Exemplo com fetch, ajuste conforme necessário)
    document.getElementById('form-registro').addEventListener('submit', function(e) {
        // Validação básica de senhas
        const senha = document.getElementById('senha').value;
        const confirma = document.getElementById('confirma_senha').value;

        if (senha !== confirma) {
            e.preventDefault();
            Swal.fire("Erro", "As senhas não conferem!", "error");
            return;
        }

        // Se quiser envio via AJAX:
        /*
        e.preventDefault();
        const formData = new FormData(this);
        fetch(this.action, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire("Sucesso", data.message, "success").then(() => window.location.href = 'dashboard');
            } else {
                Swal.fire("Erro", data.message, "error");
            }
        });
        */
    });

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
        let valorClean = cep.replace(/\D/g, '');
        if (valorClean.length === 8) {
            fetch(`https://viacep.com.br/ws/${valorClean}/json/`)
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