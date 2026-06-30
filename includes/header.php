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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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
<header class="site-header">
    <div class="container header-inner">
        <a href="index.php" class="logo">
            <img src="<?= e(config('logo_claro')) ?>" alt="<?= e(config('site_nome')) ?>"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='inline';">
            <span class="logo-text logo-text-claro" style="display:none;">Vital<strong>Brasil</strong></span>
        </a>
        <nav class="site-nav">
            <a href="index.php">Combos</a>
            <a href="<?= e(config('site_url')) ?>" target="_blank" rel="noopener">Site oficial</a>
            <a href="<?= e(whatsapp_link('Olá! Vim pela loja online e gostaria de mais informações.')) ?>"
               class="btn btn-wpp" target="_blank" rel="noopener">WhatsApp</a>
        </nav>
    </div>
</header>
<main>
