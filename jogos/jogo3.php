<?php
// ============================================================
// MathPlay Solutions — Jogo 3: Loja MathPlay
// ============================================================
require_once '../includes/verificar_login.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja MathPlay — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
    <link rel="stylesheet" href="/site_antigravity/css/jogos.css">
</head>
<body>
<div class="app-layout">

    <?php require_once '../includes/header.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>🛒 Loja MathPlay</h1>
                <p>Gerencie a loja resolvendo problemas de porcentagem e finanças!</p>
            </div>
            <a href="/site_antigravity/jogos.php" class="btn btn-outline btn-sm">← Voltar</a>
        </div>

        <div class="game-wrapper">

            <!-- TELA INICIAL -->
            <div id="startScreen" class="game-start-screen">
                <span class="game-big-icon">🛒</span>
                <h2>Loja MathPlay</h2>
                <p>
                    Você é o dono de uma loja! Resolva problemas de
                    <strong>desconto, troco, lucro e porcentagem</strong>
                    para atender seus clientes corretamente.
                </p>
                <div style="background:rgba(46,204,113,0.08);border-radius:12px;padding:16px;margin-bottom:24px;text-align:left;max-width:400px;margin-left:auto;margin-right:auto;">
                    <p style="font-weight:700;margin-bottom:8px;">📖 Temas abordados:</p>
                    <p style="font-size:0.9rem;color:#636e72;line-height:1.8;">
                        🏷️ Cálculo de desconto<br>
                        💵 Cálculo de troco<br>
                        📈 Porcentagem de aumento<br>
                        💰 Lucro e prejuízo<br>
                        🏦 Juros simples (difícil)
                    </p>
                </div>
                <p style="font-weight:700;margin-bottom:12px;">Escolha a dificuldade:</p>
                <div class="difficulty-selector">
                    <button class="diff-btn diff-facil selected" onclick="selecionarDif('facil', this)">🟢 Fácil</button>
                    <button class="diff-btn diff-medio"           onclick="selecionarDif('medio', this)">🟡 Médio</button>
                    <button class="diff-btn diff-dificil"         onclick="selecionarDif('dificil', this)">🔴 Difícil</button>
                </div>
                <button class="btn btn-success btn-lg" onclick="iniciarJogo()" style="margin-top:24px;background:#2ecc71;border-radius:50px;color:#fff;padding:14px 40px;font-weight:700;font-size:1rem;border:none;cursor:pointer;">
                    🛒 Abrir a Loja!
                </button>
            </div>

            <!-- PAINEL DO JOGO -->
            <div id="gamePanel" class="game-panel">

                <!-- HUD -->
                <div class="game-hud">
                    <div class="hud-item">
                        <span class="hud-icon">🛒</span>
                        <div>
                            <div class="hud-label">Venda</div>
                            <div class="hud-value"><span id="vendaAtual">1</span>/10</div>
                        </div>
                    </div>
                    <div class="hud-item">
                        <span class="hud-icon">✅</span>
                        <div>
                            <div class="hud-label">Acertos</div>
                            <div class="hud-value" id="acertosHud" style="color:#2ecc71;">0</div>
                        </div>
                    </div>
                    <div class="hud-item">
                        <span class="hud-icon">❌</span>
                        <div>
                            <div class="hud-label">Erros</div>
                            <div class="hud-value" id="errosHud" style="color:#e74c3c;">0</div>
                        </div>
                    </div>
                    <div class="hud-item">
                        <span class="hud-icon">💰</span>
                        <div>
                            <div class="hud-label">Pontos</div>
                            <div class="hud-value" id="pontosHud">0</div>
                        </div>
                    </div>
                    <div class="hud-progress">
                        <div class="hud-label" style="font-size:0.75rem;color:#636e72;">Progresso</div>
                        <div class="hud-progress-bar">
                            <div class="hud-progress-fill" id="progressFill" style="width:0%;background:linear-gradient(90deg,#2ecc71,#27ae60);"></div>
                        </div>
                    </div>
                </div>

                <!-- Cenário da venda -->
                <div class="store-item" id="storeItem">
                    <div class="product-icon" id="productIcon">👕</div>
                    <div class="product-info">
                        <h4 id="productName">Camiseta</h4>
                        <p  id="productDesc">Produto em destaque</p>
                    </div>
                    <div class="store-price">
                        <div class="original" id="originalPrice">R$ 100,00</div>
                        <div class="discounted" id="discountedPrice" style="display:none;"></div>
                    </div>
                </div>

                <!-- Questão -->
                <div class="question-card">
                    <div class="question-number" id="questionNumber">Venda 1 de 10</div>
                    <div class="question-text" id="questionText"></div>

                    <!-- Input de resposta -->
                    <div class="answer-input-wrap">
                        <span style="font-size:1.1rem;font-weight:700;" id="prefixo">R$</span>
                        <input type="number" id="answerInput" class="answer-input" placeholder="0,00" step="0.01" min="0">
                        <button class="btn btn-success" onclick="verificarResposta()" id="btnVerificar"
                                style="background:#2ecc71;border:none;border-radius:50px;color:#fff;padding:12px 24px;font-weight:700;cursor:pointer;">
                            ✅ Confirmar
                        </button>
                    </div>

                    <!-- Feedback -->
                    <div class="feedback-box" id="feedbackBox">
                        <div class="feedback-title" id="feedbackTitle"></div>
                        <div class="feedback-text"  id="feedbackText"></div>
                    </div>

                    <button class="btn-next" id="btnNext" onclick="proximaQuestao()" style="background:#2ecc71;">
                        Próxima Venda →
                    </button>
                </div>

            </div>

            <!-- RESULTADO -->
            <div id="resultScreen" class="result-screen">
                <span class="result-icon" id="resultIcon">🏆</span>
                <h2 id="resultTitle">Loja Fechada!</h2>
                <p class="result-sub" id="resultSub"></p>
                <div class="result-stats">
                    <div class="result-stat"><div class="r-value" id="rPontuacao">0</div><div class="r-label">Pontuação</div></div>
                    <div class="result-stat"><div class="r-value" style="color:#2ecc71;" id="rAcertos">0</div><div class="r-label">Acertos</div></div>
                    <div class="result-stat"><div class="r-value" style="color:#e74c3c;" id="rErros">0</div><div class="r-label">Erros</div></div>
                </div>
                <div class="result-xp"><div class="xp-earned" id="xpEarned">+0 XP</div><div class="xp-label">Experiência ganha!</div></div>
                <div id="conquistaBox" style="display:none;background:rgba(243,156,18,0.1);border:1px solid rgba(243,156,18,0.3);border-radius:12px;padding:16px;margin-bottom:20px;text-align:center;">
                    <div style="font-weight:700;color:#e67e22;margin-bottom:8px;">🎉 Nova(s) Conquista(s)!</div>
                    <div id="conquistaLista"></div>
                </div>
                <div class="result-buttons">
                    <button class="btn" style="background:#2ecc71;color:#fff;border-radius:50px;padding:12px 28px;font-weight:700;cursor:pointer;" onclick="reiniciarJogo()">🔄 Jogar Novamente</button>
                    <a href="/site_antigravity/jogos.php" class="btn btn-outline">🎮 Outros Jogos</a>
                    <a href="/site_antigravity/dashboard.php" class="btn btn-outline">🏠 Dashboard</a>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    const USUARIO_ID = <?= $_SESSION['usuario_id'] ?>;
</script>
<script src="/site_antigravity/js/jogo3.js"></script>
</body>
</html>

