/**
 * carousel.js
 * Gerenciamento do carrossel de profissionais com Bootstrap 5.
 */

document.addEventListener('DOMContentLoaded', () => {
    const CONFIG = {
        urlApi: 'api/carousel',
        container: document.getElementById('carousel-container'),
        itemsPerSlide: 3,
        carouselId: 'professional-carousel',
        interval: 5000,
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
        const names = prof.nome_completo.trim().split(/\s+/);
        return names.length > 1 ? `${names[0]} ${names[names.length - 1]}` : names[0];
    };

    // --- Componentes UI ---

    const createProfessionalCard = (prof) => {
        const finalNome = formatName(getDisplayName(prof));
        const foto = prof.foto || 'sem-foto.png';
        const cargo = prof.tipo_ass === 'Psiquiatra' ? 'Psiquiatra' : 'Psicológo(a)';
        const zapLink = prof.celular ? `https://web.whatsapp.com/send?phone=55${prof.celular.replace(/\D/g, '')}` : null;

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

                            ${prof.mini_curr ? `
                                <span class="perfil curri"><strong>Mini Currículo:</strong> ${prof.mini_curr}</span><br>` : ''}

                            ${prof.publico_atend ? `
                                <span class="perfil"><strong>Público:</strong> ${prof.publico_atend}</span><br>` : ''}

                            ${prof.rede_social ? `
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

            el.addEventListener('show.bs.collapse', () => btn.textContent = '- Mostrar menos...');
            el.addEventListener('hide.bs.collapse', () => btn.textContent = '+ Mostrar mais...');
        });
    };

    const renderCarousel = (professionals) => {
        CONFIG.container.innerHTML = '';
        const shuffled = shuffleArray([...professionals]);
        
        // Agrupa profissionais em slides
        for (let i = 0; i < shuffled.length; i += CONFIG.itemsPerSlide) {
            const isActive = i === 0 ? 'active' : '';
            const slice = shuffled.slice(i, i + CONFIG.itemsPerSlide);
            
            const slideHtml = `
                <div class="carousel-item ${isActive}">
                    <div class="row">
                        ${slice.map(prof => createProfessionalCard(prof)).join('')}
                    </div>
                </div>`;
            
            CONFIG.container.insertAdjacentHTML('beforeend', slideHtml);
        }

        setupCollapseEvents();

        // Inicializa Bootstrap Carousel
        const carouselEl = document.getElementById(CONFIG.carouselId);
        if (carouselEl) {
            new bootstrap.Carousel(carouselEl, {
                interval: CONFIG.interval,
                wrap: true
            });
        }
    };

    const loadProfessionals = async () => {
        try {
            const response = await fetch(CONFIG.urlApi, { method: 'POST' });
            if (!response.ok) throw new Error(`Erro: ${response.status}`);
            
            const data = await response.json();
            renderCarousel(data);
        } catch (error) {
            console.error("Erro ao carregar profissionais:", error);
            CONFIG.container.innerHTML = `<p class="text-center text-danger">Erro ao carregar os perfis.</p>`;
        }
    };

    loadProfessionals();
});