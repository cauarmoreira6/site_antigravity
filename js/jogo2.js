// ============================================================
// MathPlay Solutions — Jogo 2: Cofre das Equações (jogo2.js)
// Equações de 1º grau: encontre o valor de X
// ============================================================

// ---- BANCO DE QUESTÕES ----
// Para equações com resposta não-inteira, usamos decimais simples
const questoes = {
    facil: [
        { expr: 'x + 3 = 7',    resp: 4,  explicacao: 'x + 3 = 7 → x = 7 - 3 → x = 4 (passamos o 3 para o outro lado com sinal trocado)' },
        { expr: 'x - 5 = 2',    resp: 7,  explicacao: 'x - 5 = 2 → x = 2 + 5 → x = 7' },
        { expr: '2x = 10',      resp: 5,  explicacao: '2x = 10 → x = 10 ÷ 2 → x = 5 (dividimos ambos os lados por 2)' },
        { expr: 'x + 8 = 12',   resp: 4,  explicacao: 'x + 8 = 12 → x = 12 - 8 → x = 4' },
        { expr: '3x = 9',       resp: 3,  explicacao: '3x = 9 → x = 9 ÷ 3 → x = 3' },
        { expr: 'x - 1 = 10',   resp: 11, explicacao: 'x - 1 = 10 → x = 10 + 1 → x = 11' },
        { expr: '5x = 20',      resp: 4,  explicacao: '5x = 20 → x = 20 ÷ 5 → x = 4' },
        { expr: 'x + 6 = 15',   resp: 9,  explicacao: 'x + 6 = 15 → x = 15 - 6 → x = 9' },
        { expr: '4x = 16',      resp: 4,  explicacao: '4x = 16 → x = 16 ÷ 4 → x = 4' },
        { expr: 'x - 3 = 8',    resp: 11, explicacao: 'x - 3 = 8 → x = 8 + 3 → x = 11' },
    ],
    medio: [
        { expr: '2x + 4 = 10',   resp: 3,   explicacao: '2x + 4 = 10 → 2x = 10 - 4 → 2x = 6 → x = 6 ÷ 2 → x = 3' },
        { expr: '3x - 6 = 9',    resp: 5,   explicacao: '3x - 6 = 9 → 3x = 9 + 6 → 3x = 15 → x = 15 ÷ 3 → x = 5' },
        { expr: '2x + 6 = 16',   resp: 5,   explicacao: '2x + 6 = 16 → 2x = 16 - 6 → 2x = 10 → x = 10 ÷ 2 → x = 5' },
        { expr: '4x - 8 = 12',   resp: 5,   explicacao: '4x - 8 = 12 → 4x = 12 + 8 → 4x = 20 → x = 20 ÷ 4 → x = 5' },
        { expr: '5x + 5 = 30',   resp: 5,   explicacao: '5x + 5 = 30 → 5x = 30 - 5 → 5x = 25 → x = 25 ÷ 5 → x = 5' },
        { expr: '3x + 9 = 24',   resp: 5,   explicacao: '3x + 9 = 24 → 3x = 24 - 9 → 3x = 15 → x = 15 ÷ 3 → x = 5' },
        { expr: '-2x + 8 = 2',   resp: 3,   explicacao: '-2x + 8 = 2 → -2x = 2 - 8 → -2x = -6 → x = -6 ÷ -2 → x = 3' },
        { expr: '6x - 12 = 18',  resp: 5,   explicacao: '6x - 12 = 18 → 6x = 18 + 12 → 6x = 30 → x = 30 ÷ 6 → x = 5' },
        { expr: '2x + 10 = 20',  resp: 5,   explicacao: '2x + 10 = 20 → 2x = 20 - 10 → 2x = 10 → x = 5' },
        { expr: '4x + 4 = 24',   resp: 5,   explicacao: '4x + 4 = 24 → 4x = 24 - 4 → 4x = 20 → x = 5' },
    ],
    dificil: [
        { expr: '2x + 3 = x + 8',    resp: 5,   explicacao: '2x + 3 = x + 8 → 2x - x = 8 - 3 → x = 5 (agrupamos os x num lado e os números no outro)' },
        { expr: '3(x - 2) = 12',      resp: 6,   explicacao: '3(x - 2) = 12 → Dividimos por 3: x - 2 = 4 → x = 4 + 2 → x = 6' },
        { expr: '5x - 3 = 2x + 9',    resp: 4,   explicacao: '5x - 3 = 2x + 9 → 5x - 2x = 9 + 3 → 3x = 12 → x = 4' },
        { expr: '2(x + 4) = 18',      resp: 5,   explicacao: '2(x + 4) = 18 → x + 4 = 9 → x = 9 - 4 → x = 5' },
        { expr: '4x + 1 = 2x + 11',   resp: 5,   explicacao: '4x + 1 = 2x + 11 → 4x - 2x = 11 - 1 → 2x = 10 → x = 5' },
        { expr: '3(2x - 1) = 15',     resp: 3,   explicacao: '3(2x - 1) = 15 → 2x - 1 = 5 → 2x = 6 → x = 3' },
        { expr: '6x - 4 = 3x + 11',   resp: 5,   explicacao: '6x - 4 = 3x + 11 → 6x - 3x = 11 + 4 → 3x = 15 → x = 5' },
        { expr: 'x/2 + 3 = 7',        resp: 8,   explicacao: 'x/2 + 3 = 7 → x/2 = 4 → x = 4 × 2 → x = 8' },
        { expr: '2x - (x + 3) = 4',   resp: 7,   explicacao: '2x - x - 3 = 4 → x - 3 = 4 → x = 7' },
        { expr: '4(x + 2) = 3(x + 4)',resp: 4,   explicacao: '4x + 8 = 3x + 12 → 4x - 3x = 12 - 8 → x = 4' },
    ],
};

// ---- ESTADO DO JOGO ----
let estado = {
    dificuldade: 'facil',
    questoes:    [],
    indice:      0,
    acertos:     0,
    erros:       0,
    pontuacao:   0,
    xp:          0,
    respondido:  false,
};

// ---- FUNÇÕES ----

function selecionarDif(dif, btn) {
    estado.dificuldade = dif;
    document.querySelectorAll('.diff-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
}

function embaralhar(arr) {
    const a = [...arr];
    for (let i = a.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
}

function iniciarJogo() {
    // Seleciona 8 questões aleatórias da dificuldade escolhida
    estado.questoes    = embaralhar(questoes[estado.dificuldade]).slice(0, 8);
    estado.indice      = 0;
    estado.acertos     = 0;
    estado.erros       = 0;
    estado.pontuacao   = 0;
    estado.xp          = 0;
    estado.respondido  = false;

    // Renderiza os cofres visuais
    renderizarCofres();

    document.getElementById('startScreen').style.display = 'none';
    document.getElementById('resultScreen').classList.remove('show');
    document.getElementById('gamePanel').classList.add('active');

    carregarQuestao();
}

// Renderiza os 8 cofres na tela
function renderizarCofres() {
    const row = document.getElementById('safesRow');
    row.innerHTML = '';
    for (let i = 0; i < 8; i++) {
        const div = document.createElement('div');
        div.id        = `safe${i}`;
        div.className = `safe-item ${i === 0 ? 'current' : 'locked'}`;
        div.textContent = i === 0 ? '🔐' : '🔒';
        row.appendChild(div);
    }
}

// Atualiza os cofres visuais
function atualizarCofres() {
    for (let i = 0; i < 8; i++) {
        const safe = document.getElementById(`safe${i}`);
        if (i < estado.indice) {
            // Cofres já resolvidos
            safe.className   = 'safe-item open';
            safe.textContent = '💰';
        } else if (i === estado.indice) {
            // Cofre atual
            safe.className   = 'safe-item current';
            safe.textContent = '🔐';
        } else {
            // Cofres futuros (bloqueados)
            safe.className   = 'safe-item locked';
            safe.textContent = '🔒';
        }
    }
}

function carregarQuestao() {
    const q = estado.questoes[estado.indice];
    estado.respondido = false;

    const num = estado.indice + 1;
    document.getElementById('questionNumber').textContent = `Cofre ${num} de 8`;
    document.getElementById('cofreAtual').textContent     = num;
    document.getElementById('mathExpr').textContent       = q.expr;

    // Limpa o input
    const input = document.getElementById('answerInput');
    input.value   = '';
    input.disabled = false;
    input.style.borderColor = '#e0e0e0';

    // Habilita o botão de verificar
    document.getElementById('btnVerificar').disabled = false;

    // Esconde feedback e botão próximo
    document.getElementById('feedbackBox').className = 'feedback-box';
    document.getElementById('btnNext').className     = 'btn-next';

    // Foca no input para o aluno já poder digitar
    input.focus();

    atualizarCofres();
}

// Permite submeter com Enter
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !estado.respondido) {
        verificarResposta();
    }
});

function verificarResposta() {
    if (estado.respondido) return;

    const input  = document.getElementById('answerInput');
    const valor  = parseFloat(input.value); // parseFloat lida com decimais

    // Verifica se o aluno digitou algo válido
    if (input.value.trim() === '' || isNaN(valor)) {
        alert('⚠️ Por favor, digite um número para X!');
        input.focus();
        return;
    }

    estado.respondido = true;
    input.disabled    = true;
    document.getElementById('btnVerificar').disabled = true;

    const q       = estado.questoes[estado.indice];
    const acertou = Math.abs(valor - q.resp) < 0.01; // Tolerância para decimais

    const feedbackBox = document.getElementById('feedbackBox');

    if (acertou) {
        estado.acertos++;
        estado.pontuacao += 10;
        estado.xp        += 10;

        input.style.borderColor = '#2ecc71';
        feedbackBox.className   = 'feedback-box correct-fb show';
        document.getElementById('feedbackTitle').textContent = '🔑 Cofre aberto! +10 XP';
        document.getElementById('feedbackText').textContent  = `Correto! x = ${q.resp}. ${q.expr.replace('x', `(${q.resp})`)}`;

        // Animação do cofre abrindo
        const safeEl = document.getElementById(`safe${estado.indice}`);
        safeEl.textContent = '💰';
        safeEl.className   = 'safe-item open';

    } else {
        estado.erros++;
        input.style.borderColor = '#e74c3c';
        feedbackBox.className   = 'feedback-box wrong-fb show';
        document.getElementById('feedbackTitle').textContent = `❌ Valor incorreto! A resposta era x = ${q.resp}`;
        document.getElementById('feedbackText').textContent  = q.explicacao;
    }

    // Atualiza HUD
    document.getElementById('abertosHud').textContent = estado.acertos;
    document.getElementById('errosHud').textContent   = estado.erros;
    document.getElementById('pontosHud').textContent  = estado.pontuacao;

    document.getElementById('btnNext').className = 'btn-next show';
    document.getElementById('btnNext').style.background = '#ff6b35';
}

function proximaQuestao() {
    estado.indice++;
    if (estado.indice >= 8) {
        encerrarJogo();
    } else {
        carregarQuestao();
    }
}

function encerrarJogo() {
    // Bônus por concluir + extra por todos os cofres abertos
    estado.xp += 20;
    if (estado.acertos === 8) {
        estado.xp += 20; // Bônus extra por 100% de aproveitamento
        estado.pontuacao += 20;
    }

    document.getElementById('gamePanel').classList.remove('active');

    const icon  = estado.acertos === 8 ? '💎' : (estado.acertos >= 6 ? '🏆' : '🔑');
    const title = estado.acertos === 8 ? 'Perfeito! Todos os cofres abertos!' : (estado.acertos >= 6 ? 'Ótimo trabalho!' : 'Continue praticando!');

    document.getElementById('resultIcon').textContent  = icon;
    document.getElementById('resultTitle').textContent = title;
    document.getElementById('resultSub').textContent   = `Você abriu ${estado.acertos} de 8 cofres na dificuldade ${estado.dificuldade}!`;
    document.getElementById('rPontuacao').textContent  = estado.pontuacao;
    document.getElementById('rAbertos').textContent    = estado.acertos;
    document.getElementById('rErros').textContent      = estado.erros;
    document.getElementById('xpEarned').textContent    = `+${estado.xp} XP`;

    document.getElementById('resultScreen').classList.add('show');
    salvarResultado();
}

function salvarResultado() {
    const dados = new FormData();
    dados.append('jogo_id',     2);
    dados.append('jogo_nome',   'Cofre das Equações');
    dados.append('pontuacao',   estado.pontuacao);
    dados.append('acertos',     estado.acertos);
    dados.append('erros',       estado.erros);
    dados.append('dificuldade', estado.dificuldade);
    dados.append('xp_ganho',    estado.xp);

    fetch('/site_antigravity/jogos/salvar_resultado.php', { method: 'POST', body: dados })
        .then(r => r.json())
        .then(data => {
            if (data.sucesso) {
                if (data.subiu_nivel) setTimeout(() => alert(`🎉 Você subiu para o Nível ${data.novo_nivel}!`), 500);
                if (data.novas_conquistas && data.novas_conquistas.length > 0) mostrarConquistas(data.novas_conquistas);
            }
        })
        .catch(err => console.error('Erro:', err));
}

function mostrarConquistas(conquistas) {
    const box   = document.getElementById('conquistaBox');
    const lista = document.getElementById('conquistaLista');
    lista.innerHTML = conquistas.map(c => `<div style="display:inline-block;margin:4px;background:rgba(255,255,255,0.6);border-radius:8px;padding:8px 14px;font-weight:700;">${c.icone} ${c.nome}</div>`).join('');
    box.style.display = 'block';
}

function reiniciarJogo() {
    document.getElementById('resultScreen').classList.remove('show');
    document.getElementById('startScreen').style.display = 'block';
    document.getElementById('gamePanel').classList.remove('active');
}

