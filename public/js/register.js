document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-registro");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      // Validação de senha
      const senha = document.getElementById("senha").value;
      const confirma = document.getElementById("confirma_senha").value;

      if (senha !== confirma) {
        Swal.fire("Erro", "As senhas não conferem!", "error");
        return;
      }

      const formData = new FormData(this);
      const actionUrl = this.getAttribute("action");

      // Feedback
      Swal.fire({
        title: "Processando...",
        text: "Cadastrando novo associado.",
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });

      fetch(actionUrl, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            Swal.fire({
              title: "Sucesso!",
              text: data.message,
              timer: 4000,
              icon: "success",
              confirmButtonText: "Voltar para o painel",
            }).then(() => {
              // Redireciona de volta para a dashboard administrativa
              window.location.href = BASE_URL + "/dashboard";
            });
          } else {
            Swal.fire("Erro", data.message || "Erro desconhecido", "error");
          }
        })
        .catch((error) => {
          console.error("Erro:", error);
          Swal.fire(
            "Erro",
            "Ocorreu um erro ao processar o cadastro.",
            "error",
          );
        });
    });
  }
});
