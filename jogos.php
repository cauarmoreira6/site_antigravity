<?php
// ============================================================
// MathPlay Solutions — Catálogo de Jogos
// ============================================================
require_once 'includes/verificar_login.php';
require_once 'includes/conexao.php';

$uid = $_SESSION['usuario_id'];

// Busca quantas partidas o usuário jogou por jogo
$stmt = $conn->prepare('SELECT jogo_id, COUNT(*) as partidas, MAX(pontuacao) as melhor FROM resultados WHERE usuario_id = ? GROUP BY jogo_id');
$stmt->bind_param('i', $uid);
$stmt->execute();
$stats_jogo = [];
foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $s) {
    $stats_jogo[$s['jogo_id']] = $s;
}
$stmt->close();

// Definição dos jogos
$jogos = [
    1 => [
        'nome'      => 'Batalha dos Inteiros',
        'tema'      => 'Números Inteiros',
        'descricao' => 'Resolva operações com números positivos e negativos.',
        'dificuldades'=> 'Fácil, Médio e Difícil',
        'cor'       => 'j1',
        'link'      => '/site_antigravity/jogos/jogo1.php',
    ],
    2 => [
        'nome'      => 'Cofre das Equações',
        'tema'      => 'Equações de 1º Grau',
        'descricao' => 'Descubra o valor de X para resolver as equações com dificuldade crescente.',
        'dificuldades'=> 'Fácil, Médio e Difícil',
        'cor'       => 'j2',
        'link'      => '/site_antigravity/jogos/jogo2.php',
    ],
    3 => [
        'nome'      => 'Loja MathPlay',
        'tema'      => 'Porcentagem e Finanças',
        'descricao' => 'Pratique conceitos de desconto, troco, acréscimo e porcentagens aplicadas.',
        'dificuldades'=> 'Fácil, Médio e Difícil',
        'cor'       => 'j3',
        'link'      => '/site_antigravity/jogos/jogo3.php',
    ],
    4 => [
        'nome'      => 'Detetive dos Gráficos',
        'tema'      => 'Estatística e Gráficos',
        'descricao' => 'Interprete gráficos de barras e resolva questões de média, moda e mediana.',
        'dificuldades'=> 'Fácil, Médio e Difícil',
        'cor'       => 'j4',
        'link'      => '/site_antigravity/jogos/jogo4.php',
    ],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogos — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
    <link rel="stylesheet" href="/site_antigravity/css/jogos.css">
</head>
<body>
<div class="app-layout">

    <?php require_once 'includes/header.php'; ?>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Catálogo de Jogos</h1>
                <p>Escolha um jogo para praticar suas habilidades matemáticas</p>
            </div>
        </div>

        <!-- Dica de Pontuação -->
        <div style="background:rgba(108,99,255,0.08);border:1px solid rgba(108,99,255,0.2);border-radius:10px;padding:14px 20px;margin-bottom:24px;">
            <strong style="color:#6c63ff;">Critérios de Pontuação:</strong>
            <span style="color:#636e72;font-size:0.9rem;"> +10 pontos por resposta correta • +5 pontos bônus em sequências de acertos • +20 pontos ao concluir a partida</span>
        </div>

        <!-- Catálogo de jogos -->
        <div class="jogos-catalog">
            <?php foreach ($jogos as $id => $jogo):
                $stats = $stats_jogo[$id] ?? null;
                $jogado = $stats !== null;
            ?>
            <a href="<?= $jogo['link'] ?>" class="catalog-card <?= $jogo['cor'] ?>">

                <!-- Corpo -->
                <div class="catalog-card-body" style="padding-top:24px;">
                    <h3><?= $jogo['nome'] ?></h3>
                    <p><?= $jogo['descricao'] ?></p>

                    <!-- Informações de desempenho -->
                    <?php if ($jogado): ?>
                    <div style="background:#f0f2f5;border-radius:8px;padding:8px 12px;font-size:0.8rem;color:#636e72;">
                        <strong><?= $stats['partidas'] ?></strong> partida<?= $stats['partidas'] > 1 ? 's' : '' ?> realizada<?= $stats['partidas'] > 1 ? 's' : '' ?> •
                        Melhor: <strong style="color:#6c63ff;"><?= $stats['melhor'] ?> pts</strong>
                    </div>
                    <?php else: ?>
                    <div style="background:#f0f2f5;border-radius:8px;padding:8px 12px;font-size:0.8rem;color:#95a5a6;">
                        Ainda não iniciado
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Rodapé -->
                <div class="catalog-card-footer">
                    <span class="jogo-tag"><?= $jogo['tema'] ?></span>
                    <span>Jogar</span>
                </div>

            </a>
            <?php endforeach; ?>
        </div>

        <!-- Legenda de dificuldades -->
        <div class="card" style="margin-top:28px;">
            <div class="card-title">Níveis de Dificuldade</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                <div style="padding:14px;background:#f0f2f5;border-radius:10px;">
                    <div style="font-weight:700;color:#2ecc71;">Fácil</div>
                    <div style="font-size:0.8rem;color:#636e72;">Conceitos iniciais e cálculos diretos</div>
                </div>
                <div style="padding:14px;background:#f0f2f5;border-radius:10px;">
                    <div style="font-weight:700;color:#f39c12;">Médio</div>
                    <div style="font-size:0.8rem;color:#636e72;">Questões intermediárias com múltiplos passos</div>
                </div>
                <div style="padding:14px;background:#f0f2f5;border-radius:10px;">
                    <div style="font-weight:700;color:#e74c3c;">Difícil</div>
                    <div style="font-size:0.8rem;color:#636e72;">Desafios avançados e problemas contextualizados</div>
                </div>
            </div>
        </div>

    </main>
</div>
</body>
</html>
