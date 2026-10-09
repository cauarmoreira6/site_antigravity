<?php
// ============================================================
// MathPlay Solutions — Conquistas / Medalhas
// ============================================================
require_once 'includes/verificar_login.php';
require_once 'includes/conexao.php';

$uid = $_SESSION['usuario_id'];

// Busca as conquistas do usuário
$stmt = $conn->prepare('SELECT * FROM conquistas WHERE usuario_id = ? ORDER BY conquistado_em DESC');
$stmt->bind_param('i', $uid);
$stmt->execute();
$minhas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Lista de TODAS as medalhas possíveis do sistema
$todas_medalhas = [
    ['icone'=>'01', 'medalha'=>'Primeiro Passo',      'descricao'=>'Completar o primeiro jogo da plataforma'],
    ['icone'=>'05', 'medalha'=>'Sequência Perfeita',   'descricao'=>'Conseguir 5 respostas corretas consecutivas'],
    ['icone'=>'100','medalha'=>'Mestre da Matemática', 'descricao'=>'Acumular 100 acertos no total'],
    ['icone'=>'0',  'medalha'=>'Sem Errar',             'descricao'=>'Finalizar uma partida com 0 erros'],
    ['icone'=>'10', 'medalha'=>'Jogador Frequente',     'descricao'=>'Completar 10 partidas'],
    ['icone'=>'$',  'medalha'=>'Comerciante Nato',      'descricao'=>'Completar a Loja MathPlay no nível difícil'],
    ['icone'=>'OA', 'medalha'=>'Olho de Águia',         'descricao'=>'Completar o Detetive dos Gráficos sem erros'],
];

// Cria um array com os nomes das medalhas já conquistadas (para verificação rápida)
$conquistadas_nomes = array_column($minhas, 'medalha');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conquistas — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
</head>
<body>
<div class="app-layout">

    <?php require_once 'includes/header.php'; ?>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Conquistas</h1>
                <p>Suas medalhas e conquistas — continue jogando para desbloquear mais!</p>
            </div>
            <div class="topbar-right">
                <div style="background:#fff;border-radius:50px;padding:8px 20px;box-shadow:0 2px 10px rgba(0,0,0,0.08);font-size:0.9rem;color:#636e72;">
                    <?= count($minhas) ?> / <?= count($todas_medalhas) ?> medalhas
                </div>
            </div>
        </div>

        <!-- Barra de progresso das conquistas -->
        <div class="card" style="margin-bottom:24px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                <span style="font-weight:700;font-size:0.9rem;">Progresso de Conquistas</span>
                <span style="color:#636e72;font-size:0.85rem;">
                    <?= count($minhas) ?> de <?= count($todas_medalhas) ?>
                </span>
            </div>
            <?php $pct = round(count($minhas) / count($todas_medalhas) * 100); ?>
            <div class="xp-bar-wrap" style="background:#e0e0e0;">
                <div class="xp-bar-fill" style="width:<?= $pct ?>%;background:linear-gradient(90deg,#f39c12,#ffd700);"></div>
            </div>
        </div>

        <!-- Grid de todas as medalhas -->
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;">

            <?php foreach ($todas_medalhas as $m):
                $conquistada = in_array($m['medalha'], $conquistadas_nomes);

                // Encontra a data da conquista (se conquistada)
                $data_conquista = '';
                foreach ($minhas as $mc) {
                    if ($mc['medalha'] === $m['medalha']) {
                        $data_conquista = date('d/m/Y', strtotime($mc['conquistado_em']));
                        break;
                    }
                }
            ?>
            <div style="background:#fff;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);
                        text-align:center;transition:all 0.3s;
                        <?= !$conquistada ? 'opacity:0.45;filter:grayscale(1);' : 'border:2px solid rgba(243,156,18,0.3);' ?>"
                 <?= $conquistada ? 'onmouseover="this.style.transform=\'translateY(-4px)\'" onmouseout="this.style.transform=\'translateY(0)\'"' : '' ?>>

                <div class="achievement-mark"><?= htmlspecialchars($m['icone']) ?></div>
                <h3 style="font-size:1rem;font-weight:800;color:#2d3436;margin-bottom:8px;">
                    <?= htmlspecialchars($m['medalha']) ?>
                </h3>
                <p style="font-size:0.8rem;color:#636e72;margin-bottom:12px;line-height:1.5;">
                    <?= htmlspecialchars($m['descricao']) ?>
                </p>

                <?php if ($conquistada): ?>
                    <span style="display:inline-block;background:rgba(243,156,18,0.12);color:#e67e22;
                                 padding:4px 12px;border-radius:50px;font-size:0.75rem;font-weight:700;">
                         Conquistada <?= $data_conquista ? 'em '.$data_conquista : '' ?>
                    </span>
                <?php else: ?>
                    <span style="display:inline-block;background:rgba(99,110,114,0.1);color:#b2bec3;
                                 padding:4px 12px;border-radius:50px;font-size:0.75rem;font-weight:700;">
                         Bloqueada
                    </span>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>

        </div>

        <!-- Botão para jogar -->
        <?php if (count($minhas) < count($todas_medalhas)): ?>
        <div style="text-align:center;margin-top:32px;">
            <p style="color:#636e72;margin-bottom:16px;">Continue jogando para desbloquear mais medalhas!</p>
            <a href="/site_antigravity/jogos.php" class="btn btn-primary"> Ir para os Jogos</a>
        </div>
        <?php else: ?>
        <div style="text-align:center;margin-top:32px;padding:30px;background:#fff;border-radius:14px;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <h2 style="color:#2d3436;">Parabéns! Você conquistou todas as medalhas!</h2>
            <p style="color:#636e72;">Você é um verdadeiro Mestre MathPlay!</p>
        </div>
        <?php endif; ?>

    </main>
</div>
</body>
</html>
