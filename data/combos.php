<?php
/**
 * Catálogo de combos / rotinas de exames.
 *
 * Para adicionar um novo combo, copie um bloco do array e edite os campos.
 * O 'slug' precisa ser único — é ele que monta a URL: combo.php?slug=...
 *
 * Campos:
 *   slug         => identificador único na URL (sem espaços/acentos)
 *   nome         => título do combo
 *   categoria    => Saúde da Mulher, Saúde do Homem, Infantil, Performance, Hormonal, Check-up...
 *   destaque     => true para receber o selo "Destaque" no card
 *   resumo       => frase curta exibida no card da home
 *   imagem       => caminho da imagem (assets/img/...)
 *   preco        => valor numérico (ex.: 174.00). 0 = "Sob consulta"
 *   preco_de     => preço "de" riscado (opcional, 0 para ocultar)
 *   preco_obs    => observação abaixo do preço (ex.: "em até 3x sem juros") | null
 *   jejum        => texto sobre jejum | null
 *   indicacao    => para quem é indicado | null
 *   descricao    => parágrafo(s) (HTML simples permitido)
 *   beneficios   => bullets de marketing
 *   exames       => lista de exames inclusos
 *   adicionais   => lista de add-ons opcionais (ex.: "Pré-câncer: + R$ 30,00") | []
 *
 * OBS: campos com valor 0 / placeholder estão marcados com // CONFIRMAR.
 */

return [

    // ───────────────────────── SAÚDE DA MULHER ─────────────────────────
    [
        'slug'       => 'rotina-vital-mulher',
        'nome'       => 'Rotina Vital Mulher',
        'categoria'  => 'Saúde da Mulher',
        'destaque'   => true,
        'resumo'     => 'Rotina completa sob medida para cuidar da saúde feminina com praticidade.',
        'imagem'     => 'assets/img/rotina-vital-mulher.jpg',
        'preco'      => 174.00,
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Recomendado jejum para alguns exames. Confirme no agendamento.',
        'indicacao'  => 'Mulheres que buscam avaliação completa, prevenção e acompanhamento de saúde de forma prática.',
        'descricao'  => 'Montamos uma rotina de exames especial pensando na saúde da mulher. Podemos ainda incluir ou excluir algum exame de acordo com a sua necessidade. O exame é feito por agendamento, a partir da coleta de sangue no laboratório.',
        'beneficios' => [
            'Avaliação completa da saúde feminina',
            'Resultados ágeis e confiáveis',
            'Excelente custo-benefício',
        ],
        'exames'     => [
            'Hemograma',
            'EQU (exame de urina)',
            'TSH',
            'T4 livre (T4L)',
            'Colesterol e frações',
            'FSH',
            'LH',
            'Estradiol',
            'Beta-HCG',
            'Creatinina',
            'Ureia',
            'TGO',
            'TGP',
        ],
        'adicionais' => [
            'Pré-câncer (preventivo): + R$ 30,00',
        ],
    ],

    [
        'slug'       => 'painel-hormonal-feminino',
        'nome'       => 'Painel Hormonal Feminino',
        'categoria'  => 'Hormonal',
        'destaque'   => false,
        'resumo'     => 'Avaliação hormonal, tireoide e reservas de ferro e vitaminas para a mulher.',
        'imagem'     => 'assets/img/painel-hormonal-feminino.jpg',
        'preco'      => 273.00,
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Recomendado jejum para alguns exames. Confirme no agendamento.',
        'indicacao'  => 'Mulheres com queixas hormonais, alterações de tireoide, cansaço ou queda de cabelo.',
        'descricao'  => 'Painel voltado à avaliação hormonal e metabólica feminina, com marcadores de tireoide, hormônios sexuais e reservas nutricionais.',
        'beneficios' => [
            'Avaliação hormonal e da tireoide',
            'Inclui ferro, ferritina, vitamina D e B12',
            'Apoio ao diagnóstico de queda de cabelo e fadiga',
        ],
        'exames'     => [
            'Hemograma',
            'Ferro',
            'Ferritina',
            'TSH',
            'T4 livre (T4L)',
            'Anti-TPO',
            'Testosterona total e livre', // "tos e ttl" — CONFIRMAR
            'Prolactina',
            'Estradiol',
            'Progesterona',
            'LH',
            'FSH',
            'Vitamina D (25-OH)',
            'Vitamina B12',
        ],
        'adicionais' => [],
    ],

    // ───────────────────────── SAÚDE DO HOMEM ─────────────────────────
    [
        'slug'       => 'rotina-vital-homem',
        'nome'       => 'Rotina Vital Homem',
        'categoria'  => 'Saúde do Homem',
        'destaque'   => true,
        'resumo'     => 'Pacote completo de exames masculinos para desempenho, vitalidade e prevenção.',
        'imagem'     => 'assets/img/rotina-vital-homem.jpg',
        'preco'      => 0,      // CONFIRMAR valor
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Recomendado jejum para alguns exames. Confirme no agendamento.',
        'indicacao'  => 'Homens que buscam desempenho, vitalidade e prevenção.',
        'descricao'  => 'A força de um homem começa pela sua saúde. Preparamos um pacote completo de exames masculinos para quem busca desempenho, vitalidade e prevenção. Resultados rápidos, atendimento discreto e profissional.',
        'beneficios' => [
            'Avaliação metabólica completa',
            'Inclui função da próstata',
            'Avaliação renal e hepática',
        ],
        'exames'     => [
            'Hemograma',
            'Função da próstata (PSA)',
            'Colesterol e frações',
            'Glicemia',
            'Triglicerídeos',
            'Avaliação renal (ureia e creatinina)',
            'Avaliação hepática (TGO e TGP)',
        ],
        'adicionais' => [
            'Dosagem de testosterona e outros hormônios: custo adicional',
        ],
    ],

    [
        'slug'       => 'painel-masculino-vitaminas-hormonios',
        'nome'       => 'Painel Masculino — Vitaminas e Hormônios',
        'categoria'  => 'Saúde do Homem',
        'destaque'   => false,
        'resumo'     => 'Avalia vitaminas, minerais e hormônios ligados à energia, libido e massa muscular.',
        'imagem'     => 'assets/img/painel-masculino-vitaminas-hormonios.jpg',
        'preco'      => 0,      // CONFIRMAR valor
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Recomendado jejum para alguns exames. Confirme no agendamento.',
        'indicacao'  => 'Homens a partir de 18 anos, com cansaço excessivo, baixa disposição, queda de libido ou alteração de massa muscular.',
        'descricao'  => 'Painel masculino desenvolvido para avaliar vitaminas e hormônios importantes para a saúde do homem — imunidade, disposição, força muscular, libido, fertilidade, humor e energia.',
        'beneficios' => [
            'Vitaminas e minerais para imunidade e disposição',
            'Hormônios para libido, fertilidade e massa muscular',
            'Resultados rápidos e atendimento discreto',
        ],
        'exames'     => [
            'Vitamina D',
            'Vitamina B12',
            'Ácido Fólico',
            'Zinco',
            'Ferro',
            'Testosterona Total',
            'Testosterona Livre',
            'SHBG',
            'LH',
            'FSH',
            'Prolactina',
        ],
        'adicionais' => [],
    ],

    // ───────────────────────── INFANTIL ─────────────────────────
    [
        'slug'       => 'rotina-vital-infantil',
        'nome'       => 'Rotina Vital Infantil',
        'categoria'  => 'Infantil',
        'destaque'   => false,
        'resumo'     => 'Rotina especial pensada para a saúde das crianças.',
        'imagem'     => 'assets/img/rotina-vital-infantil.jpg',
        'preco'      => 96.00,
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Recomendado jejum para alguns exames. Confirme no agendamento.',
        'indicacao'  => 'Crianças, para acompanhamento de rotina e prevenção.',
        'descricao'  => 'Criamos uma rotina de exames especial pensando na saúde das nossas crianças.',
        'beneficios' => [
            'Avaliação de rotina infantil',
            'Inclui parasitológico de fezes',
            'Pagamento no cartão, dinheiro ou PIX',
        ],
        'exames'     => [
            'Hemograma',
            'Glicose',
            'Colesterol total',
            'Creatinina',
            'Cálcio',
            'Ferro',
            'EQU (exame de urina)',
            'Parasitológico de fezes',
        ],
        'adicionais' => [],
    ],

    // ───────────────────────── PERFORMANCE ─────────────────────────
    [
        'slug'       => 'rotina-vital-performance-basico',
        'nome'       => 'Rotina Vital Performance — Plano Básico',
        'categoria'  => 'Performance',
        'destaque'   => true,
        'resumo'     => 'Exames como indicadores de performance, qualidade de vida e saúde.',
        'imagem'     => 'assets/img/rotina-vital-performance.jpg',
        'preco'      => 150.00,
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Recomendado jejum para alguns exames. Confirme no agendamento.',
        'indicacao'  => 'Quem quer perder peso, ganhar massa magra ou melhorar a saúde por meio de exercícios e dieta.',
        'descricao'  => 'Já pensou em usar exames laboratoriais como indicadores para melhorar performance, qualidade de vida e saúde? Preparamos a Rotina Vital Performance. <strong>Existe também o Plano Completo</strong>, que soma os exames avançados.',
        'beneficios' => [
            'Indicadores metabólicos e hepáticos',
            'Ideal para acompanhar treino e dieta',
            'Excelente custo-benefício',
        ],
        'exames'     => [
            'Hemograma',
            'Hemoglobina glicada',
            'Glicose',
            'Colesterol e frações',
            'TGO / TGP',
            'Albumina',
            'Gama-GT',
            'Bilirrubinas',
            'Ureia',
            'Creatinina',
            'Ácido úrico',
            'CK-total',
        ],
        'adicionais' => [
            'Upgrade para o Plano Completo (27 exames): R$ 440,00',
        ],
    ],

    [
        'slug'       => 'rotina-vital-performance-completo',
        'nome'       => 'Rotina Vital Performance — Plano Completo',
        'categoria'  => 'Performance',
        'destaque'   => false,
        'resumo'     => 'Plano básico + avançado: 27 exames para uma avaliação aprofundada.',
        'imagem'     => 'assets/img/rotina-vital-performance.jpg',
        'preco'      => 440.00,
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Recomendado jejum para alguns exames. Confirme no agendamento.',
        'indicacao'  => 'Quem busca a avaliação mais completa de performance, hormônios e micronutrientes.',
        'descricao'  => 'O Plano Completo soma ao Plano Básico um painel avançado de eletrólitos, hormônios e vitaminas — totalizando 27 exames.',
        'beneficios' => [
            'Avaliação aprofundada de performance',
            'Inclui painel hormonal e de vitaminas',
            'Eletrólitos e micronutrientes',
        ],
        'exames'     => [
            'Hemograma',
            'Hemoglobina glicada',
            'Glicose',
            'Colesterol e frações',
            'TGO / TGP',
            'Albumina',
            'Gama-GT',
            'Bilirrubinas',
            'Ureia',
            'Creatinina',
            'Ácido úrico',
            'CK-total',
            'Sódio',
            'Potássio',
            'Cálcio iônico',
            'Selênio',
            'TSH',
            'T4 livre',
            'Vitamina B12',
            'Vitamina D',
            'LH',
            'Testosterona total',
            'Testosterona livre',
            'Insulina',
            'Prolactina',
            'Estradiol',
            'Progesterona',
        ],
        'adicionais' => [],
    ],

    // ───────────────────────── CHECK-UPS / ESPECÍFICOS ─────────────────────────
    [
        'slug'       => 'painel-queda-capilar',
        'nome'       => 'Painel Queda Capilar',
        'categoria'  => 'Check-up',
        'destaque'   => false,
        'resumo'     => 'Investigue as causas da queda de cabelo: vitaminas, hormônios e metabolismo.',
        'imagem'     => 'assets/img/painel-queda-capilar.jpg',
        'preco'      => 173.00,
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Recomendado jejum para alguns exames. Confirme no agendamento.',
        'indicacao'  => 'Quem está perdendo mais fios que o normal e quer identificar a origem do problema.',
        'descricao'  => 'A queda de cabelo pode ter várias causas — hormonais, nutricionais, genéticas ou estresse. Com este painel você avalia níveis de vitaminas e minerais, equilíbrio hormonal e possíveis disfunções, para escolher o melhor tratamento com seu especialista.',
        'beneficios' => [
            'Avaliação de vitaminas e minerais',
            'Avaliação do equilíbrio hormonal',
            'Apoio à escolha do tratamento ideal',
        ],
        // Lista expandida a partir das abreviações enviadas — CONFIRMAR alguns itens
        'exames'     => [
            'Hemograma',
            'Creatinina',
            'Colesterol total',
            'LDL',
            'HDL',
            'Ureia',
            'Triglicerídeos',
            'TGO',
            'TGP',
            'Hemoglobina glicada (A1c)',
            'Glicose',
            'TSH',
            'LH',
            'Estradiol', // "etm" — CONFIRMAR
            'FSH',
            'EQU (exame de urina)',
        ],
        'adicionais' => [],
    ],

    [
        'slug'       => 'check-up-vitaminas',
        'nome'       => 'Check-up de Vitaminas',
        'categoria'  => 'Check-up',
        'destaque'   => false,
        'resumo'     => 'Descubra se cansaço e baixa imunidade têm relação com falta de vitaminas.',
        'imagem'     => 'assets/img/check-up-vitaminas.jpg',
        'preco'      => 0,      // CONFIRMAR valor
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Não costuma exigir jejum. Confirme no agendamento.',
        'indicacao'  => 'Quem sente cansaço, queda de imunidade ou falta de disposição.',
        'descricao'  => 'O Check-up de Vitaminas é uma forma rápida e eficaz de entender o que o seu corpo realmente precisa.',
        'beneficios' => [
            'Avaliação ampla de vitaminas',
            'Apoio ao combate de cansaço e baixa imunidade',
            'Resultados rápidos',
        ],
        'exames'     => [
            'Vitamina A',
            'Vitamina C',
            'Vitamina D',
            'Vitamina E',
            'Vitamina K',
            'Vitamina B12',
            'Ácido Fólico',
        ],
        'adicionais' => [],
    ],

    [
        'slug'       => 'avaliacao-inchaco-abdominal',
        'nome'       => 'Avaliação de Inchaço / Aumento Abdominal',
        'categoria'  => 'Check-up',
        'destaque'   => false,
        'resumo'     => 'Avaliação completa para identificar a causa do inchaço e do aumento abdominal.',
        'imagem'     => 'assets/img/avaliacao-inchaco.jpg',
        'preco'      => 385.00,
        'preco_de'   => 0,
        'preco_obs'  => 'Em até 3x sem juros no cartão',
        'jejum'      => 'Recomendado jejum para alguns exames. Confirme no agendamento.',
        'indicacao'  => 'Quem percebe aumento abdominal ou sensação de inchaço e quer entender a causa.',
        'descricao'  => 'Uma avaliação completa é a melhor forma de descobrir o que está acontecendo — identificando a causa do desconforto com precisão e indicando exatamente o que o seu corpo precisa, com um plano personalizado.',
        'beneficios' => [
            'Avaliação completa e personalizada',
            'Clareza e segurança no diagnóstico',
            'Parcelamento em até 3x sem juros',
        ],
        'exames'     => [
            // Lista de exames não especificada pelo cliente — preencher.
        ],
        'adicionais' => [],
    ],

    [
        'slug'       => 'minipainel-respiratorio',
        'nome'       => 'Minipainel Respiratório',
        'categoria'  => 'Check-up',
        'destaque'   => false,
        'resumo'     => 'Diagnostica os 4 principais vírus respiratórios, com resultado no mesmo dia.',
        'imagem'     => 'assets/img/minipainel-respiratorio.jpg',
        'preco'      => 0,      // CONFIRMAR valor
        'preco_de'   => 0,
        'preco_obs'  => null,
        'jejum'      => 'Não exige jejum. Coleta por SWAB nasal.',
        'indicacao'  => 'Crianças e adultos com sintomas respiratórios. Detecta o vírus que causa a bronquiolite, principal causa de internação infantil.',
        'descricao'  => 'O minipainel respiratório diagnostica os quatro principais vírus respiratórios. A coleta é feita através de um SWAB (espécie de cotonete) nasal e o resultado é entregue no mesmo dia de realização do exame.',
        'beneficios' => [
            'Detecta os 4 principais vírus respiratórios',
            'Resultado no mesmo dia',
            'Coleta rápida por SWAB nasal',
        ],
        'exames'     => [
            'Pesquisa dos 4 principais vírus respiratórios (incluindo o causador da bronquiolite)',
        ],
        'adicionais' => [],
    ],

];
