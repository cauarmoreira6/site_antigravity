<?php
// ============================================================
// MathPlay Solutions — Admin: Resultados e Histórico
// ============================================================
require_once '../includes/verificar_login.php';
verificar_admin();
require_once '../includes/conexao.php';

// Filtro opcional por jogo
$filtro_jogo = (int)($_GET['jogo'] ?? 0);

$where = "";
if ($filtro_jogo >= 1 && $filtro_jogo <= 4) {
    $where = "WHERE r.jogo_id = $filtro_jogo";
}

$resultados = $conn->query("
    SELECT r.*, u.nome, u.email 
    FROM resultados r
    JOIN usuarios u ON u.id = r.usuario_id
    $where
    ORDER BY r.jogado_em DESC
    LIMIT 100
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Partidas — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
</head>
<body>
<div class="app-layout">

    <?php require_once '../includes/header.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>📊 Relatório de Resultados</h1>
                <p>Histórico detalhado de todas as partidas jogadas</p>
            </div>
            <a href="/site_antigravity/admin/dashboard.php" class="btn btn-outline btn-sm">← Voltar ao Painel</a>
        </div>

        <!-- Filtros rápidos -->
        <div class="card" style="margin-bottom:20px;padding:16px 20px;">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span style="font-weight:700;font-size:0.9rem;color:#2d3436;">Filtrar por Jogo:</span>
                <a href="/site_antigravity/admin/resultados.php" class="btn btn-sm <?= $filtro_jogo === 0 ? 'btn-primary' : 'btn-outline' ?>">Todos</a>
                <a href="/site_antigravity/admin/resultados.php?jogo=1" class="btn btn-sm <?= $filtro_jogo === 1 ? 'btn-primary' : 'btn-outline' ?>">⚔️ Inteiros</a>
                <a href="/site_antigravity/admin/resultados.php?jogo=2" class="btn btn-sm <?= $filtro_jogo === 2 ? 'btn-primary' : 'btn-outline' ?>">🔐 Equações</a>
                <a href="/site_antigravity/admin/resultados.php?jogo=3" class="btn btn-sm <?= $filtro_jogo === 3 ? 'btn-primary' : 'btn-outline' ?>">🛒 Loja</a>
                <a href="/site_antigravity/admin/resultados.php?jogo=4" class="btn btn-sm <?= $filtro_jogo === 4 ? 'btn-primary' : 'btn-outline' ?>">🔍 Gráficos</a>
            </div>
        </div>

        <div class="card">
            <div class="card-title">📋 Partidas Registradas (Exibindo até 100 mais recentes)</div>
            <?php if (empty($resultados)): ?>
                <p style="color:#b2bec3;padding:30px;text-align:center;">Nenhuma partida encontrada com o filtro selecionado.</p>
            <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Jogo</th>
                                <th>Dificuldade</th>
                                <th>Pontos</th>
                                <th>Acertos</th>
                                <th>Erros</th>
                                <th>Data e Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados as $r): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($r['nome']) ?></strong><br>
                                    <small style="color:#b2bec3;"><?= htmlspecialchars($r['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($r['jogo_nome']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $r['dificuldade'] === 'facil' ? 'success' : ($r['dificuldade'] === 'medio' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($r['dificuldade']) ?>
                                    </span>
                                </td>
                                <td><strong><?= $r['pontuacao'] ?></strong></td>
                                <td style="color:#2ecc71;font-weight:700;"><?= $r['acertos'] ?></td>
                                <td style="color:#e74c3c;font-weight:700;"><?= $r['erros'] ?></td>
                                <td style="color:#636e72;font-size:0.85rem;"><?= date('d/m/Y H:i:s', strtotime($r['jogado_em'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>
</body>
</html>

