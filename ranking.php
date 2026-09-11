<?php
// ============================================================
// MathPlay Solutions — Ranking
// ============================================================
require_once 'includes/verificar_login.php';
require_once 'includes/conexao.php';

$uid = $_SESSION['usuario_id'];

// Busca o ranking completo (apenas alunos)
// RANK() OVER é uma função de janela do MySQL 8+ que calcula posição
$sql = 'SELECT u.nome, p.pontuacao, p.xp, p.nivel, p.acertos, p.jogos_feitos,
               RANK() OVER (ORDER BY p.pontuacao DESC) AS posicao
        FROM progresso p
        JOIN usuarios u ON u.id = p.usuario_id
        WHERE u.tipo = "aluno"
        ORDER BY p.pontuacao DESC';

$ranking = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

// Pega a posição do usuário atual no ranking
$minha_pos = '?';
foreach ($ranking as $r) {
    if ($r['posicao'] <= count($ranking)) {
        // Precisamos do usuario_id no ranking para identificar "eu"
    }
}

// Refaz com usuario_id para identificar o aluno atual
$sql2 = 'SELECT u.id, u.nome, p.pontuacao, p.xp, p.nivel, p.acertos, p.jogos_feitos,
                RANK() OVER (ORDER BY p.pontuacao DESC) AS posicao
         FROM progresso p
         JOIN usuarios u ON u.id = p.usuario_id
         WHERE u.tipo = "aluno"
         ORDER BY p.pontuacao DESC';

$ranking = $conn->query($sql2)->fetch_all(MYSQLI_ASSOC);

// Encontra minha posição
foreach ($ranking as $r) {
    if ($r['id'] == $uid) {
        $minha_pos = $r['posicao'];
        break;
    }
}

$titulos = [1=>'Iniciante',2=>'Aprendiz',3=>'Explorador',4=>'Aventureiro',5=>'Desafiador',6=>'Mestre MathPlay'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
</head>
<body>
<div class="app-layout">

    <?php require_once 'includes/header.php'; ?>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>🏆 Ranking Geral</h1>
                <p>Veja quem são os melhores jogadores da MathPlay!</p>
            </div>
            <div class="topbar-right">
                <div style="background:#fff;border-radius:50px;padding:8px 20px;box-shadow:0 2px 10px rgba(0,0,0,0.08);font-size:0.9rem;color:#636e72;">
                    Sua posição: <strong style="color:#6c63ff;font-size:1.1rem;">#<?= $minha_pos ?></strong>
                </div>
            </div>
        </div>

        <!-- Pódio (top 3) -->
        <?php if (count($ranking) >= 3): ?>
        <div style="display:flex;justify-content:center;align-items:flex-end;gap:16px;margin-bottom:32px;flex-wrap:wrap;">

            <!-- 2º lugar -->
            <div style="text-align:center;order:1;">
                <div style="font-size:2rem;">🥈</div>
                <div style="background:#fff;border-radius:14px;padding:20px 24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);min-width:120px;border-top:4px solid #c0c0c0;">
                    <div style="font-size:2rem;">👤</div>
                    <div style="font-weight:700;font-size:0.9rem;margin-top:8px;"><?= htmlspecialchars(explode(' ', $ranking[1]['nome'])[0]) ?></div>
                    <div style="font-size:0.8rem;color:#636e72;"><?= number_format($ranking[1]['pontuacao']) ?> pts</div>
                </div>
                <div style="background:#c0c0c0;height:60px;width:100%;border-radius:0 0 8px 8px;"></div>
            </div>

            <!-- 1º lugar -->
            <div style="text-align:center;order:2;">
                <div style="font-size:2rem;">👑</div>
                <div style="background:#fff;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);min-width:140px;border-top:4px solid #ffd700;">
                    <div style="font-size:2.5rem;">👤</div>
                    <div style="font-weight:800;font-size:1rem;margin-top:8px;"><?= htmlspecialchars(explode(' ', $ranking[0]['nome'])[0]) ?></div>
                    <div style="font-size:0.85rem;color:#636e72;"><?= number_format($ranking[0]['pontuacao']) ?> pts</div>
                </div>
                <div style="background:#ffd700;height:90px;width:100%;border-radius:0 0 8px 8px;"></div>
            </div>

            <!-- 3º lugar -->
            <div style="text-align:center;order:3;">
                <div style="font-size:2rem;">🥉</div>
                <div style="background:#fff;border-radius:14px;padding:20px 24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);min-width:120px;border-top:4px solid #cd7f32;">
                    <div style="font-size:2rem;">👤</div>
                    <div style="font-weight:700;font-size:0.9rem;margin-top:8px;"><?= htmlspecialchars(explode(' ', $ranking[2]['nome'])[0]) ?></div>
                    <div style="font-size:0.8rem;color:#636e72;"><?= number_format($ranking[2]['pontuacao']) ?> pts</div>
                </div>
                <div style="background:#cd7f32;height:40px;width:100%;border-radius:0 0 8px 8px;"></div>
            </div>

        </div>
        <?php endif; ?>

        <!-- Tabela completa do ranking -->
        <div class="card">
            <div class="card-title">📋 Classificação Completa</div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jogador</th>
                            <th>Nível</th>
                            <th>Pontuação</th>
                            <th>XP</th>
                            <th>Acertos</th>
                            <th>Partidas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ranking as $r):
                            $pos   = $r['posicao'];
                            $isMe  = ($r['id'] == $uid);
                            $icons = ['🥇','🥈','🥉'];
                            $icon  = $icons[$pos-1] ?? $pos;
                        ?>
                        <tr <?= $isMe ? 'style="background:rgba(108,99,255,0.06);font-weight:600;"' : '' ?>>
                            <td style="font-size:1.1rem;"><?= $icon ?></td>
                            <td>
                                <?= htmlspecialchars($r['nome']) ?>
                                <?php if ($isMe): ?>
                                    <span class="badge badge-primary" style="margin-left:6px;">Você</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-primary">Nv. <?= $r['nivel'] ?></span></td>
                            <td><strong><?= number_format($r['pontuacao']) ?></strong></td>
                            <td><?= number_format($r['xp']) ?> XP</td>
                            <td><?= $r['acertos'] ?></td>
                            <td><?= $r['jogos_feitos'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($ranking)): ?>
                        <tr><td colspan="7" style="text-align:center;color:#b2bec3;padding:30px;">Nenhum jogador ainda.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</body>
</html>

