<div class="container" style="margin-top: 10rem;">
    <h2 class="text-center mb-4">PESQUISA AVANÇADA</h2>

    <div class="p-4 mb-5" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 2.3rem;">
        <form id="filtro-form">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="filtro-nome" class="form-label">Profissional:</label>
                    <input type="text" id="filtro-nome" class="form-control" name="nome" placeholder="Nome ou parte do nome">
                </div>
                <div class="col-md-4">
                    <label for="filtro-publico" class="form-label">Público de atendimento:</label>
                    <select id="filtro-publico" class="form-select" name="publico_atend">
                        <option value="Todos" selected>Todos</option>
                        <option value="Criança">Criança</option>
                        <option value="Adolescente">Adolescente</option>
                        <option value="Adulto">Adulto</option>
                        <option value="Casal">Casal</option>
                        <option value="Idoso">Idoso</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filtro-modalidade_atendimento" class="form-label">Modalidade:</label>
                    <select id="filtro-modalidade_atendimento" class="form-select" name="mod_atendimento">
                        <option value="Todos" selected>Todos</option>
                        <option value="Ambos">Ambos</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Online">Online</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="filtro-plano" class="form-label">Atende Plano de Saúde:</label>
                    <select id="filtro-plano" class="form-select" name="plano_saude">
                        <option value="Todos" selected>Todos</option>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filtro-cidade" class="form-label">Cidade:</label>
                    <input type="text" id="filtro-cidade" class="form-control" name="cidade">
                </div>
                <div class="col-md-4">
                    <label for="filtro-bairro" class="form-label">Bairro:</label>
                    <input type="text" id="filtro-bairro" class="form-control" name="bairro">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-center text-md-start">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_profissional_radio" id="radio-psicologos" value="Profissional">
                        <label class="form-check-label" border-radius: 2.3rem; for="radio-psicologos">Apenas psicólogos</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_profissional_radio" id="radio-psiquiatras" value="Psiquiatra">
                        <label class="form-check-label" for="radio-psiquiatras">Apenas psiquiatras</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_profissional_radio" id="radio-todos" value="Todos" checked>
                        <label class="form-check-label" for="radio-todos">Todos</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                <button type="submit" id="btn-filtrar" class="btn btn-success px-4">
                    <i class="fas fa-search me-1"></i> Filtrar
                </button>
                <button type="button" id="btn-limpar" class="btn btn-secondary px-4">
                    <i class="fas fa-sync me-1"></i> Limpar
                </button>
                <a href="home" class="btn btn-info px-4">
                    <i class="fas fa-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </form>
    </div>

    <h4 id="contador-resultados" class="mb-4 d-none text-primary"></h4>
    
    <div id="resultado-pesquisa" class="row">
        <p class="text-center text-muted w-100">Utilize os filtros acima para iniciar sua busca.</p>
    </div>
</div>