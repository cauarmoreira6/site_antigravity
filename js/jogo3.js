// ============================================================
// MathPlay Solutions — Jogo 3: Loja MathPlay (jogo3.js)
// Porcentagem, desconto, troco, lucro e juros simples
// ============================================================

const questoes = {
    facil: [
        {
            icone: '👕', nome: 'Camiseta Básica', original: 100.00,
            pergunta: 'Uma camiseta custa R$ 100,00 e está com 20% de desconto. Qual é o valor final a pagar?',
            resp: 80.00, prefixo: 'R$',
            explicacao: '20% de 100 = (20 ÷ 100) × 100 = R$ 20 de desconto. Preço final: 100 - 20 = R$ 80,00.'
        },
        {
            icone: '👟', nome: 'Tênis Esportivo', original: 200.00,
            pergunta: 'Um tênis de R$ 200,00 teve 10% de desconto à vista. Quanto o cliente pagará?',
            resp: 180.00, prefixo: 'R$',
            explicacao: '10% de 200 = R$ 20. Preço com desconto: 200 - 20 = R$ 180,00.'
        },
        {
            icone: '🎒', nome: 'Mochila Escolar', original: 50.00,
            pergunta: 'O cliente comprou uma mochila de R$ 35,00 e pagou com uma nota de R$ 50,00. Qual o valor do troco?',
            resp: 15.00, prefixo: 'R$',
            explicacao: 'Troco = Valor pago - Valor da compra. 50 - 35 = R$ 15,00.'
        },
        {
            icone: '📚', nome: 'Livro de Matemática', original: 60.00,
            pergunta: 'Um livro de R$ 60,00 está com 50% de desconto na promoção. Qual é o novo preço?',
            resp: 30.00, prefixo: 'R$',
            explicacao: '50% é a metade do valor! Metade de 60 = R$ 30,00.'
        },
        {
            icone: '🧢', nome: 'Boné Estiloso', original: 40.00,
            pergunta: 'Um boné custa R$ 40,00 e está com 25% de desconto. Qual o preço final?',
            resp: 30.00, prefixo: 'R$',
            explicacao: '25% de 40 = 40 ÷ 4 = R$ 10 de desconto. Preço final: 40 - 10 = R$ 30,00.'
        },
        {
            icone: '🍫', nome: 'Caixa de Bombom', original: 20.00,
            pergunta: 'A compra deu R$ 12,00 e o cliente entregou uma nota de R$ 20,00. Qual o troco?',
            resp: 8.00, prefixo: 'R$',
            explicacao: 'Troco = 20 - 12 = R$ 8,00.'
        },
        {
            icone: '🖊️', nome: 'Estojo Completo', original: 30.00,
            pergunta: 'Um estojo de R$ 30,00 teve 10% de acréscimo. Qual é o novo valor?',
            resp: 33.00, prefixo: 'R$',
            explicacao: '10% de 30 = R$ 3. Novo preço: 30 + 3 = R$ 33,00.'
        },
        {
            icone: '🎧', nome: 'Fone de Ouvido', original: 80.00,
            pergunta: 'Fone de R$ 80,00 com 25% de desconto. Quanto você economiza?',
            resp: 20.00, prefixo: 'R$',
            explicacao: 'O desconto economizado é 25% de 80 = 80 ÷ 4 = R$ 20,00.'
        },
        {
            icone: '🎮', nome: 'Jogo de Tabuleiro', original: 100.00,
            pergunta: 'O jogo custa R$ 85,00 e o cliente pagou com R$ 100,00. Qual o troco correto?',
            resp: 15.00, prefixo: 'R$',
            explicacao: 'Troco = 100 - 85 = R$ 15,00.'
        },
        {
            icone: '🥤', nome: 'Garrafa Térmica', original: 50.00,
            pergunta: 'Garrafa de R$ 50,00 com 30% de desconto. Qual o valor final?',
            resp: 35.00, prefixo: 'R$',
            explicacao: '30% de 50 = (30/100) × 50 = R$ 15 de desconto. Preço: 50 - 15 = R$ 35,00.'
        }
    ],

    medio: [
        {
            icone: '📱', nome: 'Capa para Celular', original: 80.00,
            pergunta: 'Você comprou por R$ 50,00 e vendeu por R$ 80,00. Qual foi o seu lucro em reais?',
            resp: 30.00, prefixo: 'R$',
            explicacao: 'Lucro = Preço de venda - Preço de custo = 80 - 50 = R$ 30,00.'
        },
        {
            icone: '⌚', nome: 'Relógio Digital', original: 150.00,
            pergunta: 'Um relógio de R$ 150,00 está com 15% de desconto. Qual o preço final?',
            resp: 127.50, prefixo: 'R$',
            explicacao: '15% de 150 = 0,15 × 150 = R$ 22,50. Preço final: 150 - 22,50 = R$ 127,50.'
        },
        {
            icone: '🛹', nome: 'Skate Radical', original: 250.00,
            pergunta: 'Um skate de R$ 200,00 teve um aumento de 15%. Qual o novo preço?',
            resp: 230.00, prefixo: 'R$',
            explicacao: '15% de 200 = R$ 30,00. Novo preço = 200 + 30 = R$ 230,00.'
        },
        {
            icone: '🎸', nome: 'Violão Acústico', original: 400.00,
            pergunta: 'Um violão de R$ 400,00 teve desconto de 35%. Qual o valor a pagar?',
            resp: 260.00, prefixo: 'R$',
            explicacao: '35% de 400 = 0,35 × 400 = R$ 140 de desconto. Preço final: 400 - 140 = R$ 260,00.'
        },
        {
            icone: '🚲', nome: 'Bicicleta Urbana', original: 500.00,
            pergunta: 'A compra deu R$ 342,00. O cliente deu 4 notas de R$ 100,00 (R$ 400). Quanto dar de troco?',
            resp: 58.00, prefixo: 'R$',
            explicacao: 'Troco = 400 - 342 = R$ 58,00.'
        },
        {
            icone: '💻', nome: 'Mouse Gamer', original: 120.00,
            pergunta: 'Custou R$ 80,00 e foi vendido com 25% de lucro sobre o custo. Qual o preço de venda?',
            resp: 100.00, prefixo: 'R$',
            explicacao: 'Lucro = 25% de 80 = R$ 20. Preço de venda = 80 + 20 = R$ 100,00.'
        },
        {
            icone: '🖨️', nome: 'Calculadora Científica', original: 90.00,
            pergunta: 'Uma calculadora de R$ 90,00 com 20% de desconto sai por quanto?',
            resp: 72.00, prefixo: 'R$',
            explicacao: '20% de 90 = R$ 18. Preço: 90 - 18 = R$ 72,00.'
        },
        {
            icone: '⚽', nome: 'Bola Oficial', original: 120.00,
            pergunta: 'A bola de R$ 120,00 subiu 10% no Natal. Qual o preço atualizado?',
            resp: 132.00, prefixo: 'R$',
            explicacao: '10% de 120 = R$ 12. Novo preço = 120 + 12 = R$ 132,00.'
        },
        {
            icone: '👓', nome: 'Óculos de Sol', original: 180.00,
            pergunta: 'Preço de R$ 180,00 com desconto de R$ 45,00. Qual o valor pago?',
            resp: 135.00, prefixo: 'R$',
            explicacao: '180 - 45 = R$ 135,00.'
        },
        {
            icone: '🎒', nome: 'Mala de Viagem', original: 300.00,
            pergunta: 'Mala de R$ 300,00 com desconto de 40%. Quanto o comprador pagará?',
            resp: 180.00, prefixo: 'R$',
            explicacao: '40% de 300 = R$ 120 de desconto. Valor pago = 300 - 120 = R$ 180,00.'
        }
    ],

    dificil: [
        {
            icone: '📺', nome: 'Smart TV', original: 1000.00,
            pergunta: 'Um empréstimo de R$ 1.000,00 a juros simples de 3% ao mês durante 2 meses. Qual o valor total dos juros (J = C × i × t)?',
            resp: 60.00, prefixo: 'R$',
            explicacao: 'J = C × i × t = 1000 × 0,03 × 2 = R$ 60,00 de juros.'
        },
        {
            icone: '💻', nome: 'Notebook Estudante', original: 2000.00,
            pergunta: 'Compra parcelada de R$ 2.000,00 com acréscimo total de 8% de juros. Qual será o montante final pago?',
            resp: 2160.00, prefixo: 'R$',
            explicacao: '8% de 2000 = 160. Montante = 2000 + 160 = R$ 2.160,00.'
        },
        {
            icone: '📱', nome: 'Smartphone', original: 1200.00,
            pergunta: 'Um produto de R$ 1.200,00 teve 10% de desconto e depois mais 5% de desconto sobre o novo valor. Qual o valor final pago?',
            resp: 1026.00, prefixo: 'R$',
            explicacao: '1º desconto de 10%: 1200 - 120 = 1080. 2º desconto de 5% sobre 1080 = 54. Total final: 1080 - 54 = R$ 1.026,00.'
        },
        {
            icone: '🎮', nome: 'Console de Games', original: 1500.00,
            pergunta: 'Você comprou o console por R$ 1.000,00 e vendeu por R$ 1.500,00. Qual a taxa percentual de lucro sobre o custo (em %)?',
            resp: 50.00, prefixo: '%',
            explicacao: 'Lucro = 500. Percentual = (500 ÷ 1000) × 100 = 50%.'
        },
        {
            icone: '🛴', nome: 'Patinete Elétrico', original: 800.00,
            pergunta: 'Aplicação de R$ 800,00 a juros simples de 5% ao mês durante 3 meses. Qual o rendimento (juros) gerado?',
            resp: 120.00, prefixo: 'R$',
            explicacao: 'J = 800 × 0,05 × 3 = R$ 120,00 de juros.'
        },
        {
            icone: '🖨️', nome: 'Impressora Laser', original: 600.00,
            pergunta: 'Preço de tabela R$ 600,00. À vista tem 12% de desconto. Qual o valor à vista?',
            resp: 528.00, prefixo: 'R$',
            explicacao: '12% de 600 = R$ 72 de desconto. 600 - 72 = R$ 528,00.'
        },
        {
            icone: '📷', nome: 'Câmera Digital', original: 900.00,
            pergunta: 'Custava R$ 800,00 e foi para R$ 900,00. Qual foi a porcentagem de aumento (em %)?',
            resp: 12.50, prefixo: '%',
            explicacao: 'Aumento de 100 sobre 800: (100 ÷ 800) × 100 = 12,5%.'
        },
        {
            icone: '🚲', nome: 'Bicicleta de Trilha', original: 1400.00,
            pergunta: 'Comprei por R$ 1.400,00 com entrada de R$ 400,00 e o restante em 5 parcelas iguais sem juros. Qual o valor de cada parcela?',
            resp: 200.00, prefixo: 'R$',
            explicacao: 'Restante = 1400 - 400 = 1000. Parcela = 1000 ÷ 5 = R$ 200,00.'
        },
        {
            icone: '🖥️', nome: 'Monitor UltraWide', original: 1100.00,
            pergunta: 'Juros simples: R$ 500,00 aplicados a 2% ao mês por 5 meses geram qual total (montante final)?',
            resp: 550.00, prefixo: 'R$',
            explicacao: 'J = 500 × 0,02 × 5 = 50. Montante = 500 + 50 = R$ 550,00.'
        },
        {
            icone: '🔊', nome: 'Caixa de Som Portátil', original: 450.00,
            pergunta: 'Produto de R$ 450,00 com 18% de desconto. Qual o preço com desconto?',
            resp: 369.00, prefixo: 'R$',
            explicacao: '18% de 450 = 81 de desconto. Preço final: 450 - 81 = R$ 369,00.'
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
    respondido: false
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
    estado.questoes = embaralhar(questoes[estado.dificuldade]).slice(0, 10);
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

function carregarQuestao() {
    const q = estado.questoes[estado.indice];
    estado.respondido = false;

    const num = estado.indice + 1;
    document.getElementById('vendaAtual').textContent = num;
    document.getElementById('questionNumber').textContent = `Venda ${num} de 10`;
    document.getElementById('questionText').textContent = q.pergunta;

    document.getElementById('productIcon').textContent = q.icone;
    document.getElementById('productName').textContent = q.nome;
    document.getElementById('originalPrice').textContent = `Preço: R$ ${q.original.toFixed(2)}`;

    const prefixoEl = document.getElementById('prefixo');
    if (prefixoEl) prefixoEl.textContent = q.prefixo || 'R$';

    const input = document.getElementById('answerInput');
    input.value = '';
    input.disabled = false;
    input.style.borderColor = '#e0e0e0';
    document.getElementById('btnVerificar').disabled = false;

    document.getElementById('feedbackBox').className = 'feedback-box';
    document.getElementById('btnNext').className = 'btn-next';
    document.getElementById('progressFill').style.width = `${(estado.indice / 10) * 100}%`;

    input.focus();
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !estado.respondido && document.getElementById('gamePanel').classList.contains('active')) {
        verificarResposta();
    }
});

function verificarResposta() {
    if (estado.respondido) return;

    const input = document.getElementById('answerInput');
    const valor = parseFloat(input.value.replace(',', '.'));

    if (input.value.trim() === '' || isNaN(valor)) {
        alert('⚠️ Digite um valor numérico para confirmar a resposta!');
        input.focus();
        return;
    }

    estado.respondido = true;
    input.disabled = true;
    document.getElementById('btnVerificar').disabled = true;

    const q = estado.questoes[estado.indice];
    const acertou = Math.abs(valor - q.resp) < 0.1;
    const feedbackBox = document.getElementById('feedbackBox');

    if (acertou) {
        estado.acertos++;
        estado.pontuacao += 10;
        estado.xp += 10;

        input.style.borderColor = '#2ecc71';
        feedbackBox.className = 'feedback-box correct-fb show';
        document.getElementById('feedbackTitle').textContent = '✅ Resposta exata! +10 XP';
        document.getElementById('feedbackText').textContent = `Muito bem! ${q.explicacao}`;
    } else {
        estado.erros++;
        input.style.borderColor = '#e74c3c';
        feedbackBox.className = 'feedback-box wrong-fb show';
        document.getElementById('feedbackTitle').textContent = `❌ Resposta incorreta! O valor correto é ${q.prefixo} ${q.resp.toFixed(2)}`;
        document.getElementById('feedbackText').textContent = q.explicacao;
    }

    document.getElementById('acertosHud').textContent = estado.acertos;
    document.getElementById('errosHud').textContent = estado.erros;
    document.getElementById('pontosHud').textContent = estado.pontuacao;

    document.getElementById('btnNext').className = 'btn-next show';
}

function proximaQuestao() {
    estado.indice++;
    if (estado.indice >= 10) {
        encerrarJogo();
    } else {
        carregarQuestao();
    }
}

function encerrarJogo() {
    estado.xp += 20; // Bônus de finalização

    document.getElementById('gamePanel').classList.remove('active');

    const icon = estado.acertos >= 8 ? '🎉' : (estado.acertos >= 5 ? '🛒' : '📚');
    const title = estado.acertos >= 8 ? 'Excelente Administrador!' : 'Dia de Vendas Concluído!';
    const sub = `Você acertou ${estado.acertos} de 10 perguntas de finanças na dificuldade ${estado.dificuldade}!`;

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
    dados.append('jogo_id', 3);
    dados.append('jogo_nome', 'Loja MathPlay');
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

