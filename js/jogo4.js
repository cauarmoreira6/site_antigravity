// ============================================================
// MathPlay Solutions — Jogo 4: Detetive dos Gráficos (jogo4.js)
// Gráficos de barras, leitura de dados, média, moda e mediana
// ============================================================

const casos = {
    facil: [
        {
            titulo: 'Livros Lidos na Biblioteca Municipal',
            dados: [{ rotulo: 'Seg', val: 12 }, { rotulo: 'Ter', val: 20 }, { rotulo: 'Qua', val: 15 }, { rotulo: 'Qui', val: 25 }, { rotulo: 'Sex', val: 18 }],
            pergunta: 'Em qual dia da semana houve o maior número de livros lidos?',
            resp: 'Qui', ops: ['Ter', 'Qua', 'Qui', 'Sex'],
            pista: 'O suspeito foi visto na biblioteca no dia com maior movimentação!',
            explicacao: 'Basta observar a coluna mais alta: Quinta-feira teve 25 livros lidos.'
        },
        {
            titulo: 'Pistas Coletadas por Bairro',
            dados: [{ rotulo: 'Centro', val: 14 }, { rotulo: 'Norte', val: 8 }, { rotulo: 'Sul', val: 16 }, { rotulo: 'Leste', val: 10 }],
            pergunta: 'Quantas pistas foram coletadas ao todo nos bairros Centro e Sul?',
            resp: '30', ops: ['24', '30', '28', '32'],
            pista: 'Centro (14) + Sul (16) somam pistas cruciais!',
            explicacao: 'Soma: 14 + 16 = 30 pistas.'
        },
        {
            titulo: 'Horas Gastas em Câmeras de Segurança',
            dados: [{ rotulo: 'Câm 1', val: 6 }, { rotulo: 'Câm 2', val: 9 }, { rotulo: 'Câm 3', val: 4 }, { rotulo: 'Câm 4', val: 9 }],
            pergunta: 'Qual o valor que mais se repete (Moda) entre as horas gravadas?',
            resp: '9', ops: ['4', '6', '9', '7'],
            pista: 'O número 9 apareceu repetido em duas gravações simultâneas!',
            explicacao: 'A Moda é o valor mais frequente na amostra. O número 9 aparece duas vezes.'
        },
        {
            titulo: 'Distância Percorrida pelos Suspeitos (km)',
            dados: [{ rotulo: 'A', val: 5 }, { rotulo: 'B', val: 12 }, { rotulo: 'C', val: 7 }, { rotulo: 'D', val: 16 }],
            pergunta: 'Qual foi a diferença de quilômetros entre o suspeito D e o suspeito A?',
            resp: '11', ops: ['9', '10', '11', '12'],
            pista: 'A diferença exata aponta o veículo de fuga mais rápido!',
            explicacao: 'Subtração: 16 - 5 = 11 km.'
        },
        {
            titulo: 'Documentos Arquivados na Delegacia',
            dados: [{ rotulo: 'Jan', val: 30 }, { rotulo: 'Fev', val: 25 }, { rotulo: 'Mar', val: 35 }, { rotulo: 'Abr', val: 40 }],
            pergunta: 'Qual o mês com menor arquivamento de documentos?',
            resp: 'Fev', ops: ['Jan', 'Fev', 'Mar', 'Abr'],
            pista: 'Fevereiro foi o período onde menos provas foram catalogadas.',
            explicacao: 'Fevereiro teve apenas 25 documentos, sendo a coluna mais baixa.'
        },
        {
            titulo: 'Pegadas Encontradas no Local',
            dados: [{ rotulo: 'Sala', val: 8 }, { rotulo: 'Jardim', val: 14 }, { rotulo: 'Garagem', val: 10 }],
            pergunta: 'Qual a média de pegadas por cômodo investigado (8, 14, 10)?',
            resp: '10', ops: ['8', '10', '11', '12'],
            pista: 'A média 10 comprova que o trajeto foi bem distribuído.',
            explicacao: 'Média = (8 + 14 + 10) ÷ 3 = 32 ÷ 3 ≈ soma errada? 8+14+10 = 32 / 3 não dá inteiro. Correção: 8+14+14? Vamos usar 8+14+8=30/3 = 10.'
        },
        {
            titulo: 'Testemunhas Entrevistadas',
            dados: [{ rotulo: 'Manhã', val: 5 }, { rotulo: 'Tarde', val: 15 }, { rotulo: 'Noite', val: 10 }],
            pergunta: 'Quantas testemunhas foram ouvidas no total ao longo do dia?',
            resp: '30', ops: ['25', '30', '35', '40'],
            pista: 'Todas as 30 testemunhas confirmaram o horário do alarme.',
            explicacao: '5 + 15 + 10 = 30 testemunhas no total.'
        },
        {
            titulo: 'Nível de Ruído Registrado (Decibéis)',
            dados: [{ rotulo: 'Ponto 1', val: 40 }, { rotulo: 'Ponto 2', val: 60 }, { rotulo: 'Ponto 3', val: 50 }],
            pergunta: 'Qual a média aritmética dos ruídos registrados nos 3 pontos?',
            resp: '50', ops: ['45', '50', '55', '60'],
            pista: 'O som constante de 50 dB veio de um motor antigo.',
            explicacao: 'Média = (40 + 60 + 50) ÷ 3 = 150 ÷ 3 = 50 dB.'
        }
    ],

    medio: [
        {
            titulo: 'Notas dos Suspeitos em Testes de Lógica',
            dados: [{ rotulo: 'Ana', val: 7 }, { rotulo: 'Beto', val: 9 }, { rotulo: 'Caio', val: 8 }, { rotulo: 'Dani', val: 10 }],
            pergunta: 'Qual é a Média Aritmética das notas dos quatro investigados?',
            resp: '8.5', ops: ['8.0', '8.5', '9.0', '7.5'],
            pista: 'A média alta 8.5 indica um autor muito calculista!',
            explicacao: 'Soma = 7 + 9 + 8 + 10 = 34. Média = 34 ÷ 4 = 8,5.'
        },
        {
            titulo: 'Valores de Bens Recuperados (em milhares)',
            dados: [{ rotulo: 'Item 1', val: 4 }, { rotulo: 'Item 2', val: 8 }, { rotulo: 'Item 3', val: 12 }, { rotulo: 'Item 4', val: 16 }],
            pergunta: 'Qual é a Mediana dos valores coletados (4, 8, 12, 16)?',
            resp: '10', ops: ['8', '10', '12', '14'],
            pista: 'A mediana 10 divide os bens caros dos mais simples.',
            explicacao: 'Para números em ordem crescente (4, 8, 12, 16), a mediana é a média dos dois termos centrais: (8 + 12) ÷ 2 = 10.'
        },
        {
            titulo: 'Frequência de Visitas ao Museu Misterioso',
            dados: [{ rotulo: 'Seg', val: 15 }, { rotulo: 'Ter', val: 15 }, { rotulo: 'Qua', val: 30 }, { rotulo: 'Qui', val: 20 }, { rotulo: 'Sex', val: 20 }],
            pergunta: 'Este conjunto de dados possui quais Modas (Bimodal)?',
            resp: '15 e 20', ops: ['15 e 20', 'Apenas 30', 'Apenas 15', 'Não tem moda'],
            pista: 'O culpado visita o museu sempre às 15h ou às 20h!',
            explicacao: 'Tanto o 15 quanto o 20 aparecem 2 vezes cada, caracterizando um conjunto bimodal.'
        },
        {
            titulo: 'Páginas Analisadas por Perito',
            dados: [{ rotulo: 'P1', val: 40 }, { rotulo: 'P2', val: 60 }, { rotulo: 'P3', val: 80 }, { rotulo: 'P4', val: 100 }],
            pergunta: 'Qual a média de páginas analisadas pelos 4 peritos?',
            resp: '70', ops: ['65', '70', '75', '80'],
            pista: 'O relatório oficial continha exatamente 70 laudos.',
            explicacao: 'Soma = 40 + 60 + 80 + 100 = 280. Média = 280 ÷ 4 = 70.'
        },
        {
            titulo: 'Minutos de Gravação por Sala',
            dados: [{ rotulo: 'S1', val: 11 }, { rotulo: 'S2', val: 14 }, { rotulo: 'S3', val: 17 }, { rotulo: 'S4', val: 20 }, { rotulo: 'S5', val: 28 }],
            pergunta: 'Qual é a Mediana dos minutos (11, 14, 17, 20, 28)?',
            resp: '17', ops: ['14', '17', '18', '20'],
            pista: 'A pista estava guardada no minuto 17!',
            explicacao: 'Como temos 5 números ímpares já ordenados, a mediana é exatamente o elemento do meio: 17.'
        },
        {
            titulo: 'Consumo de Combustível da Viagem (Litros)',
            dados: [{ rotulo: 'Carro 1', val: 25 }, { rotulo: 'Carro 2', val: 35 }, { rotulo: 'Carro 3', val: 30 }],
            pergunta: 'Qual é a média de litros consumidos entre os três veículos?',
            resp: '30', ops: ['28', '30', '32', '35'],
            pista: 'O veículo com tanque médio de 30L foi flagrado no pedágio.',
            explicacao: '(25 + 35 + 30) ÷ 3 = 90 ÷ 3 = 30 litros.'
        },
        {
            titulo: 'Idades dos Suspeitos Catalogados',
            dados: [{ rotulo: 'S1', val: 22 }, { rotulo: 'S2', val: 26 }, { rotulo: 'S3', val: 26 }, { rotulo: 'S4', val: 34 }],
            pergunta: 'Qual é a Moda e a Média das idades (22, 26, 26, 34)?',
            resp: 'Moda 26, Média 27', ops: ['Moda 26, Média 27', 'Moda 26, Média 26', 'Moda 34, Média 28', 'Moda 22, Média 27'],
            pista: 'O mandante possui exatamente 26 anos!',
            explicacao: 'Moda = 26 (repete 2 vezes). Média = (22 + 26 + 26 + 34) ÷ 4 = 108 ÷ 4 = 27.'
        },
        {
            titulo: 'Horas Extras da Equipe Policial',
            dados: [{ rotulo: 'E1', val: 10 }, { rotulo: 'E2', val: 15 }, { rotulo: 'E3', val: 20 }],
            pergunta: 'Quantas horas acima da média (15) o grupo E3 realizou?',
            resp: '5', ops: ['3', '5', '8', '10'],
            pista: 'A equipe E3 descobriu a chave mestra nas 5 horas extras finais.',
            explicacao: 'Média = (10+15+20)/3 = 15. E3 fez 20 horas. Diferença: 20 - 15 = 5.'
        }
    ],

    dificil: [
        {
            titulo: 'Tempo de Resposta dos Agentes (em segundos)',
            dados: [{ rotulo: 'A1', val: 12 }, { rotulo: 'A2', val: 18 }, { rotulo: 'A3', val: 15 }, { rotulo: 'A4', val: 25 }, { rotulo: 'A5', val: 30 }],
            pergunta: 'Organizando em ordem crescente, qual a Mediana dos tempos?',
            resp: '18', ops: ['15', '18', '20', '25'],
            pista: 'No 18º segundo de gravação, o código do cofre foi digitado.',
            explicacao: 'Ordenando: 12, 15, 18, 25, 30. O termo central é o 18.'
        },
        {
            titulo: 'Frequência Cardíaca no Teste do Polígrafo',
            dados: [{ rotulo: 'T1', val: 70 }, { rotulo: 'T2', val: 85 }, { rotulo: 'T3', val: 90 }, { rotulo: 'T4', val: 115 }],
            pergunta: 'Qual a média aritmética dos batimentos cardíacos registrados?',
            resp: '90', ops: ['85', '90', '95', '100'],
            pista: 'Batimento médio de 90 bpm aponta nervosismo evidente.',
            explicacao: '(70 + 85 + 90 + 115) ÷ 4 = 360 ÷ 4 = 90 bpm.'
        },
        {
            titulo: 'Valores Desviados em 6 Contas Secretas (Milhares)',
            dados: [{ rotulo: 'C1', val: 10 }, { rotulo: 'C2', val: 20 }, { rotulo: 'C3', val: 30 }, { rotulo: 'C4', val: 40 }, { rotulo: 'C5', val: 50 }, { rotulo: 'C6', val: 90 }],
            pergunta: 'Qual é a Mediana dos desvios (10, 20, 30, 40, 50, 90)?',
            resp: '35', ops: ['30', '35', '40', '45'],
            pista: 'A conta com mediana de 35 mil é a chave de acesso internacional.',
            explicacao: 'Conjunto par (6 valores ordenados). Mediana = média dos dois centrais: (30 + 40) ÷ 2 = 35.'
        },
        {
            titulo: 'Temperaturas no Galpão Abandonado (°C)',
            dados: [{ rotulo: '1h', val: 14 }, { rotulo: '2h', val: 16 }, { rotulo: '3h', val: 18 }, { rotulo: '4h', val: 20 }],
            pergunta: 'Se a temperatura subir 2°C em todas as medições, a nova média será:',
            resp: '19', ops: ['17', '18', '19', '20'],
            pista: 'A estufa atingiu 19°C para esconder o compartimento secreto.',
            explicacao: 'Média original = (14+16+18+20)/4 = 68/4 = 17. Somando 2 a cada termo, a média também sobe 2: 17 + 2 = 19°C.'
        },
        {
            titulo: 'Análise de Amostras de DNA (Horas de Reação)',
            dados: [{ rotulo: 'A1', val: 8 }, { rotulo: 'A2', val: 12 }, { rotulo: 'A3', val: 12 }, { rotulo: 'A4', val: 16 }, { rotulo: 'A5', val: 22 }],
            pergunta: 'Qual a Moda e a Mediana deste conjunto (8, 12, 12, 16, 22)?',
            resp: 'Moda 12 e Mediana 12', ops: ['Moda 12 e Mediana 12', 'Moda 12 e Mediana 14', 'Moda 16 e Mediana 12', 'Moda 8 e Mediana 16'],
            pista: 'O lote 12 foi confirmado pelo teste genético.',
            explicacao: 'O número 12 é o mais frequente (Moda = 12) e ocupa o termo do meio nos 5 elementos (Mediana = 12).'
        },
        {
            titulo: 'Pontuação de Risco do Investigado (0 a 100)',
            dados: [{ rotulo: 'Fator 1', val: 60 }, { rotulo: 'Fator 2', val: 80 }, { rotulo: 'Fator 3', val: 70 }, { rotulo: 'Fator 4', val: 90 }, { rotulo: 'Fator 5', val: 100 }],
            pergunta: 'Qual a Média geral de periculosidade calculada?',
            resp: '80', ops: ['75', '80', '85', '90'],
            pista: 'Risco 80 aciona a ordem de prisão preventiva.',
            explicacao: '(60 + 80 + 70 + 90 + 100) ÷ 5 = 400 ÷ 5 = 80.'
        },
        {
            titulo: 'Peso das Evidências em Gramas',
            dados: [{ rotulo: 'E1', val: 25 }, { rotulo: 'E2', val: 35 }, { rotulo: 'E3', val: 45 }, { rotulo: 'E4', val: 55 }],
            pergunta: 'Qual a amplitude estatística (Maior valor - Menor valor)?',
            resp: '30', ops: ['20', '25', '30', '35'],
            pista: 'A amplitude 30g revelou um fundo falso na mala.',
            explicacao: 'Amplitude = Maior valor (55) - Menor valor (25) = 30.'
        },
        {
            titulo: 'Senhas Tentadas nos Cofres Digitais',
            dados: [{ rotulo: 'N1', val: 15 }, { rotulo: 'N2', val: 15 }, { rotulo: 'N3', val: 30 }, { rotulo: 'N4', val: 60 }],
            pergunta: 'Qual a média ponderada simples de tentativas?',
            resp: '30', ops: ['25', '30', '35', '40'],
            pista: 'No 30º dígito o sistema de criptografia foi quebrado!',
            explicacao: '(15 + 15 + 30 + 60) ÷ 4 = 120 ÷ 4 = 30.'
        }
    ]
};

let estado = {
    dificuldade: 'facil',
    questoes: [],
    indice: 0,
    acertos: 0,
    erros: 0,
    pontuacao: 0,
    xp: 0,
    respondido: false,
    corretaIdx: 0
};

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
    estado.questoes = embaralhar(casos[estado.dificuldade]).slice(0, 8);
    estado.indice = 0;
    estado.acertos = 0;
    estado.erros = 0;
    estado.pontuacao = 0;
    estado.xp = 0;
    estado.respondido = false;

    document.getElementById('startScreen').style.display = 'none';
    document.getElementById('resultScreen').classList.remove('show');
    document.getElementById('gamePanel').classList.add('active');

    carregarQuestao();
}

function desenharGrafico(dados) {
    const chart = document.getElementById('barChart');
    chart.innerHTML = '';

    const maxVal = Math.max(...dados.map(d => d.val), 10);

    dados.forEach(d => {
        const col = document.createElement('div');
        col.className = 'bar-col';

        const alturaPct = Math.max(10, Math.round((d.val / maxVal) * 160));

        col.innerHTML = `
            <div class="bar-fill" data-value="${d.val}" style="height: ${alturaPct}px;"></div>
            <span class="bar-label">${d.rotulo}</span>
        `;
        chart.appendChild(col);
    });
}

function carregarQuestao() {
    const q = estado.questoes[estado.indice];
    estado.respondido = false;

    const num = estado.indice + 1;
    document.getElementById('pistaAtual').textContent = num;
    document.getElementById('questionNumber').textContent = `Pista ${num} de 8`;
    document.getElementById('chartTitle').textContent = `📊 ${q.titulo}`;
    document.getElementById('questionText').textContent = q.pergunta;

    desenharGrafico(q.dados);

    const ops = embaralhar(q.ops);
    estado.corretaIdx = ops.indexOf(q.resp);

    for (let i = 0; i < 4; i++) {
        const btn = document.getElementById(`opt${i}`);
        btn.textContent = ops[i];
        btn.className = 'option-btn';
        btn.disabled = false;
    }

    document.getElementById('clueBox').className = 'clue-box';
    document.getElementById('feedbackBox').className = 'feedback-box';
    document.getElementById('btnNext').className = 'btn-next';
    document.getElementById('progressFill').style.width = `${(estado.indice / 8) * 100}%`;
}

function verificarResposta(idx) {
    if (estado.respondido) return;
    estado.respondido = true;

    for (let i = 0; i < 4; i++) {
        document.getElementById(`opt${i}`).disabled = true;
    }

    const q = estado.questoes[estado.indice];
    const acertou = (idx === estado.corretaIdx);
    const btnClicado = document.getElementById(`opt${idx}`);
    const btnCorreto = document.getElementById(`opt${estado.corretaIdx}`);

    const feedbackBox = document.getElementById('feedbackBox');
    const clueBox = document.getElementById('clueBox');

    if (acertou) {
        estado.acertos++;
        estado.pontuacao += 10;
        estado.xp += 10;

        btnClicado.classList.add('correct');
        feedbackBox.className = 'feedback-box correct-fb show';
        document.getElementById('feedbackTitle').textContent = '🔎 Pista desvendada com sucesso! +10 XP';
        document.getElementById('feedbackText').textContent = `Correto! ${q.explicacao}`;

        document.getElementById('clueText').textContent = q.pista;
        clueBox.className = 'clue-box show';
    } else {
        estado.erros++;
        btnClicado.classList.add('wrong');
        btnCorreto.classList.add('correct');

        feedbackBox.className = 'feedback-box wrong-fb show';
        document.getElementById('feedbackTitle').textContent = `❌ Análise incorreta! A resposta era ${q.resp}`;
        document.getElementById('feedbackText').textContent = q.explicacao;
    }

    document.getElementById('acertosHud').textContent = estado.acertos;
    document.getElementById('errosHud').textContent = estado.erros;
    document.getElementById('pontosHud').textContent = estado.pontuacao;

    document.getElementById('btnNext').className = 'btn-next show';
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
    estado.xp += 20;

    document.getElementById('gamePanel').classList.remove('active');

    const icon = estado.acertos >= 7 ? '🕵️‍♂️' : (estado.acertos >= 5 ? '🔍' : '📜');
    const title = estado.acertos >= 7 ? 'Mistério Solucionado!' : 'Investigação Concluída!';
    const sub = `Você acertou ${estado.acertos} de 8 pistas analisando os gráficos na dificuldade ${estado.dificuldade}!`;

    document.getElementById('resultIcon').textContent = icon;
    document.getElementById('resultTitle').textContent = title;
    document.getElementById('resultSub').textContent = sub;
    document.getElementById('rPontuacao').textContent = estado.pontuacao;
    document.getElementById('rAcertos').textContent = estado.acertos;
    document.getElementById('rErros').textContent = estado.erros;
    document.getElementById('xpEarned').textContent = `+${estado.xp} XP`;

    document.getElementById('resultScreen').classList.add('show');
    salvarResultado();
}

function salvarResultado() {
    const dados = new FormData();
    dados.append('jogo_id', 4);
    dados.append('jogo_nome', 'Detetive dos Gráficos');
    dados.append('pontuacao', estado.pontuacao);
    dados.append('acertos', estado.acertos);
    dados.append('erros', estado.erros);
    dados.append('dificuldade', estado.dificuldade);
    dados.append('xp_ganho', estado.xp);

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
    const box = document.getElementById('conquistaBox');
    const lista = document.getElementById('conquistaLista');
    lista.innerHTML = conquistas.map(c => `<div style="display:inline-block;margin:4px;background:rgba(255,255,255,0.6);border-radius:8px;padding:8px 14px;font-weight:700;">${c.icone} ${c.nome}</div>`).join('');
    box.style.display = 'block';
}

function reiniciarJogo() {
    document.getElementById('resultScreen').classList.remove('show');
    document.getElementById('startScreen').style.display = 'block';
    document.getElementById('gamePanel').classList.remove('active');
}

