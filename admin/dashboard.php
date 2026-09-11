<?php
// ============================================================
// MathPlay Solutions — Admin: Dashboard Geral
// ============================================================
require_once '../includes/verificar_login.php';
verificar_admin();
require_once '../includes/conexao.php';

// Estatísticas globais
$total_alunos = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo = 'aluno'")->fetch_assoc()['total'];
$total_partidas = $conn->query("SELECT COUNT(*) as total FROM resultados")->fetch_assoc()['total'];
$total_acertos = $conn->query("SELECT SUM(acertos) as total FROM resultados")->fetch_assoc()['total'] ?? 0;
$total_erros = $conn->query("SELECT SUM(erros) as total FROM resultados")->fetch_assoc()['total'] ?? 0;

$total_tentativas = $total_acertos + $total_erros;
$taxa_geral = $total_tentativas > 0 ? round(($total_acertos / $total_tentativas) * 100) : 0;

// Últimas 5 partidas jogadas no sistema
$ultimos_jogos = $conn->query("
    SELECT r.*, u.nome 
    FROM resultados r 
    JOIN usuarios u ON u.id = r.usuario_id 
    ORDER BY r.jogado_em DESC 
    LIMIT 6
")->fetch_all(MYSQLI_ASSOC);

// Top 5 Alunos com maior pontuação
$top_alunos = $conn->query("
    SELECT u.nome, p.pontuacao, p.xp, p.nivel, p.jogos_feitos 
    FROM progresso p 
    JOIN usuarios u ON u.id = p.usuario_id 
    WHERE u.tipo = 'aluno' 
    ORDER BY p.pontuacao DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Professor — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
</head>
<body>
<div class="app-layout">

    <?php require_once '../includes/header.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1>👨‍🏫 Painel do Professor / Admin</h1>
                <p>Acompanhamento de desempenho da turma e estatísticas pedagógicas</p>
            </div>
            <div class="topbar-right">
                <a href="/site_antigravity/admin/usuarios.php" class="btn btn-outline btn-sm">👥 Gerenciar Alunos</a>
                <a href="/site_antigravity/admin/resultados.php" class="btn btn-primary btn-sm">📊 Relatório de Partidas</a>
            </div>
        </div>

        <!-- Indicadores Gerais -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <div class="stat-value"><?= $total_alunos ?></div>
                    <div class="stat-label">Alunos Cadastrados</div>
                </div>
            </div>
            <div class="stat-card accent">
                <div class="stat-icon">🎮</div>
                <div class="stat-info">
                    <div class="stat-value"><?= $total_partidas ?></div>
                    <div class="stat-label">Partidas Realizadas</div>
                </div>
            </div>
            <div class="stat-card success">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <div class="stat-value"><?= $total_acertos ?></div>
                    <div class="stat-label">Questões Acertadas</div>
                </div>
            </div>
            <div class="stat-card warning">
                <div class="stat-icon">🎯</div>
                <div class="stat-info">
                    <div class="stat-value"><?= $taxa_geral ?>%</div>
                    <div class="stat-label">Taxa Geral de Acerto</div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Partidas recentes -->
            <div class="card">
                <div class="card-title">🕒 Últimas Partidas dos Alunos</div>
                <?php if (empty($ultimos_jogos)): ?>
                    <p style="color:#b2bec3;padding:20px;text-align:center;">Nenhuma partida registrada até o momento.</p>
                <?php else: ?>
                    <div style="overflow-x:auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Jogo</th>
                                    <th>Dificuldade</th>
                                    <th>Pontos</th>
                                    <th>Acertos/Erros</th>
                                    <th>Horário</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ultimos_jogos as $j): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($j['nome']) ?></strong></td>
                                    <td><?= htmlspecialchars($j['jogo_nome']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $j['dificuldade'] === 'facil' ? 'success' : ($j['dificuldade'] === 'medio' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($j['dificuldade']) ?>
                                        </span>
                                    </td>
                                    <td><?= $j['pontuacao'] ?></td>
                                    <td>
                                        <span style="color:#2ecc71;font-weight:700;"><?= $j['acertos'] ?></span> / 
                                        <span style="color:#e74c3c;font-weight:700;"><?= $j['erros'] ?></span>
                                    </td>
                                    <td style="color:#b2bec3;font-size:0.8rem;"><?= date('d/m/Y H:i', strtotime($j['jogado_em'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Melhores Alunos -->
            <div class="card">
                <div class="card-title">🏆 Alunos com Maior Pontuação</div>
                <div class="ranking-list">
                    <?php if (empty($top_alunos)): ?>
                        <p style="color:#b2bec3;padding:10px;text-align:center;">Sem registros de alunos.</p>
                    <?php else: ?>
                        <?php foreach ($top_alunos as $idx => $aluno): ?>
                            <div class="ranking-item">
                                <div class="ranking-pos <?= $idx === 0 ? 'gold' : ($idx === 1 ? 'silver' : ($idx === 2 ? 'bronze' : '')) ?>">
                                    <?= $idx + 1 ?>º
                                </div>
                                <div class="ranking-name">
                                    <div><?= htmlspecialchars($aluno['nome']) ?></div>
                                    <small style="color:#636e72;font-size:0.75rem;">Nível <?= $aluno['nivel'] ?> • <?= $aluno['jogos_feitos'] ?> jogos</small>
                                </div>
                                <div class="ranking-pts"><?= number_format($aluno['pontuacao']) ?> pts</div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </main>
</div>
</body>
</html>

