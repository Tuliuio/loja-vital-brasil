<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/asaas.php';
session_start();

$slug  = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'])) : '';
$combo = $slug ? combo_por_slug($slug) : null;

// ─────────────────────────────────────────────────────────────────────────
// CHECKOUT COM PAGAMENTO DESATIVADO.
// A venda agora é finalizada no WhatsApp (carrinho — assets/js/carrinho.js).
// Qualquer acesso direto a esta página é redirecionado para o combo/loja.
// O código antigo (ASAAS) foi mantido abaixo apenas para referência/rollback.
// ─────────────────────────────────────────────────────────────────────────
header('Location: ' . ($combo ? 'combo.php?slug=' . urlencode($slug) : 'index.php'));
exit;

// Combo inexistente ou "sob consulta" não entram no checkout.
if (!$combo || empty($combo['preco']) || $combo['preco'] <= 0) {
    header('Location: ' . ($combo ? 'combo.php?slug=' . urlencode($slug) : 'index.php'));
    exit;
}

$erros = [];
$dados = ['nome' => '', 'cpf' => '', 'email' => '', 'telefone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $k => $_) {
        $dados[$k] = trim($_POST[$k] ?? '');
    }

    if (mb_strlen($dados['nome']) < 3)                      $erros[] = 'Informe seu nome completo.';
    if (strlen(preg_replace('/\D/', '', $dados['cpf'])) !== 11) $erros[] = 'Informe um CPF válido (11 dígitos).';
    if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) $erros[] = 'Informe um e-mail válido.';
    if (strlen(preg_replace('/\D/', '', $dados['telefone'])) < 10) $erros[] = 'Informe um telefone válido com DDD.';

    if (!$erros) {
        $cli = asaas_criar_cliente($dados);
        if (!$cli['ok']) {
            $erros[] = 'Não foi possível iniciar o pagamento: ' . $cli['erro'];
        } else {
            $cob = asaas_criar_cobranca(
                $cli['id'],
                (float) $combo['preco'],
                $combo['nome'] . ' - Vital Brasil',
                $combo['slug'],
                base_url() . '/confirmacao.php'
            );
            if (!$cob['ok'] || empty($cob['invoiceUrl'])) {
                $erros[] = 'Não foi possível gerar a cobrança: ' . ($cob['erro'] ?? 'tente novamente.');
            } else {
                // Guarda o pedido na sessão para validar no retorno (sem banco).
                $_SESSION['pedido'] = [
                    'payment_id' => $cob['id'],
                    'slug'       => $combo['slug'],
                    'combo'      => $combo['nome'],
                    'valor'      => preco_fmt($combo['preco']),
                    'nome'       => $dados['nome'],
                    'cpf'        => $dados['cpf'],
                    'email'      => $dados['email'],
                    'telefone'   => $dados['telefone'],
                ];
                header('Location: ' . $cob['invoiceUrl']);
                exit;
            }
        }
    }
}

$page_title = 'Checkout — ' . $combo['nome'];
require __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container checkout-grid">

        <div class="checkout-form-wrap">
            <h1>Finalizar compra</h1>
            <p class="checkout-sub">Preencha seus dados para gerar o pagamento (PIX, cartão ou boleto).</p>

            <?php if ($erros): ?>
                <div class="alert alert-erro">
                    <ul><?php foreach ($erros as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <form method="post" class="form" novalidate>
                <label>Nome completo
                    <input type="text" name="nome" value="<?= e($dados['nome']) ?>" required>
                </label>
                <label>CPF
                    <input type="text" name="cpf" value="<?= e($dados['cpf']) ?>" inputmode="numeric" placeholder="000.000.000-00" required>
                </label>
                <label>E-mail
                    <input type="email" name="email" value="<?= e($dados['email']) ?>" required>
                </label>
                <label>Telefone (WhatsApp)
                    <input type="text" name="telefone" value="<?= e($dados['telefone']) ?>" inputmode="numeric" placeholder="(53) 9 9999-9999" required>
                </label>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Ir para o pagamento</button>
                <p class="checkout-seguro">🔒 Pagamento processado com segurança pelo ASAAS.</p>
            </form>
        </div>

        <aside class="checkout-resumo">
            <h2>Resumo</h2>
            <div class="resumo-item">
                <span class="card-cat"><?= e($combo['categoria']) ?></span>
                <h3><?= e($combo['nome']) ?></h3>
                <p><?= count($combo['exames']) ?> exames</p>
            </div>
            <div class="resumo-total">
                <span>Total</span>
                <strong><?= e(preco_fmt($combo['preco'])) ?></strong>
            </div>
            <?php if (!empty($combo['preco_obs'])): ?>
                <p class="preco-obs"><?= e($combo['preco_obs']) ?></p>
            <?php endif; ?>
            <a href="combo.php?slug=<?= e($combo['slug']) ?>" class="voltar-link">← Voltar ao combo</a>
        </aside>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
