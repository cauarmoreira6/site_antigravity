<?php
// ============================================================
// Educa Play — Admin: Resultados e Histórico
// ============================================================
require_once '../includes/verificar_login.php';
verificar_admin();
require_once '../includes/conexao.php';

// Filtros opcionais por jogo e sala.
$filtro_jogo = (int)($_GET['jogo'] ?? 0);
$salas_disponiveis = [];
foreach (range(6, 9) as $ano) {
    foreach (['A', 'B'] as $turma) {
        $salas_disponiveis[] = [
            'codigo' => $ano . $turma,
            'serie' => $ano . 'º ano',
            'turma' => $turma,
        ];
    }
}

$filtro_sala = $_GET['sala'] ?? '';
$sala_selecionada = null;
foreach ($salas_disponiveis as $sala) {
    if ($filtro_sala === $sala['codigo']) {
        $sala_selecionada = $sala;
        break;
    }
}

$condicoes = [];
$parametros = [];
if ($filtro_jogo >= 1 && $filtro_jogo <= 8) {
    $condicoes[] = 'r.jogo_id = ?';
    $parametros[] = $filtro_jogo;
}
if ($sala_selecionada !== null) {
    $condicoes[] = 'u.serie = ? AND u.turma = ?';
    $parametros[] = $sala_selecionada['serie'];
    $parametros[] = $sala_selecionada['turma'];
}

$sql = "
    SELECT r.*, u.nome, u.email, u.serie, u.turma
    FROM resultados r
    JOIN usuarios u ON u.id = r.usuario_id
    " . (empty($condicoes) ? '' : 'WHERE ' . implode(' AND ', $condicoes)) . "
    ORDER BY r.jogado_em DESC
    LIMIT 100
";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    throw new RuntimeException('Não foi possível preparar a consulta de resultados.');
}
if (!$stmt->execute($parametros)) {
    throw new RuntimeException('Não foi possível carregar os resultados.');
}
$resultados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Partidas — Educa Play</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css?v=20261023-logo">
    <style>
        .filter-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 42px;
            padding: 10px 20px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, #6c63ff, #5148c8);
            color: #fff;
            font: inherit;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(108, 99, 255, 0.25);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .filter-submit:hover {
            background: linear-gradient(135deg, #5a52d5, #4038ad);
            box-shadow: 0 6px 16px rgba(108, 99, 255, 0.35);
            transform: translateY(-2px);
        }

        .filter-submit:active {
            box-shadow: 0 2px 6px rgba(108, 99, 255, 0.25);
            transform: translateY(0);
        }

        .filter-submit svg {
            width: 18px;
            height: 18px;
            flex: 0 0 auto;
        }

        .sala-select {
            min-width: 190px;
            min-height: 42px;
            padding: 9px 40px 9px 14px;
            border: 1px solid #d9d5ff;
            border-radius: 10px;
            appearance: none;
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%235148c8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            color: #2d3436;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .sala-select:hover {
            border-color: #8a82ff;
        }

        #filtroSala:focus-visible {
            border-color: #6c63ff;
            outline: none !important;
            outline-offset: 0;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
        }

        @media (max-width: 520px) {
            .filter-submit {
                width: 100%;
            }

            .sala-select {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="app-layout">

    <?php require_once '../includes/header.php'; ?>

    <main id="main-content" class="main-content" tabindex="-1">
        <div class="topbar">
            <div>
                <h1> Relatório de Resultados</h1>
                <p>Histórico detalhado de todas as partidas jogadas</p>
            </div>
            <a href="/site_antigravity/admin/dashboard.php" class="btn btn-outline btn-sm">← Voltar ao Painel</a>
        </div>

        <!-- Filtros rápidos -->
        <div class="card" style="margin-bottom:20px;padding:16px 20px;">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span id="filtroJogoLabel" style="font-weight:700;font-size:0.9rem;color:#2d3436;">Filtrar por Jogo:</span>
                <a href="/site_antigravity/admin/resultados.php<?= $sala_selecionada !== null ? '?sala=' . urlencode($sala_selecionada['codigo']) : '' ?>" class="btn btn-sm <?= $filtro_jogo === 0 ? 'btn-primary' : 'btn-outline' ?>">Todos</a>
                <?php
                $nomes_jogos = [
                    1 => 'Inteiros',
                    2 => 'Equações',
                    3 => 'Loja',
                    4 => 'Gráficos',
                    5 => 'Revisor',
                    6 => 'Metáforas',
                    7 => 'Histórias',
                    8 => 'Robôs',
                ];
                foreach ($nomes_jogos as $jogo_id => $nome_jogo):
                    $parametros_link = ['jogo' => $jogo_id];
                    if ($sala_selecionada !== null) {
                        $parametros_link['sala'] = $sala_selecionada['codigo'];
                    }
                ?>
                <a href="/site_antigravity/admin/resultados.php?<?= htmlspecialchars(http_build_query($parametros_link)) ?>" class="btn btn-sm <?= $filtro_jogo === $jogo_id ? 'btn-primary' : 'btn-outline' ?>"><?= htmlspecialchars($nome_jogo) ?></a>
                <?php endforeach; ?>
            </div>
            <form method="get" action="/site_antigravity/admin/resultados.php" style="display:flex;align-items:end;gap:12px;flex-wrap:wrap;margin-top:16px;">
                <?php if ($filtro_jogo >= 1 && $filtro_jogo <= 8): ?>
                    <input type="hidden" name="jogo" value="<?= $filtro_jogo ?>">
                <?php endif; ?>
                <div>
                    <label for="filtroSala" style="display:block;margin-bottom:6px;font-weight:700;">Filtrar por sala:</label>
                    <select id="filtroSala" name="sala" class="sala-select">
                        <option value="">Todas as salas</option>
                        <?php foreach ($salas_disponiveis as $sala): ?>
                            <option value="<?= htmlspecialchars($sala['codigo']) ?>" <?= ($sala_selecionada['codigo'] ?? '') === $sala['codigo'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sala['codigo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="filter-submit">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Filtrar sala
                </button>
                <?php if ($sala_selecionada !== null): ?>
                    <a class="btn btn-outline btn-sm" href="/site_antigravity/admin/resultados.php<?= $filtro_jogo >= 1 && $filtro_jogo <= 8 ? '?jogo=' . $filtro_jogo : '' ?>">Limpar sala</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <div class="card-title"> Partidas Registradas (Exibindo até 100 mais recentes)</div>
            <?php if (empty($resultados)): ?>
                <p style="color:#b2bec3;padding:30px;text-align:center;">Nenhuma partida encontrada com o filtro selecionado.</p>
            <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Sala</th>
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
                                <td><?= htmlspecialchars(preg_replace('/º ano$/u', '', $r['serie'] ?? '') . ($r['turma'] ?? '')) ?: '—' ?></td>
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
