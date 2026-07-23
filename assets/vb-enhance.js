/* Vital Brasil — melhorias de layout do site (pill no hero + cards de acesso rápido)
   Inserção via JS para não mexer no HTML minificado do Elementor.
   Se algum seletor não bater, simplesmente não injeta (não quebra a página). */
(function () {
  "use strict";
  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  var ICON = {
    laudo: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M9 13l2 2 4-4"/></svg>',
    loja:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3v2m6-2v2M8 8l1 11a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2l1-11"/><path d="M6 8h12"/><path d="M12 8V5"/></svg>',
    conv:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-4.35-7-10a4 4 0 0 1 7-2.65A4 4 0 0 1 19 11c0 5.65-7 10-7 10z"/></svg>',
    wpp:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-8.5 8.5 8.5 8.5 0 0 1-3.9-.9L3 21l1.9-5.6A8.5 8.5 0 1 1 21 11.5z"/></svg>',
    arrow: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>'
  };

  var CARDS = [
    { icon: "laudo", title: "Resultados de exames", sub: "Acesse seus laudos online", href: "/resultados/", blank: false },
    { icon: "loja",  title: "Comprar exames",        sub: "Rotinas e combos na loja",  href: "/loja/",                       blank: false },
    { icon: "conv",  title: "Convênios",             sub: "Veja os parceiros",          href: "/#convenios",                  blank: false },
    { icon: "wpp",   title: "Fale no WhatsApp",      sub: "Agende sua coleta",          href: "https://wa.me/555331990378",   blank: true }
  ];

  ready(function () {
    var h1 = document.querySelector("main h1, h1");
    if (!h1) return;

    // 1) Pill acima do título do hero
    if (!document.querySelector(".vb-hero-pill")) {
      var pill = document.createElement("div");
      pill.className = "vb-hero-pill";
      pill.innerHTML = '<span class="vb-hero-pill__dot"></span> Laboratório em Pelotas/RS · Atendimento por agendamento';
      h1.parentNode.insertBefore(pill, h1);
    }

    // 2) Fileira de cards logo após a seção do hero
    if (!document.querySelector(".vb-quick")) {
      var parent = document.querySelector(".e-con.e-parent") || document.querySelector(".elementor-section");
      var heroSection = null;
      if (parent) {
        for (var i = 0; i < parent.children.length; i++) {
          if (parent.children[i].contains(h1)) { heroSection = parent.children[i]; break; }
        }
      }
      var band = document.createElement("section");
      band.className = "vb-quick";
      band.setAttribute("aria-label", "Acesso rápido");
      var inner = '<div class="vb-quick__inner">';
      CARDS.forEach(function (c) {
        inner += '<a class="vb-quick__card" href="' + c.href + '"' + (c.blank ? ' target="_blank" rel="noopener"' : '') + '>' +
          '<span class="vb-quick__icon">' + ICON[c.icon] + '</span>' +
          '<span class="vb-quick__body"><span class="vb-quick__title">' + c.title + '</span>' +
          '<span class="vb-quick__sub">' + c.sub + '</span></span>' +
          '<span class="vb-quick__arrow">' + ICON.arrow + '</span></a>';
      });
      inner += '</div>';
      band.innerHTML = inner;

      if (heroSection && heroSection.parentNode) {
        heroSection.parentNode.insertBefore(band, heroSection.nextSibling);
      } else {
        // fallback: logo após o header
        var main = document.querySelector("main") || document.body;
        main.insertBefore(band, main.firstChild);
      }
    }
  });
})();
