<?php
/**
 * EXEMPLO de configuração local com segredos.
 *
 * 1. Copie este arquivo para  config.local.php
 * 2. Preencha com a sua chave do ASAAS
 * 3. NÃO comite o config.local.php (já está no .gitignore)
 *
 * Os valores aqui sobrescrevem os de config.php.
 */

return [
    // 'sandbox' para testes, 'producao' quando for cobrar de verdade
    'asaas_env'   => 'sandbox',

    // Chave de API do ASAAS (Painel ASAAS > Integrações > Chave de API)
    'asaas_token' => 'COLOQUE_AQUI_SUA_CHAVE_ASAAS',

    // E-mail do laboratório que recebe os agendamentos
    'notificacao_email' => 'contato@vitalbrasil.net',
];
