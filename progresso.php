<?php
// ============================================================
// MathPlay Solutions — Progresso Detalhado
// ============================================================
require_once 'includes/verificar_login.php';
require_once 'includes/conexao.php';

$uid = $_SESSION['usuario_id'];

// Progresso geral
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

// Histórico completo
$stmt = $conn->prepare('SELECT * FROM resultados WHERE usuario_id = ? ORDER BY jogado_em DESC');
$stmt->bind_param('i', $uid);
$stmt->execute();
$historico = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Estatísticas por dificuldade
$stmt = $conn->prepare('SELECT dificuldade, COUNT(*) as partidas, SUM(acertos) as acertos, SUM(pontuacao) as pts FROM resultados WHERE usuario_id = ? GROUP BY dificuldade');
$stmt->bind_param('i', $uid);
$stmt->execute();
$por_dif = [];
foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $d) {
    $por_dif[$d['dificuldade']] = $d;
}
$stmt->close();

// Definição dos níveis
$niveis = [
    1 => ['nome'=>'Iniciante',       'xp_min'=>0,    'xp_max'=>99,   'cor'=>'#b2bec3'],
    2 => ['nome'=>'Aprendiz',        'xp_min'=>100,  'xp_max'=>249,  'cor'=>'#2ecc71'],
    3 => ['nome'=>'Explorador',      'xp_min'=>250,  'xp_max'=>499,  'cor'=>'#3498db'],
    4 => ['nome'=>'Aventureiro',     'xp_min'=>500,  'xp_max'=>799,  'cor'=>'#9b59b6'],
    5 => ['nome'=>'Desafiador',      'xp_min'=>800,  'xp_max'=>1199, 'cor'=>'#e67e22'],
    6 => ['nome'=>'Mestre MathPlay', 'xp_min'=>1200, 'xp_max'=>9999, 'cor'=>'#f39c12'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progresso — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
</head>
<body>
<div class="app-layout">

    <?php require_once 'includes/header.php'; ?>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Meu Progresso</h1>
                <p>Acompanhe seu desenvolvimento e evolução na plataforma</p>
            </div>
        </div>

        <!-- Mapa de Níveis -->
        <div class="card" style="margin-bottom:24px;">
            <div class="card-title">Jornada de Níveis</div>
            <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:8px;">
                <?php foreach ($niveis as $n => $info):
                    $conquistado = ($xp >= $info['xp_min']);
                    $atual       = ($nivel == $n);
                ?>
                <div style="min-width:130px;text-align:center;padding:16px 12px;border-radius:12px;
                            background:<?= $atual ? $info['cor'] : ($conquistado ? $info['cor'].'22' : '#f0f2f5') ?>;
                            border:2px solid <?= ($atual || $conquistado) ? $info['cor'] : '#e0e0e0' ?>;
                            flex-shrink:0;">
                    <div style="font-size:0.75rem;font-weight:700;color:<?= $atual ? '#fff' : ($conquistado ? $info['cor'] : '#b2bec3') ?>;">
                        Nível <?= $n ?><br><?= $info['nome'] ?>
                    </div>
                    <div style="font-size:0.65rem;color:<?= $atual ? 'rgba(255,255,255,0.8)' : '#b2bec3' ?>;margin-top:4px;">
                        <?= $info['xp_min'] ?> XP
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">

            <!-- Progresso por dificuldade -->
            <div class="card">
                <div class="card-title">Por Dificuldade</div>
                <?php
                $difs = [
                    'facil'   => ['nome'=>'Fácil',    'cor'=>'#2ecc71'],
                    'medio'   => ['nome'=>'Médio',    'cor'=>'#f39c12'],
                    'dificil' => ['nome'=>'Difícil',  'cor'=>'#e74c3c'],
                ];
                foreach ($difs as $key => $d):
                    $dados = $por_dif[$key] ?? ['partidas'=>0,'acertos'=>0,'pts'=>0];
                ?>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <div style="flex:1;">
                        <div style="display:flex;justify-content:space-between;font-size:0.85rem;font-weight:600;margin-bottom:4px;">
                            <span><?= $d['nome'] ?></span>
                            <span style="color:#636e72;"><?= $dados['partidas'] ?> partidas</span>
                        </div>
                        <div style="background:#e0e0e0;border-radius:50px;height:8px;overflow:hidden;">
                            <?php $pct_d = ($jogos_feitos > 0) ? round($dados['partidas'] / $jogos_feitos * 100) : 0; ?>
                            <div style="height:100%;width:<?= $pct_d ?>%;background:<?= $d['cor'] ?>;border-radius:50px;transition:width 0.8s ease;"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Resumo rápido -->
            <div class="card">
                <div class="card-title">Resumo</div>
                <?php
                $total_resp  = $acertos + $erros;
                $taxa_acerto = ($total_resp > 0) ? round($acertos / $total_resp * 100) : 0;
                $items = [
                    ['Total de Partidas',    $jogos_feitos],
                    ['Total de Respostas',   $total_resp],
                    ['Respostas Corretas',   $acertos],
                    ['Respostas Erradas',    $erros],
                    ['Taxa de Acerto',       $taxa_acerto.'%'],
                    ['Pontuação Total',      number_format($pontuacao)],
                ];
                foreach ($items as [$label, $val]): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;
                            padding:10px 0;border-bottom:1px solid #f0f0f0;">
                    <span style="color:#636e72;font-size:0.9rem;"><?= $label ?></span>
                    <span style="font-weight:700;color:#2d3436;"><?= $val ?></span>
                </div>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- Histórico completo -->
        <div class="card">
            <div class="card-title"> Histórico Completo de Partidas</div>
            <?php if (empty($historico)): ?>
                <p style="text-align:center;color:#b2bec3;padding:30px 0;">
                    Nenhuma partida registrada. <a href="/site_antigravity/jogos.php" style="color:#6c63ff;">Comece jogando!</a>
                </p>
            <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jogo</th>
                                <th>Dificuldade</th>
                                <th>Pontuação</th>
                                <th>Acertos</th>
                                <th>Erros</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historico as $i => $h):
                                $d = $h['dificuldade'];
                                $cls = ['facil'=>'success','medio'=>'warning','dificil'=>'danger'];
                            ?>
                            <tr>
                                <td style="color:#b2bec3;"><?= count($historico) - $i ?></td>
                                <td><?= htmlspecialchars($h['jogo_nome']) ?></td>
                                <td><span class="badge badge-<?= $cls[$d]??'gray' ?>"><?= ucfirst($d) ?></span></td>
                                <td><strong><?= $h['pontuacao'] ?></strong></td>
                                <td style="color:#2ecc71;"><?= $h['acertos'] ?></td>
                                <td style="color:#e74c3c;"><?= $h['erros'] ?></td>
                                <td style="color:#b2bec3;font-size:0.8rem;"><?= date('d/m/Y H:i', strtotime($h['jogado_em'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>
<style>
@media(max-width:768px){
    .main-content > div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns:1fr !important; }
}
</style>
</body>
</html>
