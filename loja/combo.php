<?php
require_once __DIR__ . '/includes/functions.php';

$slug  = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'])) : '';
$combo = $slug ? combo_por_slug($slug) : null;

// Combo não encontrado -> 404 amigável
if (!$combo) {
    http_response_code(404);
    $page_title = 'Combo não encontrado';
    require __DIR__ . '/includes/header.php';
    echo '<section class="section container"><h1>Combo não encontrado</h1>'
       . '<p>O combo que você procura não existe ou foi removido.</p>'
       . '<a href="index.php" class="btn btn-primary">Voltar para os combos</a></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$page_title       = $combo['nome'];
$page_description = $combo['resumo'];

$msg_wpp = "Olá! Tenho interesse no combo *{$combo['nome']}*. Pode me passar mais informações e o valor?";

require __DIR__ . '/includes/header.php';
?>

<nav class="breadcrumb container">
    <a href="index.php">Combos</a> <span>/</span> <?= e($combo['nome']) ?>
</nav>

<section class="section produto">
    <div class="container produto-grid">

        <div class="produto-img">
            <img src="<?= e($combo['imagem']) ?>" alt="<?= e($combo['nome']) ?>"
                 onerror="this.parentElement.classList.add('no-img'); this.remove();">
        </div>

        <div class="produto-info">
            <span class="card-cat"><?= e($combo['categoria']) ?></span>
            <h1><?= e($combo['nome']) ?></h1>

            <p class="produto-desc"><?= $combo['descricao'] /* HTML simples permitido */ ?></p>

            <div class="produto-preco-box">
                <?php if (!empty($combo['preco_de']) && $combo['preco_de'] > 0): ?>
                    <span class="preco-de"><?= e(preco_fmt($combo['preco_de'])) ?></span>
                <?php endif; ?>
                <span class="preco-atual"><?= e(preco_fmt($combo['preco'])) ?></span>
            </div>
            <?php if (!empty($combo['preco_obs'])): ?>
                <p class="preco-obs"><?= e($combo['preco_obs']) ?></p>
            <?php endif; ?>

            <div class="produto-cta">
                <?php if (!empty($combo['preco']) && $combo['preco'] > 0): ?>
                    <button type="button" class="btn btn-primary btn-lg js-add-carrinho"
                            data-slug="<?= e($combo['slug']) ?>"
                            data-nome="<?= e($combo['nome']) ?>"
                            data-preco="<?= number_format((float) $combo['preco'], 2, '.', '') ?>">
                        <span class="js-add-label">Adicionar ao carrinho</span>
                    </button>
                    <a href="<?= e(whatsapp_link($msg_wpp)) ?>" class="btn btn-outline" target="_blank" rel="noopener">
                        Tirar dúvidas
                    </a>
                <?php else: ?>
                    <a href="<?= e(whatsapp_link($msg_wpp)) ?>" class="btn btn-wpp btn-lg" target="_blank" rel="noopener">
                        Consultar valor no WhatsApp
                    </a>
                <?php endif; ?>
            </div>

            <ul class="produto-features">
                <?php foreach ($combo['beneficios'] as $b): ?>
                    <li><?= e($b) ?></li>
                <?php endforeach; ?>
            </ul>

            <?php if (!empty($combo['adicionais'])): ?>
                <div class="produto-adicionais">
                    <strong>Opcionais:</strong>
                    <ul>
                        <?php foreach ($combo['adicionais'] as $ad): ?>
                            <li><?= e($ad) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($combo['jejum'])): ?>
                <p class="produto-jejum"><strong>Jejum:</strong> <?= e($combo['jejum']) ?></p>
            <?php endif; ?>
            <?php if (!empty($combo['indicacao'])): ?>
                <p class="produto-indicacao"><strong>Indicação:</strong> <?= e($combo['indicacao']) ?></p>
            <?php endif; ?>

            <div class="produto-infobox">
                <span>🕖 <?= e(config('horario')) ?></span>
                <span>💳 <?= e(config('pagamento')) ?></span>
                <span>📍 <?= e(config('endereco')) ?></span>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <h2 class="section-title">Exames inclusos</h2>
        <?php if (!empty($combo['exames'])): ?>
            <p class="section-sub"><?= count($combo['exames']) ?> exames neste pacote.</p>
            <ul class="lista-exames">
                <?php foreach ($combo['exames'] as $ex): ?>
                    <li><?= e($ex) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="section-sub">A lista de exames deste pacote é definida conforme avaliação. Fale conosco no WhatsApp para os detalhes.</p>
        <?php endif; ?>

        <div class="cta-final">
            <a href="<?= e(whatsapp_link($msg_wpp)) ?>" class="btn btn-wpp btn-lg" target="_blank" rel="noopener">
                Quero este combo
            </a>
            <a href="index.php" class="btn btn-outline btn-lg">Ver outros combos</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
