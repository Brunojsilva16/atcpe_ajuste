const BASE_API = typeof BASE_URL !== "undefined" ? BASE_URL : "";

// const API_BASE = '<?= defined('URL_BASE') ? URL_BASE : '' ?>';

// function toggleStatus(id, currentStatus) {
//   Swal.fire({
//     title: "Alterar status?",
//     text: `Deseja ${currentStatus == 1 ? "desativar" : "ativar"} este associado?`,
//     icon: "question",
//     showCancelButton: true,
//     confirmButtonText: "Sim, confirmar!",
//     cancelButtonText: "Cancelar",
//     confirmButtonColor: "#527d76",
//     cancelButtonColor: "#6c757d",
//   }).then((result) => {
//     if (result.isConfirmed) {
//       const formData = new FormData();
//       formData.append("id", id);
//       formData.append("status", currentStatus);

//       fetch(`${API_BASE}/admin/toggle-status`, {
//         method: "POST",
//         body: formData,
//       })
//         .then((res) => res.json())
//         .then((data) => {
//           if (data.success) {
//             Swal.fire("Sucesso!", "Status atualizado.", "success").then(() =>
//               location.reload(),
//             );
//           } else {
//             Swal.fire(
//               "Erro",
//               data.message || "Erro ao alterar status",
//               "error",
//             );
//           }
//         })
//         .catch((err) => console.error(err));
//     }
//   });
// }

// function deleteAssociate(id) {
//   Swal.fire({
//     title: "Tem certeza?",
//     text: "Esta ação não pode ser desfeita!",
//     icon: "warning",
//     showCancelButton: true,
//     confirmButtonColor: "#d33",
//     cancelButtonColor: "#3085d6",
//     confirmButtonText: "Sim, excluir!",
//     cancelButtonText: "Cancelar",
//   }).then((result) => {
//     if (result.isConfirmed) {
//       const formData = new FormData();
//       formData.append("id", id);

//       fetch(`${API_BASE}/admin/delete`, {
//         method: "POST",
//         body: formData,
//       })
//         .then((res) => res.json())
//         .then((data) => {
//           if (data.status === "success") {
//             Swal.fire("Excluído!", "O associado foi removido.", "success").then(
//               () => location.reload(),
//             );
//           } else {
//             Swal.fire("Erro", data.message || "Erro ao excluir", "error");
//           }
//         });
//     }
//   });
// }

// async function toggleStatus(id, currentStatus) {
//   // Feedback visual imediato (opcional)
//   Swal.fire({
//     title: "Atualizando...",
//     didOpen: () => Swal.showLoading(),
//   });

//   try {
//     const formData = new FormData();
//     formData.append("id", id);
//     formData.append("status", currentStatus);

//     const response = await fetch(`${BASE_API}/admin/toggle-status`, {
//       method: "POST",
//       body: formData,
//     });

//     // Primeiro pegamos o texto puro para depurar se não for JSON
//     const text = await response.text();

//     let data;
//     try {
//       data = JSON.parse(text);
//     } catch (e) {
//       console.error("Erro ao analisar JSON. Resposta do servidor:", text);
//       Swal.fire(
//         "Erro no Servidor",
//         "O servidor retornou uma resposta inválida. Verifique o console.",
//         "error",
//       );
//       return;
//     }

//     if (data.success) {
//       Swal.fire({
//         icon: "success",
//         title: "Status atualizado!",
//         showConfirmButton: false,
//         timer: 1500,
//       }).then(() => {
//         location.reload();
//       });
//     } else {
//       Swal.fire("Erro", data.message || "Erro desconhecido", "error");
//     }
//   } catch (error) {
//     console.error("Erro na requisição:", error);
//     Swal.fire("Erro", "Falha na comunicação com o servidor.", "error");
//   }
// }

// async function deleteAssociate(id) {
//   const result = await Swal.fire({
//     title: "Tem certeza?",
//     text: "Essa ação não pode ser revertida!",
//     icon: "warning",
//     showCancelButton: true,
//     confirmButtonColor: "#d33",
//     cancelButtonColor: "#3085d6",
//     confirmButtonText: "Sim, excluir!",
//     cancelButtonText: "Cancelar",
//   });

//   if (result.isConfirmed) {
//     try {
//       const formData = new FormData();
//       formData.append("id", id);

//       const response = await fetch(`${BASE_API}/admin/delete`, {
//         method: "POST",
//         body: formData,
//       });

//       const text = await response.text();
//       let data;
//       try {
//         data = JSON.parse(text);
//       } catch (e) {
//         console.error("Erro ao analisar JSON de exclusão:", text);
//         Swal.fire("Erro", "Resposta inválida do servidor.", "error");
//         return;
//       }

//       if (data.success) {
//         Swal.fire("Excluído!", "O associado foi removido.", "success").then(
//           () => location.reload(),
//         );
//       } else {
//         Swal.fire("Erro", data.message || "Não foi possível excluir.", "error");
//       }
//     } catch (error) {
//       console.error(error);
//       Swal.fire("Erro", "Falha na requisição.", "error");
//     }
//   }
// }
