<?php
/**
 * Cliente mínimo da API ASAAS.
 * Usa a chave definida em config.local.php (asaas_token) e o ambiente (asaas_env).
 * Toda chamada é server-side — a chave NUNCA vai para o navegador.
 */
require_once __DIR__ . '/functions.php';

/** URL base conforme ambiente. */
function asaas_base_url(): string {
    return config('asaas_env') === 'producao'
        ? 'https://api.asaas.com/v3'
        : 'https://api-sandbox.asaas.com/v3';
}

/**
 * Requisição genérica à API ASAAS.
 * @return array{ok:bool, status:int, data:array}
 */
function asaas_request(string $metodo, string $endpoint, ?array $payload = null): array {
    $token = (string) config('asaas_token');
    if ($token === '') {
        return ['ok' => false, 'status' => 0, 'data' => ['erro' => 'Chave ASAAS não configurada (config.local.php).']];
    }

    $ch = curl_init(asaas_base_url() . $endpoint);
    $headers = [
        'access_token: ' . $token,
        'Content-Type: application/json',
        'User-Agent: LojaVitalBrasil/1.0',
    ];
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => $metodo,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 30,
    ]);
    if ($payload !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    }

    $resp   = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err    = curl_error($ch);
    curl_close($ch);

    if ($resp === false) {
        return ['ok' => false, 'status' => 0, 'data' => ['erro' => 'Falha de conexão: ' . $err]];
    }

    $data = json_decode($resp, true) ?: [];
    return ['ok' => $status >= 200 && $status < 300, 'status' => $status, 'data' => $data];
}

/**
 * Cria (ou reaproveita) um cliente no ASAAS e retorna o ID.
 * @return array{ok:bool, id?:string, erro?:string}
 */
function asaas_criar_cliente(array $dados): array {
    $payload = [
        'name'        => $dados['nome'],
        'cpfCnpj'     => preg_replace('/\D/', '', $dados['cpf']),
        'email'       => $dados['email'],
        'mobilePhone' => preg_replace('/\D/', '', $dados['telefone']),
    ];
    $r = asaas_request('POST', '/customers', $payload);
    if ($r['ok'] && !empty($r['data']['id'])) {
        return ['ok' => true, 'id' => $r['data']['id']];
    }
    return ['ok' => false, 'erro' => asaas_msg_erro($r['data'])];
}

/**
 * Cria uma cobrança (billingType UNDEFINED → cliente escolhe PIX/cartão/boleto).
 * @return array{ok:bool, id?:string, invoiceUrl?:string, erro?:string}
 */
function asaas_criar_cobranca(string $clienteId, float $valor, string $descricao, string $externalRef, string $successUrl): array {
    $payload = [
        'customer'          => $clienteId,
        'billingType'       => 'UNDEFINED',
        'value'             => round($valor, 2),
        'dueDate'           => date('Y-m-d', strtotime('+' . (int) config('asaas_due_dias') . ' days')),
        'description'       => $descricao,
        'externalReference' => $externalRef,
        'callback'          => [
            'successUrl'   => $successUrl,
            'autoRedirect' => true,
        ],
    ];
    $r = asaas_request('POST', '/payments', $payload);
    if ($r['ok'] && !empty($r['data']['id'])) {
        return [
            'ok'         => true,
            'id'         => $r['data']['id'],
            'invoiceUrl' => $r['data']['invoiceUrl'] ?? '',
        ];
    }
    return ['ok' => false, 'erro' => asaas_msg_erro($r['data'])];
}

/** Consulta uma cobrança pelo ID. */
function asaas_consultar_cobranca(string $paymentId): array {
    $r = asaas_request('GET', '/payments/' . rawurlencode($paymentId));
    if ($r['ok']) {
        return ['ok' => true, 'data' => $r['data']];
    }
    return ['ok' => false, 'erro' => asaas_msg_erro($r['data'])];
}

/** Status que indicam pagamento efetivado. */
function asaas_pago(string $status): bool {
    return in_array($status, ['RECEIVED', 'CONFIRMED', 'RECEIVED_IN_CASH'], true);
}

/** Extrai mensagem de erro legível do retorno do ASAAS. */
function asaas_msg_erro(array $data): string {
    if (!empty($data['errors'][0]['description'])) {
        return $data['errors'][0]['description'];
    }
    return $data['erro'] ?? 'Erro ao comunicar com o ASAAS.';
}
