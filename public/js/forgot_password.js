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

            fetch(`${baseUrl}/esqueci-senha/enviar`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {

                console.log(data); // Log da resposta para depuração
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Link Enviado!',
                        text: data.message, // Mensagem genérica de segurança
                        icon: 'success',
                        confirmButtonColor: '#0d6efd'
                    });
                    form.reset();
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire('Erro', 'Ocorreu um erro ao tentar enviar o e-mail.', 'error');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }
});