document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-reset');
    const baseUrl = (typeof BASE_URL !== 'undefined') ? BASE_URL : '';

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const p1 = document.getElementById('senha').value;
            const p2 = document.getElementById('confirma_senha').value;

            if (p1 !== p2) {
                Swal.fire('Atenção', 'As senhas não coincidem.', 'warning');
                return;
            }

            if (p1.length < 6) {
                Swal.fire('Atenção', 'A senha deve ter no mínimo 6 caracteres.', 'warning');
                return;
            }

            const formData = new FormData(this);

            Swal.fire({
                title: 'Atualizando...',
                text: 'Aguarde um momento',
                didOpen: () => Swal.showLoading()
            });

            fetch(`${baseUrl}/redefinir-senha/atualizar`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#198754'
                    }).then(() => {
                        window.location.href = `${baseUrl}/login`;
                    });
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire('Erro', 'Não foi possível atualizar a senha.', 'error');
            });
        });
    }
});