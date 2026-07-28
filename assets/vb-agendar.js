/* ==========================================================================
   Vital Brasil — Modal de pré-agendamento + captura de leads
   Intercepta os CTAs de WhatsApp (agendamento), qualifica o lead e monta a
   mensagem antes de abrir o WhatsApp. Registra cada lead numa planilha do
   Google (Apps Script) sem backend.

   Progressive enhancement: sem JS, os links de WhatsApp continuam funcionando
   normalmente (vão direto ao wa.me). O modal é só uma camada por cima.

   Configuração: preencha LEAD_ENDPOINT com a URL do Web App do Apps Script.
   Vazio = site funciona normal, só NÃO registra o lead na planilha.
   ========================================================================== */
(function () {
  "use strict";

  /* -------------------- CONFIG (ajuste aqui) -------------------- */
  var LEAD_ENDPOINT = "";                 // URL do Web App do Apps Script (vazio = não registra)
  var WA_NUMBER = "555331990378";         // (53) 3199-0378 — Pelotas/RS
  var HORARIO = "Seg a Sex, 7h–17h";      // usado na nota do modal
  var TEM_COLETA_DOMICILIAR = true;       // false remove o campo "Local da coleta"

  // Lista do laboratório. Grupos viram <optgroup>. O "value" de cada item é o
  // que casa com data-exame="..." nos botões de página de exame.
  var EXAM_GROUPS = [
    { label: "Check-ups e rotinas", items: [
      "Rotina Vital Mulher",
      "Rotina Vital Homem",
      "Rotina Vital Infantil",
      "Rotina Vital Performance",
      "Check-up de Vitaminas",
      "Painel Hormonal Feminino",
      "Painel Queda Capilar"
    ]},
    { label: "Exames avulsos", items: [
      "Hemograma completo",
      "Glicose",
      "Colesterol / Perfil lipídico",
      "TSH e T4 (tireoide)",
      "Vitamina D",
      "Ureia e Creatinina",
      "Exame de urina (EAS)",
      "Exame de fezes",
      "Beta HCG (gravidez)",
      "PSA (próstata)"
    ]}
  ];

  var UTM_KEYS = ["utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content", "gclid", "fbclid"];

  /* -------------------- Atribuição (UTM / gclid / fbclid) -------------------- */
  function captureAttribution() {
    try {
      var qs = new URLSearchParams(location.search), found = false, s = {};
      UTM_KEYS.forEach(function (k) { if (qs.get(k)) { s[k] = qs.get(k); found = true; } });
      if (found) sessionStorage.setItem("vb_attr", JSON.stringify(s));
      if (!sessionStorage.getItem("vb_ref") && document.referrer &&
          document.referrer.indexOf(location.host) === -1) {
        sessionStorage.setItem("vb_ref", document.referrer);
      }
    } catch (e) {}
  }
  function getAttribution() {
    var a = {};
    try { a = JSON.parse(sessionStorage.getItem("vb_attr") || "{}"); } catch (e) {}
    UTM_KEYS.forEach(function (k) { a[k] = a[k] || ""; });
    try { a.referrer = sessionStorage.getItem("vb_ref") || document.referrer || ""; }
    catch (e) { a.referrer = document.referrer || ""; }
    return a;
  }
  captureAttribution();

  /* -------------------- Envio do lead (não bloqueia o WhatsApp) -------------------- */
  function logLead(d) {
    if (!LEAD_ENDPOINT) return;
    try {
      var p = JSON.stringify(d);
      if (navigator.sendBeacon) {
        navigator.sendBeacon(LEAD_ENDPOINT, new Blob([p], { type: "application/json" }));
      } else {
        fetch(LEAD_ENDPOINT, {
          method: "POST", mode: "no-cors",
          headers: { "Content-Type": "application/json" }, body: p
        });
      }
    } catch (e) {}
  }

  function waLink(m) { return "https://wa.me/" + WA_NUMBER + "?text=" + encodeURIComponent(m); }
  var MSG_GENERICO = "Olá! Vim pelo site da Vital Brasil e gostaria de agendar um exame.";

  /* -------------------- Modal -------------------- */
  var modal, els = {};

  function buildOptions() {
    var html = '<option value="">Selecione o exame…</option>';
    EXAM_GROUPS.forEach(function (g) {
      html += '<optgroup label="' + g.label + '">';
      g.items.forEach(function (e) { html += '<option value="' + e + '">' + e + '</option>'; });
      html += '</optgroup>';
    });
    html += '<option value="__orientacao__">Ainda não sei / quero orientação</option>';
    return html;
  }

  function buildModal() {
    var localBlock = "";
    if (TEM_COLETA_DOMICILIAR) {
      localBlock =
        '<div class="vb-field"><span>Local da coleta</span><div class="vb-chips">' +
          '<label class="vb-chip"><input type="radio" name="vbLocal" value="unidade"><span>Na unidade</span></label>' +
          '<label class="vb-chip"><input type="radio" name="vbLocal" value="domiciliar"><span>Domiciliar</span></label>' +
        '</div></div>';
    }

    var w = document.createElement("div");
    w.className = "vb-modal-overlay";
    w.setAttribute("hidden", "");
    w.innerHTML =
      '<div class="vb-modal" role="dialog" aria-modal="true" aria-labelledby="vbAgTitle">' +
        '<button class="vb-modal-close" type="button" aria-label="Fechar">✕</button>' +
        '<div class="vb-modal-head">' +
          '<span class="vb-eyebrow">Agendamento</span>' +
          '<h2 id="vbAgTitle">Solicite seu horário</h2>' +
          '<p>Escolha sua preferência e a nossa equipe confirma pelo WhatsApp.</p>' +
        '</div>' +
        '<div class="vb-modal-body">' +
          '<label class="vb-field"><span>Qual exame?</span><select id="vbExame">' + buildOptions() + '</select></label>' +
          '<div class="vb-field-row">' +
            '<label class="vb-field"><span>Preferência de data</span><input type="date" id="vbData"></label>' +
            '<div class="vb-field"><span>Período</span><div class="vb-chips">' +
              '<label class="vb-chip"><input type="radio" name="vbPeriodo" value="manhã"><span>Manhã</span></label>' +
              '<label class="vb-chip"><input type="radio" name="vbPeriodo" value="tarde"><span>Tarde</span></label>' +
            '</div></div>' +
          '</div>' +
          localBlock +
          '<label class="vb-field"><span>Seu nome (opcional)</span><input type="text" id="vbNome" placeholder="Como podemos te chamar?" autocomplete="name"></label>' +
        '</div>' +
        '<div class="vb-modal-foot">' +
          '<a class="vb-btn-wa wa-direct" id="vbConfirm" target="_blank" rel="noopener" href="' + waLink(MSG_GENERICO) + '">Confirmar pelo WhatsApp</a>' +
          '<button class="vb-modal-direct" type="button" id="vbDirect">Prefiro falar direto no WhatsApp</button>' +
          '<p class="vb-modal-note">Nossa equipe confirma o horário. Resposta no horário de atendimento: ' + HORARIO + '.</p>' +
        '</div>' +
      '</div>';

    document.body.appendChild(w);
    modal = w;

    els.exame = w.querySelector("#vbExame");
    els.data = w.querySelector("#vbData");
    els.nome = w.querySelector("#vbNome");
    els.confirm = w.querySelector("#vbConfirm");
    els.direct = w.querySelector("#vbDirect");
    els.data.min = new Date().toISOString().split("T")[0];

    function selected(name) {
      var el = w.querySelector('input[name="' + name + '"]:checked');
      return el ? el.value : "";
    }

    function buildMsg() {
      var ex = els.exame.value, per = selected("vbPeriodo"),
          loc = selected("vbLocal"), nome = els.nome.value.trim(), m;
      if (ex === "__orientacao__") m = "Olá! Vim pelo site da Vital Brasil e gostaria de orientação sobre qual exame realizar.";
      else if (ex) m = "Olá! Vim pelo site da Vital Brasil e gostaria de agendar: " + ex + ".";
      else m = MSG_GENERICO;
      var p = [];
      if (els.data.value) { var d = els.data.value.split("-"); p.push("dia " + d[2] + "/" + d[1]); }
      if (per) p.push("no período da " + per);
      if (p.length) m += " Minha preferência: " + p.join(", ") + ".";
      if (loc) m += " Coleta: " + loc + ".";
      if (nome) m += " Meu nome é " + nome + ".";
      return m;
    }

    function refresh() { els.confirm.href = waLink(buildMsg()); }
    w.addEventListener("input", refresh);
    w.addEventListener("change", refresh);

    function lead(origem) {
      return Object.assign({
        timestamp: new Date().toISOString(),
        exame: els.exame.value === "__orientacao__" ? "Orientação" : (els.exame.value || ""),
        data_preferida: els.data.value || "",
        periodo: selected("vbPeriodo"),
        local_coleta: selected("vbLocal"),
        nome: els.nome.value.trim(),
        origem: origem,
        pagina: location.pathname
      }, getAttribution());
    }

    els.confirm.addEventListener("click", function () { logLead(lead("modal-confirmar")); closeModal(); });
    els.direct.addEventListener("click", function () {
      logLead(lead("modal-direto"));
      window.open(waLink(MSG_GENERICO), "_blank", "noopener");
      closeModal();
    });
    w.querySelector(".vb-modal-close").addEventListener("click", closeModal);
    w.addEventListener("mousedown", function (e) { if (e.target === w) closeModal(); });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && modal && !modal.hasAttribute("hidden")) closeModal();
    });

    refresh();
  }

  function openModal(pre) {
    if (!modal) buildModal();
    if (pre) {
      for (var i = 0; i < els.exame.options.length; i++) {
        if (els.exame.options[i].value === pre) {
          els.exame.value = pre;
          els.exame.dispatchEvent(new Event("change", { bubbles: true }));
          break;
        }
      }
    }
    modal.removeAttribute("hidden");
    document.body.classList.add("vb-modal-open");
    requestAnimationFrame(function () { modal.classList.add("open"); });
    setTimeout(function () { try { els.exame.focus(); } catch (e) {} }, 60);
  }

  function closeModal() {
    if (!modal) return;
    modal.classList.remove("open");
    document.body.classList.remove("vb-modal-open");
    setTimeout(function () { modal.setAttribute("hidden", ""); }, 250);
  }

  /* -------------------- Liga os CTAs de agendamento -------------------- */
  /* Delegação: pega qualquer <a> de WhatsApp, inclusive os injetados por
     outros scripts (ex.: card "Fale no WhatsApp" do vb-enhance.js) e o
     botão flutuante (que é só ícone, sem texto).
     Vai DIRETO (não abre modal) só quando:
       - o link tem a classe .wa-direct (número de contato, rodapé, etc.);
       - o link está DENTRO do próprio modal. */
  document.addEventListener("click", function (e) {
    var node = e.target;
    if (node && node.nodeType === 3) node = node.parentNode;      // text node → elemento
    var a = node && node.closest ? node.closest('a[href*="wa.me/"]') : null;
    if (!a) return;
    if (a.classList.contains("wa-direct")) return;
    if (a.closest(".vb-modal-overlay")) return;
    e.preventDefault();
    openModal(a.getAttribute("data-exame"));
  }, false);
})();
