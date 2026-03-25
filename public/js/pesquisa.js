/**
 * pesquisa.js
 * Gerenciamento da busca avançada de profissionais.
 */

document.addEventListener('DOMContentLoaded', () => {
    const CONFIG = {
        urlApi: 'api/pesquisa',
        form: document.getElementById('filtro-form'),
        container: document.getElementById('resultado-pesquisa'),
        contador: document.getElementById('contador-resultados'),
        btnLimpar: document.getElementById('btn-limpar'),
        excecoesNomes: ['de', 'do', 'da', 'dos', 'das', 'e']
    };

    // --- Auxiliares ---
    const shuffleArray = (arr) => {
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr;
    };

    const formatName = (fullName) => {
        if (!fullName || typeof fullName !== 'string') return '';
        return fullName.toLowerCase().split(' ').map((word, i) => {
            if (CONFIG.excecoesNomes.includes(word) && i > 0) return word;
            return word.charAt(0).toUpperCase() + word.slice(1);
        }).join(' ');
    };

    const getDisplayName = (prof) => {
        if (prof.nomever?.trim()) return prof.nomever;
        const names = prof.nome_completo?.trim().split(/\s+/) || ['Profissional'];
        return names.length > 1 ? `${names[0]} ${names[names.length - 1]}` : names[0];
    };

    // --- Componentes UI ---
    const createProfessionalCard = (prof) => {
        const finalNome = formatName(getDisplayName(prof));
        const foto = prof.foto || 'sem-foto.png';
        const cargo = prof.tipo_ass === 'Psiquiatra' ? 'Psiquiatra' : 'Psicológo(a)';
        const zapLink = (prof.celular && prof.celular !== 'null') 
            ? `https://web.whatsapp.com/send?phone=55${prof.celular.replace(/\D/g, '')}` 
            : null;

        return `
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card-professional">
                    <div class="profile-img-wrapper">
                        <img src="./assets/foto/${foto}" alt="Foto de ${finalNome}" class="profile-img">
                    </div>

                    <h5>${finalNome}</h5>
                    <p class="cargo">${cargo}</p>
                    <span class="registro">${prof.crp_crm || ''}</span>
                    
                    <button type="button" class="btn btn-collapse btn-light" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#collapse-${prof.id_associados}" 
                        aria-expanded="false" 
                        aria-controls="collapse-${prof.id_associados}">
                        + Mostrar mais...
                    </button>
                    
                    <div class="collapse" id="collapse-${prof.id_associados}">
                        <div class="collapse-content">
                            ${zapLink ? `
                                <span class="perfil">                                    
                                    <strong>Celular:</strong> 
                                    <a href="${zapLink}" target="_blank">${prof.celular} <i class="fab fa-whatsapp"></i></a>
                                </span><br>` : ''}

                            ${(prof.mini_curr && prof.mini_curr !== 'null') ? `
                                <span class="perfil curri"><strong>Mini Currículo:</strong> ${prof.mini_curr}</span><br>` : ''}

                            ${(prof.publico_atend && prof.publico_atend !== 'null') ? `
                                <span class="perfil"><strong>Público:</strong> ${prof.publico_atend}</span><br>` : ''}
  
                            ${(prof.rede_social && prof.rede_social !== 'null') ? `
                                <span class="perfil"><strong>Social:</strong> ${prof.rede_social}</span><br>` : ''}
                        </div>
                    </div>  
                </div>
            </div>`;
    };

    // --- Lógica Principal ---

    const setupCollapseEvents = () => {
        document.querySelectorAll('.collapse').forEach(el => {
            const btn = document.querySelector(`[data-bs-target="#${el.id}"]`);
            if (!btn) return;

            // Remove listeners antigos para evitar duplicação em múltiplas buscas
            el.replaceWith(el.cloneNode(true));
            const newEl = document.getElementById(el.id);

            newEl.addEventListener('show.bs.collapse', () => btn.textContent = '- Mostrar menos...');
            newEl.addEventListener('hide.bs.collapse', () => btn.textContent = '+ Mostrar mais...');
        });
    };

    const renderResults = (professionals) => {
        CONFIG.container.innerHTML = '';
        
        if (professionals.length === 0) {
            CONFIG.contador.classList.add('d-none');
            CONFIG.container.innerHTML = `<p class="text-center text-muted w-100">Nenhum profissional encontrado com os critérios selecionados.</p>`;
            return;
        }

        CONFIG.contador.textContent = `${professionals.length} resultado(s) encontrado(s).`;
        CONFIG.contador.classList.remove('d-none');

        const shuffled = shuffleArray([...professionals]);
        const html = shuffled.map(prof => createProfessionalCard(prof)).join('');
        
        CONFIG.container.innerHTML = html;
        setupCollapseEvents();
    };

    const realizarPesquisa = async (e) => {
        if (e) e.preventDefault();

        CONFIG.contador.classList.add('d-none');
        CONFIG.container.innerHTML = `
            <div class="text-center w-100 py-5">
                <div class="spinner-border text-success" role="status"></div>
                <p class="mt-2 text-muted">Buscando profissionais...</p>
            </div>`;

        try {
            const formData = new FormData(CONFIG.form);
            const response = await fetch(CONFIG.urlApi, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error(`Erro: ${response.status}`);
            
            const data = await response.json();
            renderResults(data);
        } catch (error) {
            console.error("Erro na pesquisa:", error);
            CONFIG.container.innerHTML = `<p class="text-center text-danger w-100">Ocorreu um erro ao processar sua busca. Tente novamente.</p>`;
        }
    };

    const limparFiltros = () => {
        CONFIG.form.reset();
        CONFIG.contador.classList.add('d-none');
        CONFIG.container.innerHTML = `<p class="text-center text-muted w-100">Utilize os filtros acima para iniciar sua busca.</p>`;
    };

    // --- Listeners ---
    CONFIG.form.addEventListener('submit', realizarPesquisa);
    CONFIG.btnLimpar.addEventListener('click', limparFiltros);
});