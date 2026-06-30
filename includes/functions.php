<?php
/**
 * Funções auxiliares compartilhadas.
 */

/** Carrega config uma única vez (com override opcional de config.local.php). */
function config(?string $chave = null) {
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require __DIR__ . '/../config.php';
        $local = __DIR__ . '/../config.local.php';
        if (is_file($local)) {
            $cfg = array_merge($cfg, require $local);
        }
    }
    if ($chave === null) {
        return $cfg;
    }
    return $cfg[$chave] ?? null;
}

/** Carrega o catálogo de combos. */
function combos(): array {
    static $lista = null;
    if ($lista === null) {
        $lista = require __DIR__ . '/../data/combos.php';
    }
    return $lista;
}

/** Busca um combo pelo slug. Retorna null se não existir. */
function combo_por_slug(string $slug): ?array {
    foreach (combos() as $c) {
        if ($c['slug'] === $slug) {
            return $c;
        }
    }
    return null;
}

/** Escapa texto para HTML. */
function e(?string $texto): string {
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/** Formata preço em reais ou "Sob consulta" quando 0. */
function preco_fmt($valor): string {
    if (!$valor || $valor <= 0) {
        return 'Sob consulta';
    }
    return 'R$ ' . number_format((float) $valor, 2, ',', '.');
}

/** Monta link de WhatsApp com mensagem pré-preenchida. */
function whatsapp_link(string $mensagem = ''): string {
    $num = preg_replace('/\D/', '', (string) config('whatsapp'));
    $url = 'https://wa.me/' . $num;
    if ($mensagem !== '') {
        $url .= '?text=' . rawurlencode($mensagem);
    }
    return $url;
}

/** URL base do site (para canonical / og / callback ASAAS). */
function base_url(): string {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
}

/**
 * Gera os dias e horários disponíveis para agendamento, conforme config['agenda'].
 * Sem controle de vagas (atendimento por ordem de chegada).
 * @return array<int, array{data:string, label:string, horarios:array<int,string>}>
 */
function agenda_slots(): array {
    $cfg = config('agenda');
    $tz  = new DateTimeZone('America/Sao_Paulo');
    $agora = new DateTimeImmutable('now', $tz);
    $minimo = $agora->modify('+' . (int) $cfg['antecedencia_horas'] . ' hours');

    // Pré-gera a grade de horários do expediente
    $grade = [];
    foreach ($cfg['periodos'] as [$ini, $fim]) {
        $t = DateTime::createFromFormat('H:i', $ini);
        $f = DateTime::createFromFormat('H:i', $fim);
        while ($t < $f) {
            $grade[] = $t->format('H:i');
            $t->modify('+' . (int) $cfg['intervalo_min'] . ' minutes');
        }
    }

    $meses = ['', 'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
    $semana = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sáb'];

    $resultado = [];
    for ($i = 0; $i <= (int) $cfg['dias_a_frente']; $i++) {
        $dia = $agora->modify("+$i days");
        $dow = (int) $dia->format('w');
        if (!in_array($dow, $cfg['dias_semana'], true)) {
            continue;
        }
        // Filtra horários que respeitam a antecedência mínima
        $horarios = [];
        foreach ($grade as $h) {
            $slot = DateTimeImmutable::createFromFormat('Y-m-d H:i', $dia->format('Y-m-d') . ' ' . $h, $tz);
            if ($slot >= $minimo) {
                $horarios[] = $h;
            }
        }
        if (!$horarios) {
            continue;
        }
        $resultado[] = [
            'data'     => $dia->format('Y-m-d'),
            'label'    => $semana[$dow] . ', ' . $dia->format('d') . ' ' . $meses[(int) $dia->format('n')],
            'horarios' => $horarios,
        ];
    }
    return $resultado;
}

/** Valida se uma data+hora escolhida é um slot legítimo. */
function agenda_valida(string $data, string $hora): bool {
    foreach (agenda_slots() as $dia) {
        if ($dia['data'] === $data && in_array($hora, $dia['horarios'], true)) {
            return true;
        }
    }
    return false;
}

/**
 * Notifica o laboratório sobre um novo agendamento (e-mail).
 * Retorna true se o e-mail foi aceito para envio.
 */
function notificar_laboratorio(array $info): bool {
    $para    = (string) config('notificacao_email');
    $assunto = 'Novo agendamento: ' . $info['combo'] . ' - ' . $info['nome'];
    $corpo   =
        "Novo agendamento confirmado pela loja online\n\n" .
        "Combo: {$info['combo']}\n" .
        "Valor: {$info['valor']}\n" .
        "Pagamento (ASAAS): {$info['payment_id']}\n\n" .
        "Cliente: {$info['nome']}\n" .
        "CPF: {$info['cpf']}\n" .
        "E-mail: {$info['email']}\n" .
        "Telefone: {$info['telefone']}\n\n" .
        "Data da coleta: {$info['data_label']}\n" .
        "Horário: {$info['hora']}\n";
    $headers =
        'From: ' . config('site_nome') . ' <' . $para . ">\r\n" .
        'Reply-To: ' . $info['email'] . "\r\n" .
        "Content-Type: text/plain; charset=UTF-8\r\n";

    // mail() pode falhar em ambiente local sem SMTP — não quebra o fluxo.
    return @mail($para, $assunto, $corpo, $headers);
}

/** Monta link de WhatsApp para o laboratório com os dados do agendamento (fallback). */
function whatsapp_lab_agendamento(array $info): string {
    $msg =
        "*Novo agendamento — loja online*%0A%0A" .
        "Combo: {$info['combo']}%0A" .
        "Cliente: {$info['nome']}%0A" .
        "Telefone: {$info['telefone']}%0A" .
        "Data: {$info['data_label']} às {$info['hora']}%0A" .
        "Pagamento: {$info['payment_id']}";
    return whatsapp_link('') . '?text=' . $msg;
}
