<?php
// ============================================================
// MathPlay Solutions — Jogo 2: Cofre das Equações
// ============================================================
require_once '../includes/verificar_login.php';
require_once '../includes/serie_jogos.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cofre das Equações — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
    <link rel="stylesheet" href="/site_antigravity/css/jogos.css">
</head>
<body>
<div class="app-layout">

    <?php require_once '../includes/header.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>Cofre das Equações</h1>
                <p>Equações de 1º Grau</p>
            </div>
            <a href="/site_antigravity/jogos.php" class="btn btn-outline btn-sm">← Voltar</a>
        </div>

        <div class="game-wrapper">

            <!-- TELA INICIAL -->
            <div id="startScreen" class="game-start-screen">
                <span class="game-big-icon"></span>
                <h2>Cofre das Equações</h2>
                <p>
                    Você é um caçador de tesouros! Para abrir cada cofre, você precisa
                    resolver a equação e descobrir o valor de <strong>X</strong>.
                    Cada cofre aberto revela uma recompensa!
                </p>
                <div style="background:rgba(255,107,53,0.08);border-radius:12px;padding:16px;margin-bottom:24px;text-align:left;max-width:400px;margin-left:auto;margin-right:auto;">
                    <p style="font-weight:700;margin-bottom:8px;"> Como jogar:</p>
                    <p style="font-size:0.9rem;color:#636e72;line-height:1.7;">
                         Resolva a equação e encontre o valor de X<br>
                         Digite apenas o número (ex: 5)<br>
                         Cada cofre aberto = <strong>+10 XP</strong><br>
                         Abrir todos os cofres = <strong>+20 XP bônus</strong><br>
                         São <strong>8 cofres</strong> por partida
                    </p>
                </div>
                <p style="font-weight:700;margin-bottom:12px;">Escolha a dificuldade:</p>
                <div class="difficulty-selector">
                    <button class="diff-btn diff-facil selected" onclick="selecionarDif('facil', this)">Fácil</button>
                    <button class="diff-btn diff-medio"           onclick="selecionarDif('medio', this)">Médio</button>
                    <button class="diff-btn diff-dificil"         onclick="selecionarDif('dificil', this)">Difícil</button>
                </div>
                <button class="btn btn-accent btn-lg" onclick="iniciarJogo()" style="margin-top:24px;">
                     Abrir os Cofres!
                </button>
            </div>

            <!-- PAINEL DO JOGO -->
            <div id="gamePanel" class="game-panel">

                <!-- Cofres visuais -->
                <div class="card" style="text-align:center;">
                    <div class="card-title"> Cofres da Missão</div>
                    <div class="safes-row" id="safesRow"></div>
                </div>

                <!-- HUD -->
                <div class="game-hud">
                    <div class="hud-item">
                        <span class="hud-icon"></span>
                        <div>
                            <div class="hud-label">Cofre</div>
                            <div class="hud-value"><span id="cofreAtual">1</span>/8</div>
                        </div>
                    </div>
                    <div class="hud-item">
                        <span class="hud-icon"></span>
                        <div>
                            <div class="hud-label">Abertos</div>
                            <div class="hud-value" id="abertosHud" style="color:#2ecc71;">0</div>
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
                </div>

                <!-- Questão -->
                <div class="question-card">
                    <div class="question-number" id="questionNumber">Cofre 1 de 8</div>
                    <p class="question-text">Para abrir este cofre, descubra o valor de <strong>X</strong>:</p>
                    <div class="math-expression" id="mathExpr">2x + 4 = 10</div>

                    <!-- Input de resposta -->
                    <div class="answer-input-wrap">
                        <span style="font-size:1.2rem;font-weight:700;">X =</span>
                        <input type="number" id="answerInput" class="answer-input" placeholder="?" step="any">
                        <button class="btn btn-accent" onclick="verificarResposta()" id="btnVerificar">
                            Abrir
                        </button>
                    </div>

                    <!-- Feedback -->
                    <div class="feedback-box" id="feedbackBox">
                        <div class="feedback-title" id="feedbackTitle"></div>
                        <div class="feedback-text"  id="feedbackText"></div>
                    </div>

                    <button class="btn-next" id="btnNext" onclick="proximaQuestao()" style="background:#ff6b35;">
                        Próximo Cofre →
                    </button>
                </div>

            </div>

            <!-- RESULTADO -->
            <div id="resultScreen" class="result-screen">
                <h2 id="resultTitle">Missão Concluída!</h2>
                <p class="result-sub" id="resultSub"></p>
                <div class="result-stats">
                    <div class="result-stat">
                        <div class="r-value" id="rPontuacao">0</div>
                        <div class="r-label">Pontuação</div>
                    </div>
                    <div class="result-stat">
                        <div class="r-value" style="color:#2ecc71;" id="rAbertos">0</div>
                        <div class="r-label">Cofres Abertos</div>
                    </div>
                    <div class="result-stat">
                        <div class="r-value" style="color:#e74c3c;" id="rErros">0</div>
                        <div class="r-label">Erros</div>
                    </div>
                </div>
                <div class="result-xp">
                    <div class="xp-earned" id="xpEarned">+0 XP</div>
                    <div class="xp-label">Experiência ganha!</div>
                </div>
                <div id="conquistaBox" style="display:none;background:rgba(243,156,18,0.1);border:1px solid rgba(243,156,18,0.3);border-radius:12px;padding:16px;margin-bottom:20px;text-align:center;">
                    <div style="font-weight:700;color:#e67e22;margin-bottom:8px;"> Nova(s) Conquista(s)!</div>
                    <div id="conquistaLista"></div>
                </div>
                <div class="result-buttons">
                    <button class="btn btn-accent" onclick="reiniciarJogo()"> Jogar Novamente</button>
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
<script src="/site_antigravity/js/jogo2.js"></script>
</body>
</html>
