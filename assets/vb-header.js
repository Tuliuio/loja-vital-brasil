/* Vital Brasil — comportamento do header unificado (site + loja) */
(function () {
  "use strict";
  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  ready(function () {
    var header = document.getElementById("vb-header");
    if (!header) return;

    var burger = header.querySelector(".vb-header__burger");
    var nav = header.querySelector(".vb-header__nav");

    // Abrir/fechar menu mobile
    if (burger && nav) {
      burger.addEventListener("click", function () {
        var open = header.classList.toggle("vb-nav-open");
        burger.setAttribute("aria-expanded", open ? "true" : "false");
      });
      // Fecha ao clicar num link
      nav.addEventListener("click", function (e) {
        if (e.target.closest("a")) {
          header.classList.remove("vb-nav-open");
          burger.setAttribute("aria-expanded", "false");
        }
      });
    }

    // Sombra/realce ao rolar
    var onScroll = function () {
      header.classList.toggle("is-scrolled", window.scrollY > 8);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  });
})();
