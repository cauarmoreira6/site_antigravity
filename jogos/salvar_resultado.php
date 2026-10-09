<?php
// ============================================================
// MathPlay Solutions — Salvar Resultado do Jogo
// ============================================================
// Este arquivo recebe os dados de uma partida via AJAX (fetch)
// e salva no banco de dados. Também atualiza o progresso do
// aluno (XP, nível, acertos, etc.) e verifica conquistas.
// ============================================================

// Inicia a sessão para verificar login
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Retorna JSON com erro (o JavaScript vai tratar isso)
    echo json_encode(['sucesso' => false, 'erro' => 'Não autorizado']);
    exit();
}

require_once '../includes/conexao.php';

$uid = $_SESSION['usuario_id'];

// ---- Recebe os dados enviados pelo JavaScript ----
// $_POST contém os dados enviados via fetch() + FormData
$jogo_id     = (int)($_POST['jogo_id']     ?? 0);
$jogo_nome   = $_POST['jogo_nome']         ?? '';
$pontuacao   = (int)($_POST['pontuacao']   ?? 0);
$acertos     = (int)($_POST['acertos']     ?? 0);
$erros       = (int)($_POST['erros']       ?? 0);
$dificuldade = $_POST['dificuldade']       ?? 'facil';
$xp_ganho    = (int)($_POST['xp_ganho']   ?? 0);

// Validação básica dos dados recebidos
if ($jogo_id < 1 || $jogo_id > 8 || empty($jogo_nome)) {
    echo json_encode(['sucesso' => false, 'erro' => 'Dados inválidos']);
    exit();
}

// Sanitiza a dificuldade
if (!in_array($dificuldade, ['facil', 'medio', 'dificil'])) {
    $dificuldade = 'facil';
}

// ---- 1. Salva o resultado da partida ----
$stmt = $conn->prepare('INSERT INTO resultados (usuario_id, jogo_id, jogo_nome, pontuacao, acertos, erros, dificuldade) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('iississ', $uid, $jogo_id, $jogo_nome, $pontuacao, $acertos, $erros, $dificuldade);
$stmt->execute();
$stmt->close();

// ---- 2. Busca o progresso atual do usuário ----
$stmt = $conn->prepare('SELECT * FROM progresso WHERE usuario_id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$prog = $stmt->get_result()->fetch_assoc();
$stmt->close();

$xp_atual      = $prog['xp']           ?? 0;
$nivel_atual   = $prog['nivel']        ?? 1;
$pts_atual     = $prog['pontuacao']    ?? 0;
$ac_atual      = $prog['acertos']      ?? 0;
$err_atual     = $prog['erros']        ?? 0;
$jogos_atual   = $prog['jogos_feitos'] ?? 0;

// ---- 3. Calcula novos valores ----
$novo_xp      = $xp_atual + $xp_ganho;
$nova_pts     = $pts_atual + $pontuacao;
$novos_ac     = $ac_atual + $acertos;
$novos_err    = $err_atual + $erros;
$novos_jogos  = $jogos_atual + 1;

// ---- 4. Calcula o novo nível baseado no XP ----
// Tabela de XP mínimo por nível
$xp_niveis = [1=>0, 2=>100, 3=>250, 4=>500, 5=>800, 6=>1200];
$novo_nivel = 1;
foreach ($xp_niveis as $n => $xp_min) {
    if ($novo_xp >= $xp_min) {
        $novo_nivel = $n;
    }
}

// ---- 5. Atualiza o progresso no banco ----
$stmt = $conn->prepare('UPDATE progresso SET xp=?, nivel=?, pontuacao=?, acertos=?, erros=?, jogos_feitos=? WHERE usuario_id=?');
$stmt->bind_param('iiiiiii', $novo_xp, $novo_nivel, $nova_pts, $novos_ac, $novos_err, $novos_jogos, $uid);
$stmt->execute();
$stmt->close();

// Atualiza a sessão
$_SESSION['xp']    = $novo_xp;
$_SESSION['nivel'] = $novo_nivel;

// ---- 6. Verifica e concede conquistas (medalhas) ----
$novas_conquistas = [];

// Função auxiliar: verifica se o usuário já tem determinada medalha
function tem_medalha($conn, $uid, $nome) {
    $s = $conn->prepare('SELECT id FROM conquistas WHERE usuario_id=? AND medalha=?');
    $s->bind_param('is', $uid, $nome);
    $s->execute();
    $s->store_result();
    $resultado = $s->num_rows > 0;
    $s->close();
    return $resultado;
}

// Função auxiliar: concede uma medalha
function dar_medalha($conn, $uid, $icone, $nome, $descricao, &$novas) {
    if (!tem_medalha($conn, $uid, $nome)) {
        $s = $conn->prepare('INSERT INTO conquistas (usuario_id, medalha, descricao, icone) VALUES (?,?,?,?)');
        $s->bind_param('isss', $uid, $nome, $descricao, $icone);
        $s->execute();
        $s->close();
        $novas[] = ['icone' => $icone, 'nome' => $nome];
    }
}

//  Primeiro Passo — primeiro jogo completado
if ($novos_jogos >= 1) {
    dar_medalha($conn, $uid, '01', 'Primeiro Passo', 'Completou o primeiro jogo da plataforma', $novas_conquistas);
}

//  Jogador Frequente — 10 partidas
if ($novos_jogos >= 10) {
    dar_medalha($conn, $uid, '10', 'Jogador Frequente', 'Completou 10 partidas na plataforma', $novas_conquistas);
}

//  Mestre da Matemática — 100 acertos totais
if ($novos_ac >= 100) {
    dar_medalha($conn, $uid, '100', 'Mestre da Matemática', 'Acumulou 100 acertos no total', $novas_conquistas);
}

//  Sem Errar — partida com 0 erros
if ($erros === 0 && $acertos > 0) {
    dar_medalha($conn, $uid, '0', 'Sem Errar', 'Finalizou uma partida sem cometer nenhum erro', $novas_conquistas);
}

//  Comerciante Nato — Jogo 3 no difícil
if ($jogo_id === 3 && $dificuldade === 'dificil') {
    dar_medalha($conn, $uid, '$', 'Comerciante Nato', 'Completou a Loja MathPlay no nível difícil', $novas_conquistas);
}

//  Olho de Águia — Jogo 4 sem erros
if ($jogo_id === 4 && $erros === 0 && $acertos > 0) {
    dar_medalha($conn, $uid, 'OA', 'Olho de Águia', 'Completou o Detetive dos Gráficos sem erros', $novas_conquistas);
}

// ---- 7. Verifica se subiu de nível ----
$subiu_nivel = ($novo_nivel > $nivel_atual);

// ---- 8. Retorna a resposta em JSON para o JavaScript ----
echo json_encode([
    'sucesso'          => true,
    'novo_xp'          => $novo_xp,
    'novo_nivel'       => $novo_nivel,
    'subiu_nivel'      => $subiu_nivel,
    'novas_conquistas' => $novas_conquistas,
]);
?>
