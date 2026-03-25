document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-forgot');
    const baseUrl = (typeof BASE_URL !== 'undefined') ? BASE_URL : '';

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btn-send');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Enviando...';
            btn.disabled = true;

            const formData = new FormData(this);

            // Ajuste robusto de URL
            const targetUrl = `${baseUrl}/esqueci-senha/enviar`.replace('//esqueci', '/esqueci');

            fetch(targetUrl, {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                const text = await response.text(); // Pega o texto bruto
                console.log("Resposta Bruta do Servidor:", text); // Mostra no console (F12)

                try {
                    return JSON.parse(text); // Tenta converter para JSON
                } catch (err) {
                    throw new Error("O servidor retornou um erro não-JSON (veja console). Provável erro PHP.");
                }
            })
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'E-mail Enviado!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#0d6efd'
                    });
                    form.reset();
                } else {
                    Swal.fire('Erro', data.message || 'Erro desconhecido.', 'error');
                }
            })
            .catch(error => {
                console.error('Erro Fetch:', error);
                Swal.fire('Erro no Sistema', error.message, 'error');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }
});