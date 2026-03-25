$(document).ready(function () {
  // Selecionamos o elemento uma única vez para ganhar performance
  var $navbar = $("#mainNavbar");

  function checkScroll() {
    // Verifica se a posição do scroll é maior que 50 pixels
    if ($(window).scrollTop() > 50) {
      // Adiciona a classe e aplica o estilo diretamente via jQuery
      $navbar.addClass("affix");
    } else {
      // Remove a classe e o estilo
      $navbar.removeClass("affix");
    }
  }

  // Executa ao carregar a página
  checkScroll();

  // Executa ao scrollar
  $(window).on("scroll", function () {
    checkScroll();
  });
});
