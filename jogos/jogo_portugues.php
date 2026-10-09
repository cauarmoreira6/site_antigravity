<?php
require_once '../includes/verificar_login.php';
require_once '../includes/serie_jogos.php';

$jogos_portugues = [
    5 => [
        'nome' => 'O Revisor de Notícias',
        'tema' => 'Concordância Verbal e Nominal',
        'chamada' => 'A edição está quase no ar! Revise manchetes e trechos de reportagem para corrigir concordância verbal e nominal antes que sejam publicados.',
        'acao' => 'Revisar a edição',
    ],
    6 => [
        'nome' => 'A Batalha das Metáforas',
        'tema' => 'Figuras de Linguagem',
        'chamada' => 'Entre no duelo poético: identifique o recurso expressivo escondido em cada frase para carregar sua carta e vencer a rodada.',
        'acao' => 'Entrar no duelo',
    ],
    7 => [
        'nome' => 'A Fábrica de Histórias',
        'tema' => 'Coesão e Coerência',
        'chamada' => 'As histórias da fábrica perderam suas ligações! Escolha conectivos e organize ideias para reconstruir narrativas claras e coerentes.',
        'acao' => 'Ligar as ideias',
    ],
    8 => [
        'nome' => 'A Montagem de Robôs',
        'tema' => 'Sujeito e Predicado',
        'chamada' => 'Cada oração é um projeto de robô. Encontre quem pratica ou sofre a ação e o que se declara sobre ele para ativar cada máquina.',
        'acao' => 'Montar o robô',
    ],
];

$jogo = $jogos_portugues[$jogo_portugues_id] ?? null;
if ($jogo === null) {
    http_response_code(404);
    exit('Jogo não encontrado.');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($jogo['nome']) ?> — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
    <link rel="stylesheet" href="/site_antigravity/css/jogos.css">
</head>
<body>
<div class="app-layout">
    <?php require_once '../includes/header.php'; ?>
    <main class="main-content">
        <div class="topbar">
            <div>
                <h1><?= htmlspecialchars($jogo['nome']) ?></h1>
                <p><?= htmlspecialchars($jogo['tema']) ?></p>
                <p>Dificuldade adaptada para o <?= htmlspecialchars($serie_escolar) ?></p>
            </div>
            <a href="/site_antigravity/jogos.php" class="btn btn-outline btn-sm">← Voltar aos jogos</a>
        </div>

        <div class="game-wrapper portuguese-game">
            <section id="startScreen" class="game-start-screen">
                <h2><?= htmlspecialchars($jogo['nome']) ?></h2>
                <p><?= htmlspecialchars($jogo['chamada']) ?></p>
                <div class="portuguese-brief">
                    <strong>Missão de hoje</strong>
                    <span>Resolva 6 desafios. Depois de cada resposta, veja a explicação e aprenda com a revisão.</span>
                    <span>Acertos rendem 10 pontos e 10 XP; ao concluir, você recebe 20 XP bônus.</span>
                </div>
                <p class="difficulty-prompt">Escolha a dificuldade:</p>
                <div class="difficulty-selector">
                    <button class="diff-btn diff-facil selected" onclick="selecionarDif('facil', this)">Fácil</button>
                    <button class="diff-btn diff-medio" onclick="selecionarDif('medio', this)">Médio</button>
                    <button class="diff-btn diff-dificil" onclick="selecionarDif('dificil', this)">Difícil</button>
                </div>
                <button class="btn btn-primary btn-lg" onclick="iniciarJogo()"><?= htmlspecialchars($jogo['acao']) ?> →</button>
            </section>

            <section id="gamePanel" class="game-panel">
                <div class="game-hud">
                    <div class="hud-item"><div><div class="hud-label">Desafio</div><div class="hud-value"><span id="roundNumber">1</span>/6</div></div></div>
                    <div class="hud-item"><div><div class="hud-label">Acertos</div><div class="hud-value" id="correctCount">0</div></div></div>
                    <div class="hud-item"><div><div class="hud-label">Erros</div><div class="hud-value" id="wrongCount">0</div></div></div>
                    <div class="hud-item"><div><div class="hud-label">Pontos</div><div class="hud-value" id="scoreCount">0</div></div></div>
                    <div class="hud-progress"><div class="hud-label">Progresso da missão</div><div class="hud-progress-bar"><div class="hud-progress-fill portuguese-progress" id="progressFill"></div></div></div>
                </div>
                <article class="portuguese-challenge">
                    <div class="portuguese-stage" id="stageLabel"></div>
                    <div class="portuguese-prompt" id="questionPrompt"></div>
                    <div class="portuguese-question" id="questionText"></div>
                    <div class="options-grid" id="optionsGrid">
                        <button class="option-btn" id="opt0" onclick="verificarResposta(0)"></button>
                        <button class="option-btn" id="opt1" onclick="verificarResposta(1)"></button>
                        <button class="option-btn" id="opt2" onclick="verificarResposta(2)"></button>
                        <button class="option-btn" id="opt3" onclick="verificarResposta(3)"></button>
                    </div>
                    <div class="feedback-box" id="feedbackBox">
                        <div class="feedback-title" id="feedbackTitle"></div>
                        <div class="feedback-text" id="feedbackText"></div>
                    </div>
                    <button class="btn-next portuguese-next" id="btnNext" onclick="proximaQuestao()">Próximo desafio →</button>
                </article>
            </section>

            <section id="resultScreen" class="result-screen">
                <h2 id="resultTitle">Missão concluída!</h2>
                <p class="result-sub" id="resultSub"></p>
                <div class="result-stats">
                    <div class="result-stat"><div class="r-value" id="rPontuacao">0</div><div class="r-label">Pontuação</div></div>
                    <div class="result-stat"><div class="r-value" id="rAcertos">0</div><div class="r-label">Acertos</div></div>
                    <div class="result-stat"><div class="r-value" id="rErros">0</div><div class="r-label">Erros</div></div>
                </div>
                <div class="result-xp"><div class="xp-earned" id="xpEarned">+0 XP</div><div class="xp-label">Experiência ganha!</div></div>
                <div id="conquistaBox" class="portuguese-achievements"><strong> Nova conquista!</strong><div id="conquistaLista"></div></div>
                <div class="result-buttons">
                    <button class="btn btn-primary" onclick="reiniciarJogo()"> Jogar novamente</button>
                    <a href="/site_antigravity/jogos.php" class="btn btn-outline">Outros jogos</a>
                    <a href="/site_antigravity/dashboard.php" class="btn btn-outline">Dashboard</a>
                </div>
            </section>
        </div>
    </main>
</div>
<script>
    const JOGO_PORTUGUES_ID = <?= (int)$jogo_portugues_id ?>;
    const JOGO_PORTUGUES_NOME = <?= json_encode($jogo['nome'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const ANO_ESCOLAR = <?= (int)$ano_escolar ?>;
</script>
<script src="/site_antigravity/js/serie_jogos.js"></script>
<script src="/site_antigravity/js/jogos_portugues.js"></script>
</body>
</html>
