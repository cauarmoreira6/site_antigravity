<?php
// ============================================================
// MathPlay Solutions — Jogo 4: Detetive dos Gráficos
// ============================================================
require_once '../includes/verificar_login.php';
require_once '../includes/serie_jogos.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detetive dos Gráficos — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
    <link rel="stylesheet" href="/site_antigravity/css/jogos.css">
</head>
<body>
<div class="app-layout">

    <?php require_once '../includes/header.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Detetive dos Gráficos</h1>
                <p>Estatística e Gráficos</p>
            </div>
            <a href="/site_antigravity/jogos.php" class="btn btn-outline btn-sm">← Voltar</a>
        </div>

        <div class="game-wrapper">

            <!-- TELA INICIAL -->
            <div id="startScreen" class="game-start-screen">
                <span class="game-big-icon"></span>
                <h2>Detetive dos Gráficos</h2>
                <p>
                    Um caso precisa ser resolvido! Analise gráficos de colunas, calcule
                    <strong>médias, modas, medianas e totais</strong> para coletar pistas e solucionar o mistério.
                </p>
                <div style="background:rgba(243,156,18,0.08);border-radius:12px;padding:16px;margin-bottom:24px;text-align:left;max-width:420px;margin-left:auto;margin-right:auto;">
                    <p style="font-weight:700;margin-bottom:8px;"> O que você investigará:</p>
                    <p style="font-size:0.9rem;color:#636e72;line-height:1.7;">
                         Leitura e interpretação de gráficos<br>
                         Cálculo de Média Aritmética<br>
                         Identificação de Moda e Mediana<br>
                         Cada resposta certa revela uma <strong>pista da investigação</strong>!<br>
                         São <strong>8 investigações</strong> por caso
                    </p>
                </div>
                <p style="font-weight:700;margin-bottom:12px;">Escolha a dificuldade:</p>
                <div class="difficulty-selector">
                    <button class="diff-btn diff-facil selected" onclick="selecionarDif('facil', this)">Fácil</button>
                    <button class="diff-btn diff-medio"           onclick="selecionarDif('medio', this)">Médio</button>
                    <button class="diff-btn diff-dificil"         onclick="selecionarDif('dificil', this)">Difícil</button>
                </div>
                <button class="btn btn-warning btn-lg" onclick="iniciarJogo()" style="margin-top:24px;background:#f39c12;color:#fff;border:none;border-radius:50px;padding:14px 40px;font-weight:700;cursor:pointer;">
                     Começar Investigação!
                </button>
            </div>

            <!-- PAINEL DO JOGO -->
            <div id="gamePanel" class="game-panel">

                <!-- HUD -->
                <div class="game-hud">
                    <div class="hud-item">
                        <span class="hud-icon"></span>
                        <div>
                            <div class="hud-label">Pista</div>
                            <div class="hud-value"><span id="pistaAtual">1</span>/8</div>
                        </div>
                    </div>
                    <div class="hud-item">
                        <span class="hud-icon"></span>
                        <div>
                            <div class="hud-label">Acertos</div>
                            <div class="hud-value" id="acertosHud" style="color:#2ecc71;">0</div>
                        </div>
                    </div>
                    <div class="hud-item">
                        <span class="hud-icon"></span>
                        <div>
                            <div class="hud-label">Erros</div>
                            <div class="hud-value" id="errosHud" style="color:#e74c3c;">0</div>
                        </div>
                    </div>
                    <div class="hud-item">
                        <span class="hud-icon"></span>
                        <div>
                            <div class="hud-label">Pontos</div>
                            <div class="hud-value" id="pontosHud">0</div>
                        </div>
                    </div>
                    <div class="hud-progress">
                        <div class="hud-label" style="font-size:0.75rem;color:#636e72;">Progresso do Caso</div>
                        <div class="hud-progress-bar">
                            <div class="hud-progress-fill" id="progressFill" style="width:0%;background:linear-gradient(90deg,#f39c12,#e67e22);"></div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico Renderizado em HTML/CSS -->
                <div class="chart-container">
                    <div class="chart-title" id="chartTitle">Gráfico de Dados da Investigação</div>
                    <div class="bar-chart" id="barChart"></div>
                </div>

                <!-- Card de Pergunta -->
                <div class="question-card">
                    <div class="question-number" id="questionNumber">Pista 1 de 8</div>
                    <div class="question-text" id="questionText">Analise o gráfico acima e responda:</div>

                    <!-- Opções -->
                    <div class="options-grid" id="optionsGrid">
                        <button class="option-btn" id="opt0" onclick="verificarResposta(0)"></button>
                        <button class="option-btn" id="opt1" onclick="verificarResposta(1)"></button>
                        <button class="option-btn" id="opt2" onclick="verificarResposta(2)"></button>
                        <button class="option-btn" id="opt3" onclick="verificarResposta(3)"></button>
                    </div>

                    <!-- Pista Encontrada -->
                    <div class="clue-box" id="clueBox">
                        <div class="clue-title"> Nova Pista Descoberta!</div>
                        <div class="clue-text" id="clueText"></div>
                    </div>

                    <!-- Feedback -->
                    <div class="feedback-box" id="feedbackBox">
                        <div class="feedback-title" id="feedbackTitle"></div>
                        <div class="feedback-text"  id="feedbackText"></div>
                    </div>

                    <button class="btn-next" id="btnNext" onclick="proximaQuestao()" style="background:#f39c12;">
                        Avançar no Caso →
                    </button>
                </div>

            </div>

            <!-- RESULTADO -->
            <div id="resultScreen" class="result-screen">
                <h2 id="resultTitle">Caso Solucionado!</h2>
                <p class="result-sub" id="resultSub"></p>
                <div class="result-stats">
                    <div class="result-stat"><div class="r-value" id="rPontuacao">0</div><div class="r-label">Pontuação</div></div>
                    <div class="result-stat"><div class="r-value" style="color:#2ecc71;" id="rAcertos">0</div><div class="r-label">Acertos</div></div>
                    <div class="result-stat"><div class="r-value" style="color:#e74c3c;" id="rErros">0</div><div class="r-label">Erros</div></div>
                </div>
                <div class="result-xp"><div class="xp-earned" id="xpEarned">+0 XP</div><div class="xp-label">Experiência ganha!</div></div>
                <div id="conquistaBox" style="display:none;background:rgba(243,156,18,0.1);border:1px solid rgba(243,156,18,0.3);border-radius:12px;padding:16px;margin-bottom:20px;text-align:center;">
                    <div style="font-weight:700;color:#e67e22;margin-bottom:8px;"> Nova(s) Conquista(s)!</div>
                    <div id="conquistaLista"></div>
                </div>
                <div class="result-buttons">
                    <button class="btn" style="background:#f39c12;color:#fff;border-radius:50px;padding:12px 28px;font-weight:700;cursor:pointer;" onclick="reiniciarJogo()"> Investigar Novamente</button>
                    <a href="/site_antigravity/jogos.php" class="btn btn-outline"> Outros Jogos</a>
                    <a href="/site_antigravity/dashboard.php" class="btn btn-outline"> Dashboard</a>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    const USUARIO_ID = <?= $_SESSION['usuario_id'] ?>;
</script>
<script>const ANO_ESCOLAR = <?= (int)$ano_escolar ?>;</script>
<script src="/site_antigravity/js/serie_jogos.js"></script>
<script src="/site_antigravity/js/jogo4.js"></script>
</body>
</html>
