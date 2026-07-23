<?php
/**
 * /resultados/ — Portal de laudos (com aviso de retry) + vitrine dos combos.
 * Reaproveita o catálogo da loja (loja/data/combos.php) para se atualizar sozinho.
 */
require_once __DIR__ . '/../loja/includes/functions.php';

$LAUDOS_URL = 'http://186.208.144.36:81/laudos/';

// Destaques primeiro; completa até 4 com os demais combos.
$lista = combos();
$featured = array_values(array_filter($lista, fn($c) => !empty($c['destaque'])));
foreach ($lista as $c) {
    if (count($featured) >= 4) break;
    $jah = false;
    foreach ($featured as $f) { if ($f['slug'] === $c['slug']) { $jah = true; break; } }
    if (!$jah) $featured[] = $c;
}
$featured = array_slice($featured, 0, 4);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de exames | Laboratório Vital Brasil</title>
    <meta name="description" content="Acesse e baixe os resultados dos seus exames online e conheça os combos de rotinas de exames do Laboratório Vital Brasil.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Mulish:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/vb-header.css">
    <link rel="stylesheet" href="/assets/vb-footer.css">
    <link rel="stylesheet" href="/assets/vb-resultados.css">
</head>
<body>
<!-- ===== Header unificado ===== -->
<header class="vb-header" id="vb-header">
  <div class="vb-header__inner">
    <a href="/" class="vb-header__logo" aria-label="Laboratório Vital Brasil — início">
      <img src="/wp-content/uploads/2024/03/logo-header-1.svg" alt="Laboratório Vital Brasil" width="200" height="34">
    </a>
    <button class="vb-header__burger" type="button" aria-label="Abrir menu" aria-expanded="false" aria-controls="vb-nav">
      <span></span><span></span><span></span>
    </button>
    <nav class="vb-header__nav" id="vb-nav" aria-label="Navegação principal">
      <ul class="vb-header__menu">
          <li><a href="/">Início</a></li>
          <li><a href="/#servicos">Serviços</a></li>
          <li><a href="/#quemsomos">Quem somos</a></li>
          <li><a href="/#convenios">Convênios</a></li>
          <li><a href="/#contato">Contato</a></li>
          <li><a href="/loja/">Loja</a></li>
      </ul>
      <div class="vb-header__actions">
        <a href="/resultados/" class="vb-btn vb-btn--ghost is-active" aria-current="page">Resultados de exames</a>
        <a href="<?= e(whatsapp_link('Olá! Gostaria de mais informações.')) ?>" target="_blank" rel="noopener" class="vb-btn vb-btn--wpp"><svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3C9.4 3 4 8.4 4 15c0 2.1.6 4.1 1.6 5.9L4 29l8.3-1.6c1.7.9 3.6 1.4 5.7 1.4 6.6 0 12-5.4 12-12S22.6 3 16 3zm0 21.8c-1.8 0-3.5-.5-5-1.4l-.4-.2-4.9 1 1-4.8-.2-.4c-1-1.6-1.5-3.4-1.5-5.3C5 9.5 9.9 4.9 16 4.9S27 9.5 27 15 22.1 24.8 16 24.8zm5.6-7.3c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2-.8 1-.9 1.2-.3.2-.6.1c-1.8-.9-3-1.6-4.2-3.6-.3-.5.3-.5.8-1.6.1-.2 0-.4 0-.5s-.7-1.7-1-2.3c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.3 5.2 4.6 2.9 1.2 2.9.8 3.5.8.5 0 1.8-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.2-.6-.4z"/></svg>WhatsApp</a>
      </div>
    </nav>
  </div>
</header>

<main class="rz">
  <section class="rz-hero">
    <div class="rz-wrap">
      <span class="rz-badge">🔒 Portal de laudos online</span>
      <h1>Resultados dos seus exames</h1>
      <p class="rz-lead">Acesse e baixe seus laudos com o login e a senha fornecidos no momento da coleta.</p>
      <a class="rz-cta" href="<?= e($LAUDOS_URL) ?>" target="_blank" rel="noopener">Acessar meus resultados →</a>
      <div class="rz-note">
        <strong>Não abriu de primeira?</strong> É só clicar novamente. O sistema de laudos às vezes falha na primeira tentativa e abre normalmente na segunda — é uma instabilidade momentânea do portal, não do seu computador. Se persistir, fale com a gente no WhatsApp.
      </div>
    </div>
  </section>

  <section class="rz-combos">
    <div class="rz-wrap">
      <p class="rz-eyebrow">Enquanto você está aqui</p>
      <h2>Conheça também nossos combos de exames</h2>
      <p class="rz-sub">Rotinas completas com ótimo custo-benefício, agendamento fácil e compra online, direto pelo site.</p>

      <div class="rz-grid">
        <?php foreach ($featured as $c): ?>
        <a class="rz-card" href="/loja/combo.php?slug=<?= e($c['slug']) ?>">
          <span class="rz-card-img">
            <img src="/loja/<?= e($c['imagem']) ?>" alt="<?= e($c['nome']) ?>" loading="lazy"
                 onerror="this.style.display='none'">
          </span>
          <span class="rz-card-body">
            <span class="rz-card-cat"><?= e($c['categoria']) ?></span>
            <span class="rz-card-name"><?= e($c['nome']) ?></span>
            <span class="rz-card-meta">
              <span><?= count($c['exames']) ?> exames</span>
              <strong><?= e(preco_fmt($c['preco'])) ?></strong>
            </span>
          </span>
        </a>
        <?php endforeach; ?>
      </div>

      <div class="rz-actions">
        <a class="rz-btn-primary" href="/loja/">Ver todos os combos e comprar online →</a>
        <a class="rz-btn-wpp" href="<?= e(whatsapp_link('Olá! Gostaria de saber mais sobre os combos de exames.')) ?>" target="_blank" rel="noopener">Falar no WhatsApp</a>
      </div>
    </div>
  </section>
</main>

<!-- ===== Footer unificado ===== -->
<footer class="vb-footer">
  <div class="vb-footer__inner">
    <p class="vb-footer__copy">Todos os direitos reservados &copy; 2025 Por <a href="https://somosmira.com.br/" target="_blank" rel="noopener">mira comunica&ccedil;&atilde;o digital</a><span class="vb-footer__tuliu">Digital Infrastructure by <a href="https://tuliu.io" target="_blank" rel="noopener">Tuliu</a></span></p>
    <a href="/" class="vb-footer__logo" aria-label="Laborat&oacute;rio Vital Brasil">
      <img src="/wp-content/uploads/2024/04/Marca_VitalBrasil_1-1024x165.png" alt="Laborat&oacute;rio Vital Brasil" width="260" height="42">
    </a>
  </div>
</footer>
<script src="/assets/vb-header.js" defer></script>
</body>
</html>
