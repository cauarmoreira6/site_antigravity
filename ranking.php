<?php
// ============================================================
// Educa Play — Ranking
// ============================================================
require_once 'includes/verificar_login.php';
require_once 'includes/conexao.php';

$uid = $_SESSION['usuario_id'];

$minha_pos = '?';
$filtros_materia = [
    'geral' => ['rotulo' => 'Geral', 'jogos' => null],
    'portugues' => ['rotulo' => 'Língua Portuguesa', 'jogos' => [5, 8]],
    'matematica' => ['rotulo' => 'Matemática', 'jogos' => [1, 4]],
];
$materia = $_GET['materia'] ?? 'geral';
if (!is_string($materia) || !isset($filtros_materia[$materia])) {
    $materia = 'geral';
}

if ($materia === 'geral') {
    $sql = 'SELECT u.id, u.nome, p.pontuacao, p.xp, p.nivel, p.acertos, p.jogos_feitos,
                   RANK() OVER (ORDER BY p.pontuacao DESC) AS posicao
            FROM progresso p
            JOIN usuarios u ON u.id = p.usuario_id
            WHERE u.tipo = "aluno"
            ORDER BY p.pontuacao DESC';
} else {
    [$jogo_inicio, $jogo_fim] = $filtros_materia[$materia]['jogos'];
    $sql = 'SELECT materia_ranking.*,
                   RANK() OVER (ORDER BY materia_ranking.pontuacao DESC) AS posicao
            FROM (
                SELECT u.id, u.nome, COALESCE(SUM(r.pontuacao), 0) AS pontuacao,
                       COALESCE(SUM(r.acertos), 0) AS acertos, COUNT(r.id) AS jogos_feitos,
                       p.xp, p.nivel
                FROM usuarios u
                LEFT JOIN progresso p ON p.usuario_id = u.id
                LEFT JOIN resultados r
                    ON r.usuario_id = u.id AND r.jogo_id BETWEEN ? AND ?
                WHERE u.tipo = "aluno"
                GROUP BY u.id, u.nome, p.xp, p.nivel
            ) AS materia_ranking
            ORDER BY materia_ranking.pontuacao DESC';
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('Não foi possível preparar o ranking por matéria.');
    }
    if (!$stmt->bind_param('ii', $jogo_inicio, $jogo_fim) || !$stmt->execute()) {
        throw new RuntimeException('Não foi possível carregar o ranking por matéria.');
    }
    $ranking = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

if ($materia === 'geral') {
    $ranking = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

foreach ($ranking as $r) {
    if ($r['id'] == $uid) {
        $minha_pos = $r['posicao'];
        break;
    }
}

$titulos = [1=>'Iniciante',2=>'Aprendiz',3=>'Explorador',4=>'Aventureiro',5=>'Desafiador',6=>'Mestre Educa Play'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking — Educa Play</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css?v=20261023-logo">
</head>
<body>
<div class="app-layout">

    <?php require_once 'includes/header.php'; ?>

    <main id="main-content" class="main-content" tabindex="-1">

        <div class="topbar">
            <div>
                <h1>Ranking de <?= htmlspecialchars($filtros_materia[$materia]['rotulo']) ?></h1>
                <p>Veja a classificação dos jogadores da Educa Play!</p>
            </div>
            <div class="topbar-right">
                <div style="background:#fff;border-radius:50px;padding:8px 20px;box-shadow:0 2px 10px rgba(0,0,0,0.08);font-size:0.9rem;color:#636e72;">
                    Sua posição: <strong style="color:#6c63ff;font-size:1.1rem;">#<?= $minha_pos ?></strong>
                </div>
            </div>
        </div>

        <nav aria-label="Filtrar ranking por matéria" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
            <?php foreach ($filtros_materia as $chave => $filtro): ?>
                <a
                    href="/site_antigravity/ranking.php?materia=<?= urlencode($chave) ?>"
                    class="btn <?= $materia === $chave ? 'btn-primary' : 'btn-outline' ?>"
                    <?= $materia === $chave ? 'aria-current="page"' : '' ?>
                ><?= htmlspecialchars($filtro['rotulo']) ?></a>
            <?php endforeach; ?>
        </nav>

        <!-- Pódio (top 3) -->
        <?php if (count($ranking) >= 3): ?>
        <div style="display:flex;justify-content:center;align-items:flex-end;gap:16px;margin-bottom:32px;flex-wrap:wrap;">

            <!-- 2º lugar -->
            <div style="text-align:center;order:1;">
                <div class="ranking-place">2º lugar</div>
                <div style="background:#fff;border-radius:14px;padding:20px 24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);min-width:120px;border-top:4px solid #c0c0c0;">
                    <div class="ranking-place">2º</div>
                    <div style="font-weight:700;font-size:0.9rem;margin-top:8px;"><?= htmlspecialchars(explode(' ', $ranking[1]['nome'])[0]) ?></div>
                    <div style="font-size:0.8rem;color:#636e72;"><?= number_format($ranking[1]['pontuacao']) ?> pts</div>
                </div>
                <div style="background:#c0c0c0;height:60px;width:100%;border-radius:0 0 8px 8px;"></div>
            </div>

            <!-- 1º lugar -->
            <div style="text-align:center;order:2;">
                <div class="ranking-place">1º lugar</div>
                <div style="background:#fff;border-radius:14px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);min-width:140px;border-top:4px solid #ffd700;">
                    <div class="ranking-place ranking-place-first">1º</div>
                    <div style="font-weight:800;font-size:1rem;margin-top:8px;"><?= htmlspecialchars(explode(' ', $ranking[0]['nome'])[0]) ?></div>
                    <div style="font-size:0.85rem;color:#636e72;"><?= number_format($ranking[0]['pontuacao']) ?> pts</div>
                </div>
                <div style="background:#ffd700;height:90px;width:100%;border-radius:0 0 8px 8px;"></div>
            </div>

            <!-- 3º lugar -->
            <div style="text-align:center;order:3;">
                <div class="ranking-place">3º lugar</div>
                <div style="background:#fff;border-radius:14px;padding:20px 24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);min-width:120px;border-top:4px solid #cd7f32;">
                    <div class="ranking-place">3º</div>
                    <div style="font-weight:700;font-size:0.9rem;margin-top:8px;"><?= htmlspecialchars(explode(' ', $ranking[2]['nome'])[0]) ?></div>
                    <div style="font-size:0.8rem;color:#636e72;"><?= number_format($ranking[2]['pontuacao']) ?> pts</div>
                </div>
                <div style="background:#cd7f32;height:40px;width:100%;border-radius:0 0 8px 8px;"></div>
            </div>

        </div>
        <?php endif; ?>

        <!-- Tabela completa do ranking -->
        <div class="card">
            <div class="card-title"> Classificação Completa</div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jogador</th>
                            <th>Pontuação</th>
                            <th>Acertos</th>
                            <th>Partidas</th>
                            <?php if ($materia === 'geral'): ?>
                                <th>Nível</th>
                                <th>XP</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ranking as $r):
                            $pos   = $r['posicao'];
                            $isMe  = ($r['id'] == $uid);
                            $icons = [1, 2, 3];
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
                            <td><strong><?= number_format($r['pontuacao']) ?></strong></td>
                            <td><?= $r['acertos'] ?></td>
                            <td><?= $r['jogos_feitos'] ?></td>
                            <?php if ($materia === 'geral'): ?>
                                <td><span class="badge badge-primary">Nv. <?= $r['nivel'] ?></span></td>
                                <td><?= number_format($r['xp']) ?> XP</td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($ranking)): ?>
                        <tr><td colspan="<?= $materia === 'geral' ? 7 : 5 ?>" style="text-align:center;color:#b2bec3;padding:30px;">Nenhum jogador ainda.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</body>
</html>
