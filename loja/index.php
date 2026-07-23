<?php
require_once __DIR__ . '/includes/functions.php';

$page_title       = 'Combos e Rotinas de Exames';
$page_description = config('site_slogan');

$lista     = combos();
$destaques = array_filter($lista, fn($c) => !empty($c['destaque']));

require __DIR__ . '/includes/header.php';
?>

<section id="combos" class="section">
    <div class="container">
        <h2 class="section-title">Nossos combos</h2>
        <p class="section-sub">Escolha o pacote ideal e finalize pelo WhatsApp.</p>

        <div class="grid-combos">
            <?php foreach ($lista as $c): ?>
                <article class="card-combo">
                    <a href="combo.php?slug=<?= e($c['slug']) ?>" class="card-img">
                        <img src="<?= e($c['imagem']) ?>" alt="<?= e($c['nome']) ?>" loading="lazy"
                             onerror="this.style.display='none';this.parentElement.classList.add('no-img');">
                        <?php if (!empty($c['destaque'])): ?>
                            <span class="badge">Destaque</span>
                        <?php endif; ?>
                    </a>
                    <div class="card-body">
                        <span class="card-cat"><?= e($c['categoria']) ?></span>
                        <h3><?= e($c['nome']) ?></h3>
                        <p class="card-resumo"><?= e($c['resumo']) ?></p>
                        <div class="card-meta">
                            <span class="card-exames"><?= count($c['exames']) ?> exames</span>
                            <span class="card-preco"><?= e(preco_fmt($c['preco'])) ?></span>
                        </div>
                        <a href="combo.php?slug=<?= e($c['slug']) ?>" class="btn btn-primary btn-block">Ver detalhes</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
