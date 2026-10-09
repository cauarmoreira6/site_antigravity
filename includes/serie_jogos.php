<?php
require_once __DIR__ . '/conexao.php';

$ano_escolar = 9;
$serie_escolar = '9º ano';

if (($_SESSION['tipo'] ?? '') === 'aluno') {
    $stmt = $conn->prepare('SELECT serie FROM usuarios WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['usuario_id']);
    $stmt->execute();
    $usuario_serie = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $series_validas = [
        '6º ano' => 6,
        '7º ano' => 7,
        '8º ano' => 8,
        '9º ano' => 9,
    ];
    $serie_escolar = $usuario_serie['serie'] ?? '';
    $ano_escolar = $series_validas[$serie_escolar] ?? null;
}

if ($ano_escolar === null) {
    http_response_code(409);
    exit('Sua série não está cadastrada. Peça ao professor para atualizar seu cadastro antes de jogar.');
}
