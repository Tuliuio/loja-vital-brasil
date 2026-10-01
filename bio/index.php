<?php
/**
 * /bio/ — Link na bio (Instagram): contato, como chegar e combo em destaque.
 * Dados de contato vêm de loja/config.php e o combo de loja/data/combos.php,
 * então preço/exames se atualizam sozinhos quando a loja muda.
 */
require_once __DIR__ . '/../loja/includes/functions.php';

$LAUDOS_URL = 'http://186.208.144.36:81/laudos/';
$INSTAGRAM  = 'https://www.instagram.com/vitalbrasil/'; // CONFIRMAR @ oficial

$combo    = combo_por_slug('rotina-vital-mulher');
$endereco = config('endereco');                       // Rua General Osório, 968 - Pelotas/RS
$destino  = rawurlencode('Laboratório Vital Brasil, ' . $endereco);
$destTxt  = rawurlencode($endereco);
$tel      = preg_replace('/\D/', '', config('telefone'));

$maps  = 'https://www.google.com/maps/dir/?api=1&destination=' . $destino;
$waze  = 'https://waze.com/ul?q=' . $destTxt . '&navigate=yes';
$apple = 'https://maps.apple.com/?daddr=' . $destTxt;
$uber  = 'https://m.uber.com/ul/?action=setPickup&pickup=my_location&dropoff%5Bnickname%5D='
       . rawurlencode('Laboratório Vital Brasil') . '&dropoff%5Bformatted_address%5D=' . $destTxt;
$embed = 'https://maps.google.com/maps?q=' . rawurlencode(str_replace(' - ', ', ', $endereco) . ', Brasil') . '&z=17&output=embed';

$wppIcon = '<svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3C9.4 3 4 8.4 4 15c0 2.1.6 4.1 1.6 5.9L4 29l8.3-1.6c1.7.9 3.6 1.4 5.7 1.4 6.6 0 12-5.4 12-12S22.6 3 16 3zm0 21.8c-1.8 0-3.5-.5-5-1.4l-.4-.2-4.9 1 1-4.8-.2-.4c-1-1.6-1.5-3.4-1.5-5.3C5 9.5 9.9 4.9 16 4.9S27 9.5 27 15 22.1 24.8 16 24.8zm5.6-7.3c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2-.8 1-.9 1.2-.3.2-.6.1c-1.8-.9-3-1.6-4.2-3.6-.3-.5.3-.5.8-1.6.1-.2 0-.4 0-.5s-.7-1.7-1-2.3c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.3 5.2 4.6 2.9 1.2 2.9.8 3.5.8.5 0 1.8-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.2-.6-.4z"/></svg>';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-5FTMHJM8');</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratório Vital Brasil | Links</title>
    <meta name="description" content="WhatsApp, endereço, como chegar, resultados de exames e combos do Laboratório Vital Brasil em Pelotas/RS.">
    <meta name="theme-color" content="#2a3c72">
    <meta property="og:title" content="Laboratório Vital Brasil">
    <meta property="og:description" content="Agende pelo WhatsApp, veja como chegar e conheça a Rotina Vital Mulher.">
    <meta property="og:image" content="/loja/<?= e($combo['imagem']) ?>">
    <link rel="icon" href="/wp-content/uploads/2025/01/cropped-Ativo-6VITAL-FAVICON-32x32.png" sizes="32x32">
    <link rel="icon" href="/wp-content/uploads/2025/01/cropped-Ativo-6VITAL-FAVICON-192x192.png" sizes="192x192">
    <link rel="apple-touch-icon" href="/wp-content/uploads/2025/01/cropped-Ativo-6VITAL-FAVICON-180x180.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Mulish:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/vb-bio.css?v=2">
</head>
<body>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FTMHJM8" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<main class="bio">
  <!-- ===== Perfil ===== -->
  <header class="bio-top">
    <a href="/" class="bio-logo" data-bio="site_logo" aria-label="Laboratório Vital Brasil — site">
      <img src="/wp-content/uploads/2024/04/Marca_VitalBrasil_1-1024x165.png" alt="Laboratório Vital Brasil" width="240" height="39">
    </a>
    <p class="bio-tag">Análises clínicas em Pelotas/RS</p>
    <p class="bio-status" id="bio-status" hidden><span class="bio-dot"></span><span class="bio-status-txt"></span></p>
  </header>

  <!-- ===== Ação principal ===== -->
  <a class="bio-btn bio-btn--wpp" data-bio="whatsapp" data-ev="whatsapp_click"
     href="<?= e(whatsapp_link('Olá! Vim pelo Instagram e gostaria de agendar meus exames.')) ?>" target="_blank" rel="noopener">
    <?= $wppIcon ?>
    <span>Agendar pelo WhatsApp<small><?= e(config('telefone')) ?></small></span>
  </a>

  <!-- ===== Destaque: Rotina Vital Mulher ===== -->
  <?php if ($combo): ?>
  <section class="bio-combo" aria-labelledby="combo-title">
    <div class="bio-combo-img">
      <img src="/loja/<?= e($combo['imagem']) ?>" alt="<?= e($combo['nome']) ?>" width="880" height="664">
      <span class="bio-combo-badge">Combo em destaque</span>
    </div>
    <div class="bio-combo-body">
      <p class="bio-combo-cat"><?= e($combo['categoria']) ?></p>
      <h2 id="combo-title"><?= e($combo['nome']) ?></h2>
      <p class="bio-combo-sum"><?= e($combo['resumo']) ?></p>

      <div class="bio-combo-price">
        <span class="bio-combo-n"><strong><?= count($combo['exames']) ?></strong> exames</span>
        <span class="bio-combo-val"><small>por</small> <?= e(preco_fmt($combo['preco'])) ?></span>
      </div>

      <details class="bio-combo-list">
        <summary>Ver os <?= count($combo['exames']) ?> exames inclusos</summary>
        <ul>
          <?php foreach ($combo['exames'] as $ex): ?><li><?= e($ex) ?></li><?php endforeach; ?>
        </ul>
        <?php foreach ($combo['adicionais'] as $ad): ?><p class="bio-combo-add">+ Opcional: <?= e($ad) ?></p><?php endforeach; ?>
      </details>

      <div class="bio-combo-ctas">
        <a class="bio-btn bio-btn--wpp bio-btn--sm" data-bio="combo_whatsapp" data-ev="whatsapp_click"
           href="<?= e(whatsapp_link('Olá! Vim pelo Instagram e quero agendar a ' . $combo['nome'] . ' (' . preco_fmt($combo['preco']) . ').')) ?>" target="_blank" rel="noopener">
          <?= $wppIcon ?><span>Quero agendar</span>
        </a>
        <a class="bio-btn bio-btn--ghost bio-btn--sm" data-bio="combo_loja"
           href="/loja/combo.php?slug=<?= e($combo['slug']) ?>">Comprar online</a>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ===== Links ===== -->
  <nav class="bio-links" aria-label="Links">
    <a class="bio-link" data-bio="resultados" data-ev="resultados_click" href="/resultados/">
      <span class="bio-ico">📄</span><span>Resultados de exames</span><i>→</i>
    </a>
    <a class="bio-link" data-bio="loja" href="/loja/">
      <span class="bio-ico">🧪</span><span>Todos os combos de exames</span><i>→</i>
    </a>
    <a class="bio-link" data-bio="telefone" data-ev="phone_click" href="tel:+55<?= e($tel) ?>">
      <span class="bio-ico">📞</span><span>Ligar <?= e(config('telefone')) ?></span><i>→</i>
    </a>
    <a class="bio-link" data-bio="site" href="/">
      <span class="bio-ico">🌐</span><span>Conheça o laboratório</span><i>→</i>
    </a>
    <a class="bio-link" data-bio="instagram" href="<?= e($INSTAGRAM) ?>" target="_blank" rel="noopener">
      <span class="bio-ico">📸</span><span>Instagram</span><i>→</i>
    </a>
  </nav>

  <!-- ===== Como chegar ===== -->
  <section class="bio-local" aria-labelledby="local-title">
    <h2 id="local-title">Como chegar</h2>
    <p class="bio-addr">
      <strong><?= e($endereco) ?></strong>
      <button type="button" class="bio-copy" id="bio-copy" data-addr="<?= e($endereco) ?>">Copiar</button>
    </p>
    <p class="bio-hours">🕒 <?= e(config('horario')) ?></p>

    <div class="bio-map">
      <iframe src="<?= e($embed) ?>" title="Mapa: <?= e($endereco) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <div class="bio-nav">
      <a data-bio="rota_google_maps" data-ev="rota_click" href="<?= e($maps) ?>" target="_blank" rel="noopener">
        <img src="https://www.google.com/images/branding/product/2x/maps_96in128dp.png" alt="" width="24" height="24">Google Maps
      </a>
      <a data-bio="rota_waze" data-ev="rota_click" href="<?= e($waze) ?>" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="11" r="8" fill="#33ccff"/><circle cx="9.3" cy="9.7" r="1.1" fill="#1d1d1b"/><circle cx="14.7" cy="9.7" r="1.1" fill="#1d1d1b"/><path d="M8.8 12.8c1.6 1.8 4.8 1.8 6.4 0" stroke="#1d1d1b" stroke-width="1.2" fill="none" stroke-linecap="round"/><circle cx="8" cy="20" r="1.8" fill="#1d1d1b"/><circle cx="16" cy="20" r="1.8" fill="#1d1d1b"/></svg>Waze
      </a>
      <a data-bio="rota_apple_maps" data-ev="rota_click" href="<?= e($apple) ?>" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M16.4 12.6c0-2.4 2-3.5 2-3.6-1.1-1.6-2.8-1.8-3.4-1.8-1.4-.1-2.8.9-3.5.9s-1.9-.8-3.1-.8c-1.6 0-3 .9-3.8 2.3-1.6 2.8-.4 7 1.2 9.2.8 1.1 1.7 2.4 2.9 2.3 1.2 0 1.6-.7 3-.7s1.8.7 3 .7c1.3 0 2.1-1.1 2.8-2.2.9-1.3 1.3-2.5 1.3-2.6 0 0-2.4-.9-2.4-3.7zM14.1 5.6c.6-.8 1.1-1.8 1-2.9-.9 0-2.1.6-2.7 1.4-.6.7-1.1 1.8-1 2.8 1 .1 2.1-.5 2.7-1.3z"/></svg>Apple Maps
      </a>
      <a data-bio="rota_uber" data-ev="rota_click" href="<?= e($uber) ?>" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" aria-hidden="true"><rect width="24" height="24" rx="5" fill="#000"/><text x="12" y="15.6" text-anchor="middle" font-family="Arial,sans-serif" font-size="8" font-weight="700" fill="#fff">Uber</text></svg>Uber
      </a>
    </div>
  </section>

  <footer class="bio-foot">
    <a href="/" class="bio-foot-logo"><img src="/wp-content/uploads/2024/04/Marca_VitalBrasil_1-1024x165.png" alt="Laboratório Vital Brasil" width="180" height="29" loading="lazy"></a>
  </footer>
</main>

<script>
(function () {
  window.dataLayer = window.dataLayer || [];

  // Rastreamento: todo clique em [data-bio] vira bio_click; alguns também disparam
  // o evento padrão do site (whatsapp_click, phone_click, resultados_click) p/ as tags existentes.
  document.addEventListener('click', function (ev) {
    var a = ev.target.closest('[data-bio]');
    if (!a) return;
    dataLayer.push({ event: 'bio_click', bio_link: a.getAttribute('data-bio'), link_url: a.href || '' });
    var std = a.getAttribute('data-ev');
    if (std) dataLayer.push({ event: std, link_origem: 'bio', link_url: a.href || '' });
  });

  // Copiar endereço
  var btn = document.getElementById('bio-copy');
  if (btn && navigator.clipboard) {
    btn.addEventListener('click', function () {
      navigator.clipboard.writeText(btn.getAttribute('data-addr')).then(function () {
        btn.textContent = 'Copiado ✓';
        setTimeout(function () { btn.textContent = 'Copiar'; }, 1800);
      });
    });
  } else if (btn) { btn.hidden = true; }

  // Aberto agora? Seg a Sex, 7h–11h30 e 13h30–17h30 (horário de Brasília)
  try {
    var p = new Intl.DateTimeFormat('en-US', { timeZone: 'America/Sao_Paulo', weekday: 'short', hour: 'numeric', minute: 'numeric', hour12: false })
      .formatToParts(new Date()).reduce(function (o, x) { o[x.type] = x.value; return o; }, {});
    var dia = p.weekday, min = (parseInt(p.hour, 10) % 24) * 60 + parseInt(p.minute, 10);
    var util = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'].indexOf(dia) > -1;
    var aberto = util && ((min >= 420 && min < 690) || (min >= 810 && min < 1050));
    var txt = aberto ? 'Aberto agora'
      : (util && min >= 690 && min < 810) ? 'Intervalo de almoço · volta às 13h30'
      : 'Fechado agora · chame no WhatsApp';
    var el = document.getElementById('bio-status');
    el.querySelector('.bio-status-txt').textContent = txt;
    el.classList.toggle('is-open', aberto);
    el.hidden = false;
  } catch (e) {}
})();
</script>
</body>
</html>
