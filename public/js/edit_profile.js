/**
 * edit_profile.js
 * Gerenciamento da edição de perfil com suporte a upload de fotos e feedback visual.
 */

document.addEventListener("DOMContentLoaded", function () {
  // Nota: Ajustado para o ID 'form-perfil' conforme o trecho selecionado
  const form = document.getElementById("form-perfil");

  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();

      // Validação de senha (mantida do arquivo original)
      const senhaInput = document.getElementById("senha");
      const confirmaInput = document.getElementById("confirma_senha");

      if (senhaInput && confirmaInput) {
        const senha = senhaInput.value;
        const confirma = confirmaInput.value;

        if (senha && senha !== confirma) {
          Swal.fire("Erro", "As senhas não conferem!", "error");
          return;
        }
      }

      // Início da lógica do trecho selecionado: Feedback de carregamento no botão
      const btn = e.target.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Salvando...';

      try {
        // O construtor FormData com 'this' captura o arquivo do input type="file"
        const formData = new FormData(this);
        const actionUrl = this.getAttribute("action") || "api/perfil/update";

        const response = await fetch(actionUrl, {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Sucesso!",
            text: result.message,
            timer: 4000,
            confirmButtonText: "Voltar para o painel",
          }).then(() => {
            // Redireciona de volta para a dashboard administrativa
            window.location.href = BASE_URL + "/dashboard";
          });
        } else {
          throw new Error(result.message);
        }
      } catch (error) {
        console.error("Erro na atualização:", error);
        Swal.fire(
          "Erro",
          error.message || "Erro ao processar requisição",
          "error",
        );
      } finally {
        // Restaura o estado original do botão
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    });
  }

  // Preview Simples de Imagem (mantido do original)
  const inputFoto = document.getElementById("foto");
  if (inputFoto) {
    inputFoto.addEventListener("change", function (e) {
      if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          const preview = document.getElementById("preview-foto");
          if (preview) {
            preview.src = e.target.result;
          }
        };
        reader.readAsDataURL(e.target.files[0]);
      }
    });
  }
});
