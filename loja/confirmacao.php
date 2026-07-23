<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/asaas.php';
session_start();

$pedido = $_SESSION['pedido'] ?? null;
// Aceita paymentId vindo na URL como fallback ao da sessão.
$paymentId = $pedido['payment_id'] ?? ($_GET['paymentId'] ?? '');

$status_pag = 'desconhecido';
if ($paymentId) {
    $c = asaas_consultar_cobranca($paymentId);
    if ($c['ok']) {
        $status_pag = $c['data']['status'] ?? 'desconhecido';
    }
}

$pago    = asaas_pago($status_pag);
$pendente = !$pago;

$page_title = $pago ? 'Compra confirmada' : 'Aguardando pagamento';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container conf-wrap">

    <?php if (!$pedido): ?>
        <div class="conf-box">
            <h1>Não encontramos seu pedido</h1>
            <p>Sua sessão pode ter expirado. Se você já pagou, fale com a gente que confirmamos manualmente.</p>
            <a href="index.php" class="btn btn-primary">Voltar à loja</a>
        </div>

    <?php elseif ($pendente): ?>
        <div class="conf-box">
            <span class="conf-icon pendente">⏳</span>
            <h1>Pagamento em processamento</h1>
            <p>Assim que o ASAAS confirmar o pagamento de <strong><?= e($pedido['combo']) ?></strong>,
               esta página libera o agendamento. Se você pagou via PIX, costuma levar poucos instantes.</p>
            <button onclick="location.reload()" class="btn btn-primary">Já paguei, atualizar</button>
        </div>

    <?php else: ?>
        <div class="conf-box conf-ok">
            <span class="conf-icon ok">✓</span>
            <h1>Compra confirmada!</h1>
            <p>Obrigado, <strong><?= e($pedido['nome']) ?></strong>. Seu pagamento de
               <strong><?= e($pedido['combo']) ?></strong> (<?= e($pedido['valor']) ?>) foi confirmado.</p>
        </div>

        <div class="agendar-box">
            <h2>Agende a coleta</h2>
            <p class="section-sub">Escolha o melhor dia e horário para comparecer ao laboratório.
               O atendimento é por ordem de chegada.</p>

            <?php $dias = agenda_slots(); ?>
            <?php if (!$dias): ?>
                <p>No momento não há horários disponíveis online. Fale conosco no WhatsApp para agendar.</p>
                <a href="<?= e(whatsapp_link('Olá! Já paguei meu combo e quero agendar a coleta.')) ?>"
                   class="btn btn-wpp btn-lg" target="_blank" rel="noopener">Agendar pelo WhatsApp</a>
            <?php else: ?>
                <form method="post" action="agendar.php" class="form-agenda" id="form-agenda">
                    <label class="campo-dia">Dia da coleta
                        <select name="data" id="select-dia" required>
                            <option value="">Selecione um dia…</option>
                            <?php foreach ($dias as $d): ?>
                                <option value="<?= e($d['data']) ?>"><?= e($d['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <div class="horarios-wrap">
                        <span class="horarios-titulo">Horário</span>
                        <div id="horarios" class="horarios-grid">
                            <p class="horarios-hint">Selecione um dia para ver os horários.</p>
                        </div>
                        <input type="hidden" name="hora" id="input-hora" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn-confirmar" disabled>
                        Confirmar presença
                    </button>
                </form>

                <script>
                    var SLOTS = <?= json_encode(array_column($dias, 'horarios', 'data'), JSON_UNESCAPED_UNICODE) ?>;
                    var selDia   = document.getElementById('select-dia');
                    var elHor    = document.getElementById('horarios');
                    var inputHora = document.getElementById('input-hora');
                    var btn      = document.getElementById('btn-confirmar');

                    selDia.addEventListener('change', function () {
                        inputHora.value = '';
                        btn.disabled = true;
                        var lista = SLOTS[this.value] || [];
                        if (!lista.length) {
                            elHor.innerHTML = '<p class="horarios-hint">Selecione um dia para ver os horários.</p>';
                            return;
                        }
                        elHor.innerHTML = '';
                        lista.forEach(function (h) {
                            var b = document.createElement('button');
                            b.type = 'button';
                            b.className = 'slot-btn';
                            b.textContent = h;
                            b.addEventListener('click', function () {
                                document.querySelectorAll('.slot-btn').forEach(function (x) { x.classList.remove('ativo'); });
                                b.classList.add('ativo');
                                inputHora.value = h;
                                btn.disabled = false;
                            });
                            elHor.appendChild(b);
                        });
                    });
                </script>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
