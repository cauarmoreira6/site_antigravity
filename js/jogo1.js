// ============================================================
// MathPlay Solutions — Jogo 1: Batalha dos Inteiros (jogo1.js)
// Operações com números inteiros positivos e negativos
// ============================================================

// ---- BANCO DE QUESTÕES ----
// Cada questão tem: expressão, resposta correta, opções e explicação para erros
const questoes = {

    facil: [
        { expr: '-3 + 5 = ?',    resp: 2,   ops: [2, -2, 8, -8],     explicacao: 'Números com sinais diferentes: subtrai o menor do maior (5 - 3 = 2) e mantém o sinal do maior (+). Resultado: +2' },
        { expr: '4 + (-7) = ?',  resp: -3,  ops: [-3, 3, 11, -11],   explicacao: '4 + (-7) é o mesmo que 4 - 7. Como 7 > 4, o resultado é negativo: -(7-4) = -3' },
        { expr: '-2 - 3 = ?',    resp: -5,  ops: [-5, 5, -1, 1],     explicacao: 'Dois negativos: -2 - 3 = -(2+3) = -5. Quando subtraímos com sinais iguais, somamos e mantemos o sinal.' },
        { expr: '6 + (-2) = ?',  resp: 4,   ops: [4, -4, 8, -8],     explicacao: '6 + (-2) = 6 - 2 = 4. Adicionar um negativo é o mesmo que subtrair.' },
        { expr: '-5 + 5 = ?',    resp: 0,   ops: [0, 10, -10, 5],    explicacao: 'Números opostos sempre somam zero! -5 + 5 = 0.' },
        { expr: '8 - 3 = ?',     resp: 5,   ops: [5, -5, 11, -11],   explicacao: 'Subtração simples: 8 - 3 = 5.' },
        { expr: '-1 + 9 = ?',    resp: 8,   ops: [8, -8, 10, -10],   explicacao: 'Sinais diferentes: 9 - 1 = 8, mantém o sinal do maior (positivo). Resultado: +8' },
        { expr: '0 - 4 = ?',     resp: -4,  ops: [-4, 4, 0, -8],     explicacao: '0 - 4 = -4. Subtrair de zero sempre dá negativo.' },
        { expr: '-6 + 2 = ?',    resp: -4,  ops: [-4, 4, -8, 8],     explicacao: 'Sinais diferentes: 6 - 2 = 4, mantém o sinal do maior (negativo). Resultado: -4' },
        { expr: '3 - 7 = ?',     resp: -4,  ops: [-4, 4, 10, -10],   explicacao: 'Como 7 > 3, o resultado é negativo: -(7-3) = -4' },
    ],

    medio: [
        { expr: '-8 + 15 = ?',     resp: 7,   ops: [7, -7, 23, -23],   explicacao: 'Sinais diferentes: 15 - 8 = 7. Mantém o sinal do maior (positivo). Resultado: +7' },
        { expr: '(-4) × 3 = ?',    resp: -12, ops: [-12, 12, -7, 7],   explicacao: 'Negativo × Positivo = Negativo. 4 × 3 = 12, então: -4 × 3 = -12' },
        { expr: '-3 × (-4) = ?',   resp: 12,  ops: [12, -12, -7, 1],   explicacao: 'Negativo × Negativo = Positivo! 3 × 4 = 12.' },
        { expr: '(-15) ÷ 3 = ?',   resp: -5,  ops: [-5, 5, -12, 12],   explicacao: 'Negativo ÷ Positivo = Negativo. 15 ÷ 3 = 5, então: -15 ÷ 3 = -5' },
        { expr: '-9 - (-4) = ?',   resp: -5,  ops: [-5, 5, -13, 13],   explicacao: 'Menos com menos = mais! -9 - (-4) = -9 + 4 = -5' },
        { expr: '12 + (-20) = ?',  resp: -8,  ops: [-8, 8, -32, 32],   explicacao: '12 + (-20) = 12 - 20 = -8 (pois 20 > 12)' },
        { expr: '(-6) × (-5) = ?', resp: 30,  ops: [30, -30, -11, 11], explicacao: 'Negativo × Negativo = Positivo! 6 × 5 = 30' },
        { expr: '-24 ÷ (-6) = ?',  resp: 4,   ops: [4, -4, -18, 18],   explicacao: 'Negativo ÷ Negativo = Positivo! 24 ÷ 6 = 4' },
        { expr: '-7 + (-8) = ?',   resp: -15, ops: [-15, 15, -1, 1],   explicacao: 'Sinais iguais (ambos negativos): somamos e mantemos o sinal. -7 + (-8) = -(7+8) = -15' },
        { expr: '5 × (-7) = ?',    resp: -35, ops: [-35, 35, -2, 2],   explicacao: 'Positivo × Negativo = Negativo. 5 × 7 = 35, então: 5 × (-7) = -35' },
    ],

    dificil: [
        { expr: '-3 + 7 - (-2) = ?',       resp: 6,    ops: [6, -6, 2, -2],      explicacao: '-3 + 7 - (-2) = -3 + 7 + 2 = 4 + 2 = 6. Lembre: subtrair negativo = somar positivo!' },
        { expr: '(-2)² = ?',               resp: 4,    ops: [4, -4, -2, 2],      explicacao: '(-2)² = (-2) × (-2) = 4. Negativo elevado ao quadrado é positivo!' },
        { expr: '-2 × 3 + 10 = ?',         resp: 4,    ops: [4, -4, 16, -16],    explicacao: 'Por ordem de operações: primeiro -2×3 = -6, depois -6 + 10 = 4' },
        { expr: '(-3)³ = ?',               resp: -27,  ops: [-27, 27, -9, 9],    explicacao: '(-3)³ = (-3)×(-3)×(-3) = 9×(-3) = -27. Potência ímpar mantém o sinal!' },
        { expr: '15 - 8 × (-2) = ?',       resp: 31,   ops: [31, -31, -1, 1],    explicacao: 'Ordem: primeiro 8×(-2) = -16, depois 15 - (-16) = 15 + 16 = 31' },
        { expr: '(-4 + 2) × 5 = ?',        resp: -10,  ops: [-10, 10, 30, -30],  explicacao: 'Parênteses primeiro: -4+2 = -2, depois (-2)×5 = -10' },
        { expr: '-100 ÷ (-4) ÷ 5 = ?',     resp: 5,    ops: [5, -5, -125, 125],  explicacao: '-100 ÷ (-4) = 25 (neg÷neg=pos), depois 25 ÷ 5 = 5' },
        { expr: '(-3) × (-2) + (-8) = ?',  resp: -2,   ops: [-2, 2, 14, -14],    explicacao: 'Primeiro: (-3)×(-2) = 6, depois 6 + (-8) = 6 - 8 = -2' },
        { expr: '|−9| + (−4) = ?',         resp: 5,    ops: [5, -5, 13, -13],    explicacao: 'Módulo: |−9| = 9 (sempre positivo!), depois 9 + (−4) = 9 − 4 = 5' },
        { expr: '-3 × (4 - 7) = ?',        resp: 9,    ops: [9, -9, -33, 33],    explicacao: 'Parênteses: 4 - 7 = -3, depois -3 × (-3) = 9' },
    ]
};

// Inimigos para cada dificuldade
const inimigos = {
    facil:   { nome: 'Zumbi dos Números', avatar: 'ZN', hp: 100 },
    medio:   { nome: 'Dragão das Equações', avatar: 'DE', hp: 150 },
    dificil: { nome: 'Vilão Matemático', avatar: 'VM', hp: 200 },
};

// ---- ESTADO DO JOGO ----
// Todas as variáveis que controlam o jogo ficam neste objeto
let estado = {
    dificuldade:    'facil',
    questoes:       [],         // Questões embaralhadas
    indice:         0,          // Índice da questão atual
    acertos:        0,
    erros:          0,
    pontuacao:      0,
    xp:             0,
    sequencia:      0,          // Contagem de acertos consecutivos
    hpAtual:        100,
    hpMax:          100,
    respondido:     false,      // Se a questão já foi respondida
    respostaCorreta:null,       // Índice da resposta correta
};

// ---- FUNÇÕES DE INTERFACE ----

// Seleciona a dificuldade ao clicar nos botões
function selecionarDif(dif, btn) {
    estado.dificuldade = dif;
    // Remove a classe 'selected' de todos os botões
    document.querySelectorAll('.diff-btn').forEach(b => b.classList.remove('selected'));
    // Adiciona 'selected' no botão clicado
    btn.classList.add('selected');
}

// Embaralha um array (algoritmo Fisher-Yates)
// Isso garante que as questões apareçam em ordem aleatória
function embaralhar(arr) {
    const a = [...arr]; // Cria uma cópia para não alterar o original
    for (let i = a.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [a[i], a[j]] = [a[j], a[i]]; // Troca os elementos
    }
    return a;
}

// Inicia o jogo
function iniciarJogo() {
    const dif = estado.dificuldade;

    // Reinicia o estado
    estado.questoes      = embaralhar(questoes[dificuldadeDoAno(dif, 1)]).slice(0, 10); // Pega 10 aleatórias
    estado.indice        = 0;
    estado.acertos       = 0;
    estado.erros         = 0;
    estado.pontuacao     = 0;
    estado.xp            = 0;
    estado.sequencia     = 0;
    estado.hpAtual       = inimigos[dif].hp;
    estado.hpMax         = inimigos[dif].hp;
    estado.respondido    = false;

    // Configura o inimigo
    const inimigo = inimigos[dif];
    document.getElementById('enemyAvatar').textContent = inimigo.avatar;
    document.getElementById('enemyName').textContent   = inimigo.nome;
    atualizarHP();

    // Mostra o painel do jogo, esconde a tela inicial
    document.getElementById('startScreen').style.display = 'none';
    document.getElementById('resultScreen').classList.remove('show');
    document.getElementById('gamePanel').classList.add('active');

    carregarQuestao();
}

// Carrega e exibe a questão atual
function carregarQuestao() {
    const q = estado.questoes[estado.indice];
    estado.respondido    = false;
    estado.respostaCorreta = null;

    // Atualiza textos
    const num = estado.indice + 1;
    document.getElementById('questionNumber').textContent = `Questão ${num} de 10`;
    document.getElementById('questaoAtual').textContent   = num;
    document.getElementById('mathExpr').textContent       = q.expr;

    // Atualiza barra de progresso
    document.getElementById('progressFill').style.width = `${(estado.indice / 10) * 100}%`;

    // Embaralha as opções para não aparecerem sempre na mesma posição
    const ops = embaralhar(q.ops);
    // Encontra o índice da resposta correta no array embaralhado
    estado.respostaCorreta = ops.indexOf(q.resp);

    // Preenche os botões de opção
    for (let i = 0; i < 4; i++) {
        const btn = document.getElementById(`opt${i}`);
        btn.textContent = ops[i];
        btn.className   = 'option-btn'; // Remove classes anteriores
        btn.disabled    = false;
    }

    // Esconde feedback e botão "próxima"
    const fb = document.getElementById('feedbackBox');
    fb.className = 'feedback-box';
    document.getElementById('btnNext').className = 'btn-next';
}

// Verifica se a resposta do aluno está correta
function verificarResposta(indiceOpcao) {
    if (estado.respondido) return; // Ignora se já respondeu
    estado.respondido = true;

    // Desabilita todos os botões para evitar clique duplo
    for (let i = 0; i < 4; i++) {
        document.getElementById(`opt${i}`).disabled = true;
    }

    const q           = estado.questoes[estado.indice];
    const acertou     = (indiceOpcao === estado.respostaCorreta);
    const btnClicado  = document.getElementById(`opt${indiceOpcao}`);
    const btnCorreto  = document.getElementById(`opt${estado.respostaCorreta}`);
    const feedbackBox = document.getElementById('feedbackBox');

    if (acertou) {
        // ---- ACERTO ----
        estado.acertos++;
        estado.sequencia++;

        // Pontuação: 10 pts base + 5 de bônus a cada 3 seguidos
        let pts = 10;
        let xpGanho = 10;
        if (estado.sequencia % 3 === 0) {
            pts     += 5;
            xpGanho += 5;
        }
        estado.pontuacao += pts;
        estado.xp        += xpGanho;

        // Dano no inimigo: -20 HP
        estado.hpAtual = Math.max(0, estado.hpAtual - 20);
        atualizarHP();

        // Marca o botão como correto (verde)
        btnClicado.classList.add('correct');

        // Feedback positivo
        feedbackBox.className = 'feedback-box correct-fb show';
        document.getElementById('feedbackTitle').textContent = ' Correto! ' + (estado.sequencia % 3 === 0 && estado.sequencia > 0 ? ' Bônus de sequência +5 XP!' : '+10 XP');
        document.getElementById('feedbackText').textContent  = `Muito bem! ${q.expr.replace('?', q.resp)}`;

    } else {
        // ---- ERRO ----
        estado.erros++;
        estado.sequencia = 0; // Zera a sequência

        // Inimigo recupera HP: +5
        estado.hpAtual = Math.min(estado.hpMax, estado.hpAtual + 5);
        atualizarHP();

        // Marca o botão clicado como errado (vermelho) e o correto (verde)
        btnClicado.classList.add('wrong');
        btnCorreto.classList.add('correct');

        // Feedback com explicação didática
        feedbackBox.className = 'feedback-box wrong-fb show';
        document.getElementById('feedbackTitle').textContent = ' Resposta errada!';
        document.getElementById('feedbackText').textContent  = q.explicacao;
    }

    // Atualiza o HUD
    document.getElementById('scorePlacar').textContent  = estado.pontuacao;
    document.getElementById('acertosHud').textContent   = estado.acertos;
    document.getElementById('errosHud').textContent     = estado.erros;
    document.getElementById('sequenciaHud').textContent = estado.sequencia;

    // Mostra o botão de próxima questão
    document.getElementById('btnNext').className = 'btn-next show';
}

// Vai para a próxima questão ou termina o jogo
function proximaQuestao() {
    estado.indice++;
    if (estado.indice >= 10) {
        encerrarJogo();
    } else {
        carregarQuestao();
    }
}

// Atualiza a barra de HP do inimigo
function atualizarHP() {
    const pct = (estado.hpAtual / estado.hpMax) * 100;
    document.getElementById('hpBar').style.width = `${pct}%`;
    document.getElementById('hpText').textContent = `HP: ${estado.hpAtual} / ${estado.hpMax}`;
}

// Encerra o jogo e mostra a tela de resultado
function encerrarJogo() {
    // Bônus de conclusão (+20 XP)
    estado.xp += 20;

    // Esconde o painel do jogo
    document.getElementById('gamePanel').classList.remove('active');

    // Atualiza a tela de resultado
    const title = estado.erros === 0 ? 'Perfeito! Sem erros!' : (estado.acertos >= 7 ? 'Ótimo resultado!' : 'Continue praticando!');
    const sub   = `Você acertou ${estado.acertos} de 10 questões na dificuldade ${estado.dificuldade}!`;

    document.getElementById('resultTitle').textContent    = title;
    document.getElementById('resultSub').textContent      = sub;
    document.getElementById('rPontuacao').textContent     = estado.pontuacao;
    document.getElementById('rAcertos').textContent       = estado.acertos;
    document.getElementById('rErros').textContent         = estado.erros;
    document.getElementById('xpEarned').textContent       = `+${estado.xp} XP`;

    document.getElementById('resultScreen').classList.add('show');

    // Salva o resultado no banco de dados via AJAX
    salvarResultado();
}

// Envia os dados do jogo para o PHP via fetch() (AJAX)
function salvarResultado() {
    // FormData permite enviar dados como se fosse um formulário
    const dados = new FormData();
    dados.append('jogo_id',     1);
    dados.append('jogo_nome',   'Batalha dos Inteiros');
    dados.append('pontuacao',   estado.pontuacao);
    dados.append('acertos',     estado.acertos);
    dados.append('erros',       estado.erros);
    dados.append('dificuldade', estado.dificuldade);
    dados.append('xp_ganho',    estado.xp);

    // fetch() faz uma requisição para o PHP sem recarregar a página
    fetch('/site_antigravity/jogos/salvar_resultado.php', {
        method: 'POST',
        body:   dados
    })
    .then(res => res.json())  // Converte a resposta para JSON
    .then(data => {
        if (data.sucesso) {
            // Verifica se subiu de nível
            if (data.subiu_nivel) {
                setTimeout(() => alert(` Parabéns! Você subiu para o Nível ${data.novo_nivel}!`), 500);
            }
            // Mostra novas conquistas
            if (data.novas_conquistas && data.novas_conquistas.length > 0) {
                mostrarConquistas(data.novas_conquistas);
            }
        }
    })
    .catch(err => console.error('Erro ao salvar:', err));
}

// Exibe as novas conquistas ganhas
function mostrarConquistas(conquistas) {
    const box   = document.getElementById('conquistaBox');
    const lista = document.getElementById('conquistaLista');
    lista.innerHTML = conquistas.map(c => `
        <div style="display:inline-block;margin:4px;background:rgba(255,255,255,0.6);
                    border-radius:8px;padding:8px 14px;font-weight:700;">
            ${c.icone} ${c.nome}
        </div>
    `).join('');
    box.style.display = 'block';
}

// Reinicia o jogo voltando para a tela inicial
function reiniciarJogo() {
    document.getElementById('resultScreen').classList.remove('show');
    document.getElementById('startScreen').style.display = 'block';
    document.getElementById('gamePanel').classList.remove('active');
}
