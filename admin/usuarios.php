<?php
// ============================================================
// MathPlay Solutions — Admin: Gerenciamento de Alunos
// ============================================================
require_once '../includes/verificar_login.php';
verificar_admin();
require_once '../includes/conexao.php';

// Busca todos os alunos cadastrados com progresso
$alunos = $conn->query("
    SELECT u.id, u.nome, u.email, u.serie, u.turma, u.criado_em,
           p.xp, p.nivel, p.pontuacao, p.acertos, p.erros, p.jogos_feitos
    FROM usuarios u
    LEFT JOIN progresso p ON p.usuario_id = u.id
    WHERE u.tipo = 'aluno'
    ORDER BY u.criado_em DESC
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css">
</head>
<body>
<div class="app-layout">

    <?php require_once '../includes/header.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div>
                <h1> Alunos Cadastrados</h1>
                <p>Lista completa de estudantes matriculados na plataforma</p>
            </div>
            <a href="/site_antigravity/admin/dashboard.php" class="btn btn-outline btn-sm">← Voltar ao Painel</a>
        </div>

        <div class="card">
            <div class="card-title"> Relação de Estudantes (Total: <?= count($alunos) ?>)</div>
            <?php if (empty($alunos)): ?>
                <p style="color:#b2bec3;padding:30px;text-align:center;">Nenhum aluno cadastrado ainda.</p>
            <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Série</th>
                                <th>Turma</th>
                                <th>Nível</th>
                                <th>XP</th>
                                <th>Pontuação</th>
                                <th>Jogos</th>
                                <th>Acertos / Erros</th>
                                <th>Data Cadastro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos as $a): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($a['nome']) ?></strong></td>
                                <td><?= htmlspecialchars($a['email']) ?></td>
                                <td><?= htmlspecialchars($a['serie'] ?? '') ?: '—' ?></td>
                                <td><?= htmlspecialchars($a['turma'] ?? '') ?: '—' ?></td>
                                <td><span class="badge badge-primary">Nv. <?= $a['nivel'] ?? 1 ?></span></td>
                                <td><?= number_format($a['xp'] ?? 0) ?></td>
                                <td><strong><?= number_format($a['pontuacao'] ?? 0) ?></strong></td>
                                <td><?= $a['jogos_feitos'] ?? 0 ?></td>
                                <td>
                                    <span style="color:#2ecc71;font-weight:700;"><?= $a['acertos'] ?? 0 ?></span> / 
                                    <span style="color:#e74c3c;font-weight:700;"><?= $a['erros'] ?? 0 ?></span>
                                </td>
                                <td style="color:#b2bec3;font-size:0.8rem;"><?= date('d/m/Y', strtotime($a['criado_em'])) ?></td>
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
