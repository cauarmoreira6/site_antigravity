<?php
// ============================================================
// MathPlay Solutions — Dashboard do Aluno
// ============================================================
require_once 'includes/verificar_login.php';  // Protege a página
require_once 'includes/conexao.php';

$uid = $_SESSION['usuario_id'];

// ---- Busca o progresso completo do usuário ----
$stmt = $conn->prepare('SELECT * FROM progresso WHERE usuario_id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$prog = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Garante valores padrão caso não exista registro
$xp          = $prog['xp']          ?? 0;
$nivel       = $prog['nivel']       ?? 1;
$pontuacao   = $prog['pontuacao']   ?? 0;
$acertos     = $prog['acertos']     ?? 0;
$erros       = $prog['erros']       ?? 0;
$jogos_feitos= $prog['jogos_feitos']?? 0;

// Atualiza a sessão com os dados mais recentes
$_SESSION['xp']    = $xp;
$_SESSION['nivel'] = $nivel;

// ---- Títulos de nível ----
$titulos = [
    1 => 'Iniciante',    2 => 'Aprendiz',
    3 => 'Explorador',   4 => 'Aventureiro',
    5 => 'Desafiador',   6 => 'Mestre MathPlay'
];
$titulo = $titulos[$nivel] ?? 'Mestre MathPlay';

// ---- Cálculo do progresso da barra de XP ----
$xp_limites = [0, 0, 100, 250, 500, 800, 1200, 9999];
$xp_prox    = $xp_limites[$nivel + 1] ?? 9999;
$xp_ini     = $xp_limites[$nivel]     ?? 0;
$pct_xp     = ($xp_prox > $xp_ini) ? min(100, round(($xp - $xp_ini) / ($xp_prox - $xp_ini) * 100)) : 100;

// ---- Taxa de acerto ----
$total_resp  = $acertos + $erros;
$taxa_acerto = ($total_resp > 0) ? round($acertos / $total_resp * 100) : 0;

// ---- Conquistas do usuário ----
$stmt = $conn->prepare('SELECT * FROM conquistas WHERE usuario_id = ? ORDER BY conquistado_em DESC LIMIT 6');
$stmt->bind_param('i', $uid);
$stmt->execute();
$conquistas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ---- Últimas partidas ----
$stmt = $conn->prepare('SELECT * FROM resultados WHERE usuario_id = ? ORDER BY jogado_em DESC LIMIT 5');
$stmt->bind_param('i', $uid);
$stmt->execute();
$historico = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ---- Mini Ranking (top 5) ----
$ranking_sql = 'SELECT u.nome, p.pontuacao, p.nivel,
                       RANK() OVER (ORDER BY p.pontuacao DESC) as posicao
                FROM progresso p
                JOIN usuarios u ON u.id = p.usuario_id
                WHERE u.tipo = "aluno"
                ORDER BY p.pontuacao DESC LIMIT 5';
$ranking = $conn->query($ranking_sql)->fetch_all(MYSQLI_ASSOC);

// Posição do usuário atual
$pos_sql = 'SELECT posicao FROM (
                SELECT usuario_id, RANK() OVER (ORDER BY pontuacao DESC) as posicao
                FROM progresso
            ) ranked WHERE usuario_id = ?';
$stmt = $conn->prepare($pos_sql);
$stmt->bind_param('i', $uid);
$stmt->execute();
$minha_pos = $stmt->get_result()->fetch_assoc()['posicao'] ?? '?';
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
</head>
<body>
<div class="app-layout">

    <!-- Sidebar -->
    <?php require_once 'includes/header.php'; ?>

    <!-- Conteúdo principal -->
    <main class="main-content">

        <!-- Título da página -->
        <div class="topbar">
            <div>
                <h1>👋 Olá, <?= htmlspecialchars(explode(' ', $_SESSION['nome'])[0]) ?>!</h1>
                <p>Bem-vindo de volta à sua aventura matemática!</p>
            </div>
            <div class="topbar-right">
                <a href="/site_antigravity/jogos.php" class="btn btn-primary">🎮 Jogar Agora</a>
            </div>
        </div>

        <!-- ---- BARRA DE XP ---- -->
        <div class="xp-section">
            <div class="xp-header">
                <div class="level-info">
                    <div class="level-badge">Nível <?= $nivel ?></div>
                    <div class="level-title">⭐ <?= $titulo ?></div>
                </div>
                <div class="xp-numbers">
                    <?= $xp ?> / <?= $xp_prox ?> XP
                </div>
            </div>
            <div class="xp-bar-wrap">
                <!-- A largura é definida como porcentagem do XP atual -->
                <div class="xp-bar-fill" style="width: <?= $pct_xp ?>%"></div>
            </div>
        </div>

        <!-- ---- CARDS DE ESTATÍSTICAS ---- -->
        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-info">
                    <div class="stat-value"><?= number_format($xp) ?></div>
                    <div class="stat-label">XP Total</div>
                </div>
            </div>

            <div class="stat-card accent">
                <div class="stat-icon">🏆</div>
                <div class="stat-info">
                    <div class="stat-value"><?= number_format($pontuacao) ?></div>
                    <div class="stat-label">Pontuação</div>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <div class="stat-value"><?= $acertos ?></div>
                    <div class="stat-label">Acertos</div>
                </div>
            </div>

            <div class="stat-card danger">
                <div class="stat-icon">❌</div>
                <div class="stat-info">
                    <div class="stat-value"><?= $erros ?></div>
                    <div class="stat-label">Erros</div>
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-icon">🎮</div>
                <div class="stat-info">
                    <div class="stat-value"><?= $jogos_feitos ?></div>
                    <div class="stat-label">Jogos Feitos</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">🎯</div>
                <div class="stat-info">
                    <div class="stat-value"><?= $taxa_acerto ?>%</div>
                    <div class="stat-label">Taxa de Acerto</div>
                </div>
            </div>

        </div>

        <!-- ---- GRID PRINCIPAL ---- -->
        <div class="dashboard-grid">

            <!-- Coluna esquerda: conquistas + histórico -->
            <div>

                <!-- Conquistas recentes -->
                <div class="card" style="margin-bottom:24px;">
                    <div class="card-title">🏅 Conquistas Recentes</div>
                    <?php if (empty($conquistas)): ?>
                        <p style="color:#b2bec3;text-align:center;padding:20px 0;">
                            Você ainda não conquistou nenhuma medalha.<br>
                            <a href="/site_antigravity/jogos.php" style="color:#6c63ff;">Comece jogando! 🎮</a>
                        </p>
                    <?php else: ?>
                        <div class="medals-grid">
                            <?php foreach ($conquistas as $c): ?>
                                <div class="medal-item" title="<?= htmlspecialchars($c['descricao']) ?>">
                                    <span class="medal-icon"><?= $c['icone'] ?></span>
                                    <span class="medal-name"><?= htmlspecialchars($c['medalha']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div style="margin-top:16px;text-align:center;">
                        <a href="/site_antigravity/conquistas.php" class="btn btn-outline btn-sm">Ver todas →</a>
                    </div>
                </div>

                <!-- Histórico de partidas -->
                <div class="card">
                    <div class="card-title">📋 Últimas Partidas</div>
                    <?php if (empty($historico)): ?>
                        <p style="color:#b2bec3;text-align:center;padding:20px 0;">
                            Nenhuma partida jogada ainda.
                        </p>
                    <?php else: ?>
                        <div style="overflow-x:auto;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Jogo</th>
                                        <th>Dificuldade</th>
                                        <th>Pontos</th>
                                        <th>Acertos</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historico as $h): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($h['jogo_nome']) ?></td>
                                        <td>
                                            <?php
                                            $d = $h['dificuldade'];
                                            $cls = ['facil'=>'success','medio'=>'warning','dificil'=>'danger'];
                                            ?>
                                            <span class="badge badge-<?= $cls[$d] ?? 'gray' ?>">
                                                <?= ucfirst($d) ?>
                                            </span>
                                        </td>
                                        <td><strong><?= $h['pontuacao'] ?></strong></td>
                                        <td><?= $h['acertos'] ?>/<?= $h['acertos'] + $h['erros'] ?></td>
                                        <td style="font-size:0.8rem;color:#b2bec3;">
                                            <?= date('d/m H:i', strtotime($h['jogado_em'])) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Coluna direita: ranking + ações rápidas -->
            <div>

                <!-- Mini Ranking -->
                <div class="card" style="margin-bottom:24px;">
                    <div class="card-title">🏆 Ranking Geral</div>
                    <div class="ranking-list">
                        <?php
                        $posIcons = ['🥇','🥈','🥉'];
                        foreach ($ranking as $r):
                            $pos = $r['posicao'];
                            $cls = ['gold','silver','bronze'][$pos-1] ?? '';
                            $isMe = false;
                            // Verifica se é o usuário atual
                            // Comparando nome (poderia ser melhor com ID)
                        ?>
                        <div class="ranking-item">
                            <div class="ranking-pos <?= $cls ?>">
                                <?= $posIcons[$pos-1] ?? $pos ?>
                            </div>
                            <div class="ranking-name"><?= htmlspecialchars($r['nome']) ?></div>
                            <div class="ranking-pts"><?= number_format($r['pontuacao']) ?> pts</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="margin-top:12px;text-align:center;font-size:0.85rem;color:#636e72;">
                        Sua posição: <strong style="color:#6c63ff;">#<?= $minha_pos ?></strong>
                    </div>
                    <div style="margin-top:12px;text-align:center;">
                        <a href="/site_antigravity/ranking.php" class="btn btn-outline btn-sm">Ranking Completo →</a>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="card">
                    <div class="card-title">🎮 Jogar Agora</div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <a href="/site_antigravity/jogos/jogo1.php" style="text-decoration:none;">
                            <div style="display:flex;align-items:center;gap:12px;padding:14px;background:#f0f2f5;border-radius:10px;transition:all 0.2s;" onmouseover="this.style.background='rgba(108,99,255,0.08)'" onmouseout="this.style.background='#f0f2f5'">
                                <span style="font-size:1.8rem;">⚔️</span>
                                <div>
                                    <div style="font-weight:700;font-size:0.9rem;color:#2d3436;">Batalha dos Inteiros</div>
                                    <div style="font-size:0.75rem;color:#636e72;">Números positivos e negativos</div>
                                </div>
                                <span style="margin-left:auto;color:#b2bec3;">›</span>
                            </div>
                        </a>
                        <a href="/site_antigravity/jogos/jogo2.php" style="text-decoration:none;">
                            <div style="display:flex;align-items:center;gap:12px;padding:14px;background:#f0f2f5;border-radius:10px;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,107,53,0.08)'" onmouseout="this.style.background='#f0f2f5'">
                                <span style="font-size:1.8rem;">🔐</span>
                                <div>
                                    <div style="font-weight:700;font-size:0.9rem;color:#2d3436;">Cofre das Equações</div>
                                    <div style="font-size:0.75rem;color:#636e72;">Equações de 1º grau</div>
                                </div>
                                <span style="margin-left:auto;color:#b2bec3;">›</span>
                            </div>
                        </a>
                        <a href="/site_antigravity/jogos/jogo3.php" style="text-decoration:none;">
                            <div style="display:flex;align-items:center;gap:12px;padding:14px;background:#f0f2f5;border-radius:10px;transition:all 0.2s;" onmouseover="this.style.background='rgba(46,204,113,0.08)'" onmouseout="this.style.background='#f0f2f5'">
                                <span style="font-size:1.8rem;">🛒</span>
                                <div>
                                    <div style="font-weight:700;font-size:0.9rem;color:#2d3436;">Loja MathPlay</div>
                                    <div style="font-size:0.75rem;color:#636e72;">Porcentagem e finanças</div>
                                </div>
                                <span style="margin-left:auto;color:#b2bec3;">›</span>
                            </div>
                        </a>
                        <a href="/site_antigravity/jogos/jogo4.php" style="text-decoration:none;">
                            <div style="display:flex;align-items:center;gap:12px;padding:14px;background:#f0f2f5;border-radius:10px;transition:all 0.2s;" onmouseover="this.style.background='rgba(243,156,18,0.08)'" onmouseout="this.style.background='#f0f2f5'">
                                <span style="font-size:1.8rem;">🔍</span>
                                <div>
                                    <div style="font-weight:700;font-size:0.9rem;color:#2d3436;">Detetive dos Gráficos</div>
                                    <div style="font-size:0.75rem;color:#636e72;">Estatística e gráficos</div>
                                </div>
                                <span style="margin-left:auto;color:#b2bec3;">›</span>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </main>
</div>
</body>
</html>

