/* ==========================================================================
   Vital Brasil — Carrinho de compras (client-side, localStorage)
   Substitui o checkout com pagamento: o cliente monta o pedido (1 ou vários
   combos) e finaliza direto no WhatsApp do laboratório, com a mensagem já
   formatada. Sem backend, sem gateway de pagamento.
   Depende de: window.VB_WPP (número do WhatsApp, só dígitos).
   Botões que adicionam ao carrinho devem ter a classe .js-add-carrinho e os
   atributos data-slug, data-nome, data-preco.
   ========================================================================== */
(function () {
  'use strict';

  var KEY = 'vb_carrinho_v1';
  var WPP = String(window.VB_WPP || '').replace(/\D/g, '');

  /* ---------- estado ---------- */
  function load() {
    try { return JSON.parse(localStorage.getItem(KEY)) || []; }
    catch (e) { return []; }
  }
  function save(itens) {
    localStorage.setItem(KEY, JSON.stringify(itens));
  }

  /* ---------- helpers ---------- */
  function brl(v) {
    return 'R$ ' + Number(v).toLocaleString('pt-BR', {
      minimumFractionDigits: 2, maximumFractionDigits: 2
    });
  }
  function total(itens) {
    return itens.reduce(function (s, i) { return s + i.preco * i.qtd; }, 0);
  }
  function count(itens) {
    return itens.reduce(function (s, i) { return s + i.qtd; }, 0);
  }

  /* ---------- mutações ---------- */
  function add(item) {
    var itens = load();
    var achado = null;
    for (var i = 0; i < itens.length; i++) {
      if (itens[i].slug === item.slug) { achado = itens[i]; break; }
    }
    if (achado) { achado.qtd += 1; }
    else { itens.push({ slug: item.slug, nome: item.nome, preco: item.preco, qtd: 1 }); }
    save(itens);
    render();
    abrir();
  }
  function ajustar(slug, delta) {
    var itens = load();
    for (var i = 0; i < itens.length; i++) {
      if (itens[i].slug === slug) {
        itens[i].qtd += delta;
        if (itens[i].qtd <= 0) { itens.splice(i, 1); }
        break;
      }
    }
    save(itens);
    render();
  }
  function remover(slug) {
    save(load().filter(function (i) { return i.slug !== slug; }));
    render();
  }

  /* ---------- WhatsApp ---------- */
  function finalizar() {
    var itens = load();
    if (!itens.length) { return; }
    var linhas = itens.map(function (i, idx) {
      var unit = i.qtd > 1 ? ' (' + brl(i.preco) + ' cada)' : '';
      return (idx + 1) + '. ' + i.nome + ' — ' + i.qtd + 'x — ' + brl(i.preco * i.qtd) + unit;
    });
    var msg =
      'Olá, Vital Brasil! Gostaria de finalizar meu pedido pela loja online:\n\n' +
      linhas.join('\n') +
      '\n\n*Total: ' + brl(total(itens)) + '*' +
      '\n\nComo faço para o pagamento e o agendamento da coleta? Obrigado!';
    var url = 'https://wa.me/' + WPP + '?text=' + encodeURIComponent(msg);
    window.open(url, '_blank', 'noopener');
  }

  /* ---------- UI ---------- */
  var el = {};

  function montarUI() {
    // Botão flutuante
    var fab = document.createElement('button');
    fab.type = 'button';
    fab.className = 'vb-cart-fab';
    fab.setAttribute('aria-label', 'Abrir carrinho');
    fab.innerHTML =
      '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM7.2 14.6l.03-.15L8 12h7.5c.75 0 1.4-.41 1.73-1.03l3.24-5.88A1 1 0 0 0 19.6 3.6H6.2l-.94-2H2v2h2l3.6 7.6-1.35 2.44C5.52 14.37 6.48 16 7.9 16H20v-2H8.1c-.14 0-.25-.11-.25-.25l.03-.15z"/></svg>' +
      '<span class="vb-cart-fab__badge" hidden>0</span>';
    fab.addEventListener('click', abrir);

    // Overlay + drawer
    var overlay = document.createElement('div');
    overlay.className = 'vb-cart-overlay';
    overlay.addEventListener('click', fechar);

    var drawer = document.createElement('aside');
    drawer.className = 'vb-cart';
    drawer.setAttribute('role', 'dialog');
    drawer.setAttribute('aria-label', 'Seu carrinho');
    drawer.setAttribute('aria-hidden', 'true');
    drawer.innerHTML =
      '<header class="vb-cart__head">' +
      '  <h3>Seu carrinho</h3>' +
      '  <button type="button" class="vb-cart__close" aria-label="Fechar">&times;</button>' +
      '</header>' +
      '<div class="vb-cart__body"></div>' +
      '<footer class="vb-cart__foot">' +
      '  <div class="vb-cart__total"><span>Total</span><strong>R$ 0,00</strong></div>' +
      '  <button type="button" class="btn btn-wpp btn-lg btn-block vb-cart__wpp">' +
      '    <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3C9.4 3 4 8.4 4 15c0 2.1.6 4.1 1.6 5.9L4 29l8.3-1.6c1.7.9 3.6 1.4 5.7 1.4 6.6 0 12-5.4 12-12S22.6 3 16 3z"/></svg>' +
      '    Finalizar no WhatsApp</button>' +
      '  <p class="vb-cart__hint">Você conclui a compra conversando com o laboratório — sem pagamento online.</p>' +
      '</footer>';

    document.body.appendChild(fab);
    document.body.appendChild(overlay);
    document.body.appendChild(drawer);

    el.fab = fab;
    el.badge = fab.querySelector('.vb-cart-fab__badge');
    el.overlay = overlay;
    el.drawer = drawer;
    el.body = drawer.querySelector('.vb-cart__body');
    el.total = drawer.querySelector('.vb-cart__total strong');

    drawer.querySelector('.vb-cart__close').addEventListener('click', fechar);
    drawer.querySelector('.vb-cart__wpp').addEventListener('click', finalizar);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { fechar(); }
    });
  }

  function abrir() {
    el.drawer.classList.add('is-open');
    el.overlay.classList.add('is-open');
    el.drawer.setAttribute('aria-hidden', 'false');
    document.body.classList.add('vb-cart-lock');
  }
  function fechar() {
    el.drawer.classList.remove('is-open');
    el.overlay.classList.remove('is-open');
    el.drawer.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('vb-cart-lock');
  }

  function render() {
    var itens = load();
    var n = count(itens);

    // badge
    if (n > 0) { el.badge.textContent = n; el.badge.hidden = false; }
    else { el.badge.hidden = true; }
    el.fab.classList.toggle('has-itens', n > 0);

    // corpo
    if (!itens.length) {
      el.body.innerHTML = '<p class="vb-cart__empty">Seu carrinho está vazio.<br>Adicione uma rotina de exames para começar.</p>';
    } else {
      el.body.innerHTML = itens.map(function (i) {
        return '' +
          '<div class="vb-cart-item" data-slug="' + i.slug + '">' +
          '  <div class="vb-cart-item__info">' +
          '    <span class="vb-cart-item__nome">' + i.nome + '</span>' +
          '    <span class="vb-cart-item__preco">' + brl(i.preco) + '</span>' +
          '  </div>' +
          '  <div class="vb-cart-item__acoes">' +
          '    <div class="vb-qtd">' +
          '      <button type="button" class="vb-qtd__btn" data-acao="menos" aria-label="Diminuir">−</button>' +
          '      <span class="vb-qtd__n">' + i.qtd + '</span>' +
          '      <button type="button" class="vb-qtd__btn" data-acao="mais" aria-label="Aumentar">+</button>' +
          '    </div>' +
          '    <button type="button" class="vb-cart-item__rm" data-acao="rm" aria-label="Remover">Remover</button>' +
          '  </div>' +
          '</div>';
      }).join('');
    }

    el.total.textContent = brl(total(itens));
  }

  /* ---------- eventos ---------- */
  function bind() {
    // Adicionar ao carrinho (delegação — funciona em cards e página do combo)
    document.addEventListener('click', function (e) {
      var btn = e.target.closest ? e.target.closest('.js-add-carrinho') : null;
      if (!btn) { return; }
      e.preventDefault();
      add({
        slug: btn.getAttribute('data-slug'),
        nome: btn.getAttribute('data-nome'),
        preco: parseFloat(btn.getAttribute('data-preco')) || 0
      });
      btn.classList.add('is-added');
      var txt = btn.querySelector('.js-add-label') || btn;
      var orig = txt.textContent;
      txt.textContent = 'Adicionado ✓';
      setTimeout(function () { txt.textContent = orig; btn.classList.remove('is-added'); }, 1400);
    });

    // Ações dentro do carrinho
    el.body.addEventListener('click', function (e) {
      var alvo = e.target.closest ? e.target.closest('[data-acao]') : null;
      if (!alvo) { return; }
      var item = alvo.closest('.vb-cart-item');
      var slug = item && item.getAttribute('data-slug');
      if (!slug) { return; }
      var acao = alvo.getAttribute('data-acao');
      if (acao === 'mais') { ajustar(slug, +1); }
      else if (acao === 'menos') { ajustar(slug, -1); }
      else if (acao === 'rm') { remover(slug); }
    });
  }

  /* ---------- init ---------- */
  function init() {
    montarUI();
    bind();
    render();
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
