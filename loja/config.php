<?php
/**
 * Configurações gerais da Loja Vital Brasil
 * Edite aqui os dados de contato e da marca.
 */

return [
    // Identidade
    'site_nome'    => 'Loja Vital Brasil',
    'site_slogan'  => 'Rotinas e combos de exames com praticidade e confiança',
    'logo'         => 'assets/img/logo-vitalbrasil.svg',        // header claro (texto escuro)
    'logo_claro'   => 'assets/img/logo-vitalbrasil-branco.svg', // p/ fundo escuro (rodapé)

    // Contato
    'whatsapp'     => '555331990378', // formato internacional, só números. CONFIRMAR número do WhatsApp
    'telefone'     => '(53) 3199-0378',
    'endereco'     => 'Rua General Osório, 968 - Pelotas/RS',
    'email'        => 'contato@vitalbrasil.net',
    'horario'      => 'Seg a Sex, das 7h às 11h30 e das 13h30 às 17h30',
    'pagamento'    => 'À vista, PIX ou cartão (Banrisul, Mastercard ou Visa)',

    // Site institucional — mesmo domínio, servido na raiz (a loja fica em /loja/)
    'site_url'     => '/',

    // Cores da marca (usadas no CSS via :root) - CONFIRMAR tons exatos
    'cor_primaria'   => '#3f5aa0', // azul royal Vital Brasil
    'cor_secundaria' => '#3DB885', // verde oficial Vital Brasil (do logo)
    'cor_escura'     => '#2a3c72', // azul escuro (para gradientes e rodapé)

    // ───────────── ASAAS (pagamentos) ─────────────
    // IMPORTANTE: NÃO comite a chave real aqui. Crie um arquivo config.local.php
    // (já está no .gitignore) com:  return ['asaas_token' => 'SUA_CHAVE'];
    'asaas_env'    => 'sandbox',  // 'sandbox' para testes, 'producao' quando for ao ar
    'asaas_token'  => '',         // sobrescreva em config.local.php
    'asaas_due_dias' => 2,        // dias para vencimento da cobrança

    // E-mail que recebe os agendamentos (laboratório)
    'notificacao_email' => 'contato@vitalbrasil.net',

    // ───────────── Agenda (slots de coleta) ─────────────
    'agenda' => [
        'intervalo_min'      => 30,   // duração de cada slot, em minutos
        'dias_a_frente'      => 21,   // quantos dias exibir para escolha
        'antecedencia_horas' => 12,   // mínimo de antecedência para agendar
        'dias_semana'        => [1, 2, 3, 4, 5], // 0=dom ... 6=sáb (seg-sex)
        'periodos'           => [
            ['07:00', '11:30'],
            ['13:30', '17:30'],
        ],
    ],
];
