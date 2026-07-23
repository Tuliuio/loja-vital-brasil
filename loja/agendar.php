<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/asaas.php';
session_start();

$pedido = $_SESSION['pedido'] ?? null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$pedido) {
    header('Location: index.php');
    exit;
}

// Revalida o pagamento antes de aceitar o agendamento.
$c = asaas_consultar_cobranca($pedido['payment_id']);
if (!$c['ok'] || !asaas_pago($c['data']['status'] ?? '')) {
    header('Location: confirmacao.php');
    exit;
}

$data = trim($_POST['data'] ?? '');
$hora = trim($_POST['hora'] ?? '');

$erro = '';
if (!agenda_valida($data, $hora)) {
    $erro = 'Horário inválido ou indisponível. Volte e escolha novamente.';
}

$enviado = false;
$wpp_lab = '';
if (!$erro) {
    // Label amigável da data
    $tz = new DateTimeZone('America/Sao_Paulo');
    $dt = DateTimeImmutable::createFromFormat('Y-m-d', $data, $tz);
    $semana = ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'];
    $data_label = $semana[(int) $dt->format('w')] . ', ' . $dt->format('d/m/Y');

    $info = [
        'combo'      => $pedido['combo'],
        'valor'      => $pedido['valor'],
        'payment_id' => $pedido['payment_id'],
        'nome'       => $pedido['nome'],
        'cpf'        => $pedido['cpf'],
        'email'      => $pedido['email'],
        'telefone'   => $pedido['telefone'],
        'data_label' => $data_label,
        'hora'       => $hora,
    ];

    $enviado = notificar_laboratorio($info);
    $wpp_lab = whatsapp_lab_agendamento($info);

    // Pedido concluído — encerra a sessão para evitar reagendamento duplicado.
    unset($_SESSION['pedido']);
}

$page_title = $erro ? 'Erro no agendamento' : 'Agendamento confirmado';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container conf-wrap">
        <?php if ($erro): ?>
            <div class="conf-box">
                <h1>Ops…</h1>
                <p><?= e($erro) ?></p>
                <a href="confirmacao.php" class="btn btn-primary">Voltar ao agendamento</a>
            </div>
        <?php else: ?>
            <div class="conf-box conf-ok">
                <span class="conf-icon ok">✓</span>
                <h1>Agendamento confirmado!</h1>
                <p>Sua coleta de <strong><?= e($info['combo']) ?></strong> está marcada para:</p>
                <p class="agenda-final"><?= e($info['data_label']) ?> às <strong><?= e($info['hora']) ?></strong></p>
                <p class="agenda-end">📍 <?= e(config('endereco')) ?></p>
                <p class="agenda-obs">Atendimento por ordem de chegada. Leve um documento com foto.
                   <?php if (!empty($pedido['email'])): ?>Enviamos os detalhes para <?= e($pedido['email']) ?>.<?php endif; ?></p>

                <?php if (!$enviado): ?>
                    <p class="agenda-obs alerta">Não foi possível enviar a confirmação automática ao laboratório.
                       Por favor, confirme pelo WhatsApp:</p>
                    <a href="<?= e($wpp_lab) ?>" class="btn btn-wpp btn-lg" target="_blank" rel="noopener">
                        Confirmar pelo WhatsApp
                    </a>
                <?php endif; ?>

                <a href="index.php" class="btn btn-outline">Voltar à loja</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
