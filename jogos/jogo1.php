<?php
// ============================================================
// MathPlay Solutions — Jogo 1: Batalha dos Inteiros
// ============================================================
require_once '../includes/verificar_login.php';
require_once '../includes/serie_jogos.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batalha dos Inteiros — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
    <link rel="stylesheet" href="/site_antigravity/css/jogos.css">
</head>
<body>
<div class="app-layout">

    <?php require_once '../includes/header.php'; ?>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Batalha dos Inteiros</h1>
                <p>Números Inteiros</p>
            </div>
            <a href="/site_antigravity/jogos.php" class="btn btn-outline btn-sm">← Voltar</a>
        </div>

        <div class="game-wrapper">

            <!-- ============ TELA INICIAL ============ -->
            <div id="startScreen" class="game-start-screen">
                <span class="game-big-icon"></span>
                <h2>Batalha dos Inteiros</h2>
                <p>
                    Você é um guerreiro matemático! Enfrente inimigos resolvendo operações
                    com <strong>números positivos e negativos</strong>. Cada resposta correta
                    dá dano ao inimigo. Vença a batalha respondendo corretamente!
                </p>

                <div style="background:rgba(108,99,255,0.08);border-radius:12px;padding:16px;margin-bottom:24px;text-align:left;max-width:400px;margin-left:auto;margin-right:auto;">
                    <p style="font-weight:700;margin-bottom:8px;"> Como jogar:</p>
                    <p style="font-size:0.9rem;color:#636e72;line-height:1.7;">
                         Cada acerto = <strong>-20 HP</strong> do inimigo<br>
                         Cada acerto = <strong>+10 XP</strong> para você<br>
                         3 acertos seguidos = <strong>+5 XP bônus</strong><br>
                         Cada erro = <strong>+5 HP</strong> recuperado pelo inimigo<br>
                         São <strong>10 questões</strong> por partida
                    </p>
                </div>

                <p style="font-weight:700;margin-bottom:12px;">Escolha a dificuldade:</p>
                <div class="difficulty-selector">
                    <button class="diff-btn diff-facil selected" onclick="selecionarDif('facil', this)">Fácil</button>
                    <button class="diff-btn diff-medio"           onclick="selecionarDif('medio', this)">Médio</button>
                    <button class="diff-btn diff-dificil"         onclick="selecionarDif('dificil', this)">Difícil</button>
                </div>

                <button class="btn btn-primary btn-lg" onclick="iniciarJogo()" style="margin-top:24px;">
                     Iniciar Batalha!
                </button>
            </div>

            <!-- ============ PAINEL DO JOGO ============ -->
            <div id="gamePanel" class="game-panel">

                <!-- Inimigo e barra de HP -->
                <div class="enemy-section" id="enemySection">
                    <div class="enemy-avatar" id="enemyAvatar"></div>
                    <div class="enemy-info">
                        <div class="enemy-name" id="enemyName">Monstro dos Números</div>
                        <div class="hp-bar-wrap">
                            <div class="hp-bar-fill" id="hpBar" style="width:100%"></div>
                        </div>
                        <div class="hp-text" id="hpText">HP: 100 / 100</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:0.75rem;color:#636e72;">Sua pontuação</div>
                        <div style="font-size:1.8rem;font-weight:900;color:#6c63ff;" id="scorePlacar">0</div>
                    </div>
                </div>

                <!-- HUD do jogo -->
                <div class="game-hud">
                    <div class="hud-item">
                        <span class="hud-icon"></span>
                        <div>
                            <div class="hud-label">Questão</div>
                            <div class="hud-value"><span id="questaoAtual">1</span>/10</div>
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
                            <div class="hud-label">Sequência</div>
                            <div class="hud-value" id="sequenciaHud" style="color:#f39c12;">0</div>
                        </div>
                    </div>
                    <div class="hud-progress">
                        <div class="hud-label" style="font-size:0.75rem;color:#636e72;">Progresso</div>
                        <div class="hud-progress-bar">
                            <div class="hud-progress-fill" id="progressFill" style="width:0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Carta de pergunta -->
                <div class="question-card">
                    <div class="question-number" id="questionNumber">Questão 1 de 10</div>
                    <div class="question-text" id="questionText">Qual é o resultado de:</div>
                    <div class="math-expression" id="mathExpr">-8 + 15 = ?</div>

                    <!-- Opções de resposta (4 alternativas) -->
                    <div class="options-grid" id="optionsGrid">
                        <button class="option-btn" id="opt0" onclick="verificarResposta(0)"></button>
                        <button class="option-btn" id="opt1" onclick="verificarResposta(1)"></button>
                        <button class="option-btn" id="opt2" onclick="verificarResposta(2)"></button>
                        <button class="option-btn" id="opt3" onclick="verificarResposta(3)"></button>
                    </div>

                    <!-- Feedback de resposta -->
                    <div class="feedback-box" id="feedbackBox">
                        <div class="feedback-title" id="feedbackTitle"></div>
                        <div class="feedback-text"  id="feedbackText"></div>
                    </div>

                    <button class="btn-next" id="btnNext" onclick="proximaQuestao()">
                        Próxima Questão →
                    </button>
                </div>

            </div>

            <!-- ============ TELA DE RESULTADO ============ -->
            <div id="resultScreen" class="result-screen">
                <h2 id="resultTitle">Batalha Concluída!</h2>
                <p class="result-sub" id="resultSub">Você lutou muito bem!</p>

                <div class="result-stats">
                    <div class="result-stat">
                        <div class="r-value" id="rPontuacao">0</div>
                        <div class="r-label">Pontuação</div>
                    </div>
                    <div class="result-stat">
                        <div class="r-value" style="color:#2ecc71;" id="rAcertos">0</div>
                        <div class="r-label">Acertos</div>
                    </div>
                    <div class="result-stat">
                        <div class="r-value" style="color:#e74c3c;" id="rErros">0</div>
                        <div class="r-label">Erros</div>
                    </div>
                </div>

                <div class="result-xp" id="resultXp">
                    <div class="xp-earned" id="xpEarned">+0 XP</div>
                    <div class="xp-label">Experiência ganha!</div>
                </div>

                <div id="conquistaBox" style="display:none;background:rgba(243,156,18,0.1);border:1px solid rgba(243,156,18,0.3);border-radius:12px;padding:16px;margin-bottom:20px;text-align:center;">
                    <div style="font-weight:700;color:#e67e22;margin-bottom:8px;"> Nova(s) Conquista(s)!</div>
                    <div id="conquistaLista"></div>
                </div>

                <div class="result-buttons">
                    <button class="btn btn-primary" onclick="reiniciarJogo()"> Jogar Novamente</button>
                    <a href="/site_antigravity/jogos.php" class="btn btn-outline"> Outros Jogos</a>
                    <a href="/site_antigravity/dashboard.php" class="btn btn-outline"> Dashboard</a>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- Dados do usuário para o JavaScript (injetados pelo PHP) -->
<script>
    // Passa o ID do usuário logado para o JavaScript
    const USUARIO_ID = <?= $_SESSION['usuario_id'] ?>;
    const ANO_ESCOLAR = <?= (int)$ano_escolar ?>;
</script>
<script src="/site_antigravity/js/serie_jogos.js"></script>
<script src="/site_antigravity/js/jogo1.js"></script>
</body>
</html>
