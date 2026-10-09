<?php
// ============================================================
// MathPlay Solutions — Perfil do Aluno
// ============================================================
require_once 'includes/verificar_login.php';
require_once 'includes/conexao.php';

$uid = $_SESSION['usuario_id'];

// Busca dados do usuário
$stmt = $conn->prepare('SELECT * FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Busca progresso
$stmt = $conn->prepare('SELECT * FROM progresso WHERE usuario_id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$prog = $stmt->get_result()->fetch_assoc();
$stmt->close();

$xp           = $prog['xp']           ?? 0;
$nivel        = $prog['nivel']        ?? 1;
$pontuacao    = $prog['pontuacao']    ?? 0;
$acertos      = $prog['acertos']      ?? 0;
$erros        = $prog['erros']        ?? 0;
$jogos_feitos = $prog['jogos_feitos'] ?? 0;

// Busca contagem de conquistas
$stmt = $conn->prepare('SELECT COUNT(*) as total FROM conquistas WHERE usuario_id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$total_medalhas = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Cálculo de taxa de acerto
$total_resp  = $acertos + $erros;
$taxa_acerto = ($total_resp > 0) ? round($acertos / $total_resp * 100) : 0;

// Cálculo de nível e XP
$titulos = [1=>'Iniciante',2=>'Aprendiz',3=>'Explorador',4=>'Aventureiro',5=>'Desafiador',6=>'Mestre MathPlay'];
$titulo  = $titulos[$nivel] ?? 'Mestre MathPlay';
$xp_limites = [0, 0, 100, 250, 500, 800, 1200, 9999];
$xp_prox    = $xp_limites[$nivel + 1] ?? 9999;
$xp_ini     = $xp_limites[$nivel]     ?? 0;
$pct_xp     = ($xp_prox > $xp_ini) ? min(100, round(($xp - $xp_ini) / ($xp_prox - $xp_ini) * 100)) : 100;

$inicial = strtoupper(substr($usuario['nome'] ?? 'U', 0, 1));
$membro_desde = date('d/m/Y', strtotime($usuario['criado_em'] ?? 'now'));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil — MathPlay Solutions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
</head>
<body>
<div class="app-layout">

    <?php require_once 'includes/header.php'; ?>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1><span class="google-icon" aria-hidden="true">person</span> Meu Perfil</h1>
                <p>Suas informações e estatísticas pessoais</p>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start;">

            <!-- ---- CARD DO PERFIL (esquerda) ---- -->
            <div>
                <div class="card" style="text-align:center;margin-bottom:24px;">
                    <!-- Avatar -->
                    <div style="width:90px;height:90px;background:linear-gradient(135deg,#6c63ff,#ff6b35);
                                border-radius:50%;display:flex;align-items:center;justify-content:center;
                                font-size:2.5rem;font-weight:700;color:#fff;margin:0 auto 16px;">
                        <?= $inicial ?>
                    </div>

                    <h2 style="font-size:1.3rem;font-weight:800;margin-bottom:4px;">
                        <?= htmlspecialchars($usuario['nome']) ?>
                    </h2>

                    <p style="color:#636e72;font-size:0.9rem;margin-bottom:8px;">
                        <?= htmlspecialchars($usuario['email']) ?>
                    </p>

                    <span class="badge badge-primary" style="font-size:0.8rem;">
                        <?php if ($usuario['tipo'] === 'admin'): ?>
                            <span class="google-icon" aria-hidden="true">school</span> Professor
                        <?php else: ?>
                            <span class="google-icon" aria-hidden="true">sports_esports</span> Aluno
                        <?php endif; ?>
                    </span>

                    <div style="margin:20px 0;padding:16px;background:#f0f2f5;border-radius:10px;">
                        <div style="font-size:0.8rem;color:#636e72;">Membro desde</div>
                        <div style="font-weight:700;color:#2d3436;"><?= $membro_desde ?></div>
                    </div>

                    <!-- Nível e XP -->
                    <div style="background:linear-gradient(135deg,#6c63ff,#a855f7);border-radius:12px;padding:16px;color:#fff;text-align:left;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                            <span style="font-weight:700;"><span class="google-icon" aria-hidden="true">star</span> Nível <?= $nivel ?></span>
                            <span style="font-size:0.85rem;opacity:0.85;"><?= $xp ?>/<?= $xp_prox ?> XP</span>
                        </div>
                        <div style="background:rgba(255,255,255,0.2);border-radius:50px;height:10px;overflow:hidden;">
                            <div style="height:100%;width:<?= $pct_xp ?>%;background:linear-gradient(90deg,#ffd700,#ff6b35);border-radius:50px;transition:width 1s ease;"></div>
                        </div>
                        <div style="text-align:center;font-size:0.85rem;margin-top:8px;opacity:0.9;"><?= $titulo ?></div>
                    </div>
                </div>

                <!-- Medalhas -->
                <div class="card">
                    <div class="card-title"><span class="google-icon" aria-hidden="true">military_tech</span> Medalhas</div>
                    <div style="text-align:center;padding:10px 0;">
                        <span class="google-icon profile-medal-icon" aria-hidden="true">military_tech</span>
                        <div style="font-size:3rem;font-weight:900;color:#f39c12;"><?= $total_medalhas ?></div>
                        <div style="font-size:0.85rem;color:#636e72;">de 7 possíveis</div>
                    </div>
                    <a href="/site_antigravity/conquistas.php" class="btn btn-outline btn-sm w-100" style="text-align:center;">Ver Conquistas →</a>
                </div>
            </div>

            <!-- ---- ESTATÍSTICAS (direita) ---- -->
            <div>
                <div class="card" style="margin-bottom:24px;">
                    <div class="card-title"><span class="google-icon" aria-hidden="true">analytics</span> Estatísticas Completas</div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:16px;">
                        <?php
                        $stats = [
                            ['emoji_events', 'Pontuação',   number_format($pontuacao),   '#6c63ff'],
                            ['star', 'XP Total',     number_format($xp),          '#a855f7'],
                            ['check_circle', 'Acertos',      $acertos,                    '#2ecc71'],
                            ['cancel', 'Erros',        $erros,                      '#e74c3c'],
                            ['sports_esports', 'Partidas',     $jogos_feitos,               '#ff6b35'],
                            ['track_changes', 'Taxa Acerto',  $taxa_acerto.'%',            '#f39c12'],
                        ];
                        foreach ($stats as [$icon, $label, $value, $color]): ?>
                        <div style="background:#f0f2f5;border-radius:12px;padding:16px;text-align:center;">
                            <div class="google-icon profile-stat-icon" aria-hidden="true"><?= $icon ?></div>
                            <div style="font-size:1.4rem;font-weight:800;color:<?= $color ?>;"><?= $value ?></div>
                            <div style="font-size:0.75rem;color:#636e72;font-weight:600;"><?= $label ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Resultados por jogo -->
                <div class="card">
                    <div class="card-title"><span class="google-icon" aria-hidden="true">sports_esports</span> Desempenho por Jogo</div>
                    <?php
                    // Busca estatísticas agregadas por jogo
                    $stmt = $conn->prepare('SELECT jogo_nome, COUNT(*) as partidas, SUM(pontuacao) as total_pts, SUM(acertos) as total_acertos, MAX(pontuacao) as melhor
                                           FROM resultados WHERE usuario_id = ? GROUP BY jogo_id, jogo_nome ORDER BY jogo_id');
                    $stmt->bind_param('i', $uid);
                    $stmt->execute();
                    $por_jogo = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    ?>
                    <?php if (empty($por_jogo)): ?>
                        <p style="color:#b2bec3;text-align:center;padding:20px;">
                            Você ainda não jogou. <a href="/site_antigravity/jogos.php" style="color:#6c63ff;">Comece agora! <span class="google-icon" aria-hidden="true">sports_esports</span></a>
                        </p>
                    <?php else: ?>
                        <div style="overflow-x:auto;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Jogo</th>
                                        <th>Partidas</th>
                                        <th>Total Pts</th>
                                        <th>Acertos</th>
                                        <th>Melhor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($por_jogo as $j): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($j['jogo_nome']) ?></td>
                                        <td><?= $j['partidas'] ?></td>
                                        <td><?= number_format($j['total_pts']) ?></td>
                                        <td><?= $j['total_acertos'] ?></td>
                                        <td style="color:#6c63ff;font-weight:700;"><?= $j['melhor'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </main>
</div>

<!-- CSS extra para responsividade do perfil -->
<style>
@media (max-width: 900px) {
    .main-content > div[style*="grid-template-columns:320px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

</body>
</html>
