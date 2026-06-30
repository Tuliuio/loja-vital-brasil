# Loja Vital Brasil

Loja simples em **PHP/HTML** para venda de rotinas e combos de exames do laboratório Vital Brasil.
Hospedada na Hostinger, que puxa automaticamente do GitHub.

## Estrutura

```
loja-vital-brasil/
├── index.php              # Home — grid com todos os combos
├── combo.php              # Página interna de um combo (combo.php?slug=...)
├── config.php             # Dados de contato, marca e cores  ← EDITAR
├── data/
│   └── combos.php         # Catálogo de combos               ← ADICIONAR PRODUTOS AQUI
├── includes/
│   ├── functions.php      # Funções auxiliares
│   ├── header.php         # Cabeçalho + menu
│   └── footer.php         # Rodapé
└── assets/
    ├── css/style.css
    ├── js/main.js
    └── img/               # Logo e imagens dos combos
```

## Como adicionar um combo

Edite `data/combos.php` e copie um bloco do array, ajustando os campos.
O `slug` precisa ser único — é ele que monta a URL: `combo.php?slug=meu-combo`.
Coloque a imagem em `assets/img/` com o nome usado no campo `imagem`.

## Configuração inicial (importante)

No `config.php`, confirme/ajuste:

- **`whatsapp`** — número real no formato internacional (só dígitos). Ex.: `55539...`
- **`logo`** — coloque o arquivo do logo em `assets/img/`
- **cores da marca** — `cor_primaria`, `cor_secundaria`, `cor_escura`
- **preços** em `data/combos.php` — hoje estão como `0` ("Sob consulta")

## Fluxo de compra (ASAAS + agendamento)

1. **combo.php** → botão **"Comprar agora"** (combos com preço definido).
2. **checkout.php** → cliente informa nome, CPF, e-mail e telefone.
   O site cria um cliente e uma cobrança na API do ASAAS e redireciona
   para a fatura (PIX, cartão ou boleto).
3. Após pagar, o ASAAS redireciona de volta para **confirmacao.php**,
   que consulta o status do pagamento. Confirmado → exibe a tela de agendamento.
4. **Agendamento:** o cliente escolhe dia e horário (slots gerados a partir do
   expediente em `config.php > agenda`) e confirma a presença.
5. **agendar.php** revalida o pagamento e **notifica o laboratório por e-mail**
   (com fallback de confirmação por WhatsApp).

> Os combos com `preco => 0` ("Sob consulta") não entram no checkout — exibem
> botão de WhatsApp para consultar valor.

### Configurar o ASAAS (obrigatório para o checkout funcionar)

1. Copie `config.local.example.php` para **`config.local.php`** (este não vai para o Git).
2. Preencha `asaas_token` com a sua chave de API (Painel ASAAS → Integrações → Chave de API).
3. Use `asaas_env => 'sandbox'` para testar e `'producao'` para cobrar de verdade.

> A chave **nunca** deve ser comitada. O `config.local.php` está no `.gitignore`.

### Observações

- **Sem banco de dados:** o pedido fica na sessão do navegador e a confirmação
  do pagamento é verificada direto na API do ASAAS. O agendamento é enviado por
  e-mail ao laboratório. Para histórico/painel, dá para migrar para MySQL depois.
- **Slots sem controle de vagas:** atendimento por ordem de chegada (combinado com o cliente).
- **Webhook (opcional):** para máxima confiabilidade, dá para configurar um
  webhook ASAAS apontando para um `webhook.php` — hoje a confirmação é feita no
  retorno via API, o que já cobre o fluxo normal.

## Rodar localmente

```bash
php -S localhost:8000
```
Acesse http://localhost:8000

> O checkout real exige a chave do ASAAS em `config.local.php`. Sem ela, as
> páginas abrem normalmente, mas a criação da cobrança retorna erro amigável.

## Deploy (Hostinger + GitHub)

1. Suba este conteúdo para o repositório `loja-vital-brasil`.
2. Na Hostinger, conecte o repositório (GitHub auto deploy) apontando para a
   pasta pública (`public_html` ou subpasta).
3. A cada `git push`, a Hostinger atualiza o site.
