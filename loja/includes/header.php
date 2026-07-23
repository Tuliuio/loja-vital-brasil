<?php
/**
 * Cabeçalho compartilhado.
 * Antes de incluir, defina opcionalmente:
 *   $page_title       (string)
 *   $page_description (string)
 */
require_once __DIR__ . '/functions.php';

$titulo    = isset($page_title) ? $page_title . ' | ' . config('site_nome') : config('site_nome');
$descricao = $page_description ?? config('site_slogan');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?></title>
    <meta name="description" content="<?= e($descricao) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&family=Mulish:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Header + Footer unificados (mesmos do site institucional) -->
    <link rel="stylesheet" href="/assets/vb-header.css">
    <link rel="stylesheet" href="/assets/vb-footer.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --cor-primaria: <?= e(config('cor_primaria')) ?>;
            --cor-secundaria: <?= e(config('cor_secundaria')) ?>;
            --cor-escura: <?= e(config('cor_escura')) ?>;
        }
    </style>
</head>
<body>
<!-- ===== Header unificado (idêntico ao site; item "Loja" ativo) ===== -->
<header class="vb-header" id="vb-header">
  <div class="vb-header__inner">
    <a href="/" class="vb-header__logo" aria-label="Laboratório Vital Brasil — início">
      <img src="/wp-content/uploads/2024/03/logo-header-1.svg" alt="Laboratório Vital Brasil" width="200" height="46">
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
          <li><a href="/loja/" class="is-active" aria-current="page">Loja</a></li>
      </ul>
      <div class="vb-header__actions">
        <a href="http://186.208.144.36:81/laudos/" target="_blank" rel="noopener" class="vb-btn vb-btn--ghost">Resultados de exames</a>
        <a href="<?= e(whatsapp_link('Olá! Vim pela loja online e gostaria de mais informações.')) ?>" target="_blank" rel="noopener" class="vb-btn vb-btn--wpp"><svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3C9.4 3 4 8.4 4 15c0 2.1.6 4.1 1.6 5.9L4 29l8.3-1.6c1.7.9 3.6 1.4 5.7 1.4 6.6 0 12-5.4 12-12S22.6 3 16 3zm0 21.8c-1.8 0-3.5-.5-5-1.4l-.4-.2-4.9 1 1-4.8-.2-.4c-1-1.6-1.5-3.4-1.5-5.3C5 9.5 9.9 4.9 16 4.9S27 9.5 27 15 22.1 24.8 16 24.8zm5.6-7.3c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2-.8 1-.9 1.2-.3.2-.6.1c-1.8-.9-3-1.6-4.2-3.6-.3-.5.3-.5.8-1.6.1-.2 0-.4 0-.5s-.7-1.7-1-2.3c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.3 5.2 4.6 2.9 1.2 2.9.8 3.5.8.5 0 1.8-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.2-.6-.4z"/></svg>WhatsApp</a>
      </div>
    </nav>
  </div>
</header>
<script src="/assets/vb-header.js" defer></script>
<main>
