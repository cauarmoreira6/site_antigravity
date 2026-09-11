<?php
// ============================================================
// MathPlay Solutions — Login
// ============================================================
session_start();

// Se já está logado, redireciona direto para o dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: /site_antigravity/dashboard.php');
    exit();
}

require_once 'includes/conexao.php';

$erro    = '';
$sucesso = '';

// ---- Processa o formulário de login (quando o formulário é enviado) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura e limpa os dados enviados pelo formulário
    // trim() remove espaços extras no início e no fim
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Verifica se os campos foram preenchidos
    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        // Busca o usuário pelo e-mail no banco de dados
        // Usamos prepared statement para evitar SQL Injection
        $stmt = $conn->prepare('SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?');
        $stmt->bind_param('s', $email);   // 's' significa que o parâmetro é string
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario   = $resultado->fetch_assoc();
        $stmt->close();

        if ($usuario) {
            // password_verify() compara a senha digitada com o hash armazenado
            if (password_verify($senha, $usuario['senha'])) {

                // Login bem-sucedido! Cria as variáveis de sessão
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome']       = $usuario['nome'];
                $_SESSION['email']      = $usuario['email'];
                $_SESSION['tipo']       = $usuario['tipo'];

                // Busca o progresso do usuário para guardar na sessão
                $stmt2 = $conn->prepare('SELECT xp, nivel FROM progresso WHERE usuario_id = ?');
                $stmt2->bind_param('i', $usuario['id']); // 'i' = integer
                $stmt2->execute();
                $prog = $stmt2->get_result()->fetch_assoc();
                $stmt2->close();

                $_SESSION['xp']    = $prog['xp']    ?? 0;
                $_SESSION['nivel'] = $prog['nivel']  ?? 1;

                // Redireciona conforme o tipo de usuário
                if ($usuario['tipo'] === 'admin') {
                    header('Location: /site_antigravity/admin/dashboard.php');
                } else {
                    header('Location: /site_antigravity/dashboard.php');
                }
                exit();

            } else {
                $erro = 'E-mail ou senha incorretos. Tente novamente.';
            }
        } else {
            $erro = 'Nenhuma conta encontrada com esse e-mail.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/login.css">
</head>
<body class="auth-body">

    <div class="auth-card">

        <!-- Logo -->
        <div class="auth-logo">
            <div class="logo-circle">🧮</div>
            <h1>MathPlay Solutions</h1>
            <p>Entre na sua conta para continuar jogando</p>
        </div>

        <!-- Mensagem de erro -->
        <?php if ($erro): ?>
            <div class="alert alert-error">❌ <?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <!-- Mensagem de sucesso (vinda do cadastro) -->
        <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'ok'): ?>
            <div class="alert alert-success">✅ Conta criada com sucesso! Faça login.</div>
        <?php endif; ?>

        <!-- Formulário de login -->
        <!-- action="" envia para a mesma página (login.php), method="post" para não mostrar na URL -->
        <form action="" method="POST">

            <div class="form-group">
                <label for="email">📧 E-mail</label>
                <div class="input-group">
                    <span class="input-icon">📧</span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="seu@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="senha">🔒 Senha</label>
                <div class="input-group">
                    <span class="input-icon">🔒</span>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        class="form-control"
                        placeholder="Sua senha"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn-auth">🎮 Entrar na MathPlay</button>
        </form>

        <!-- Link para cadastro -->
        <div class="auth-switch">
            Não tem conta ainda?
            <a href="/site_antigravity/cadastro.php">Criar conta grátis</a>
        </div>

        <!-- Link para página inicial -->
        <div class="auth-back">
            <a href="/site_antigravity/index.php">← Voltar para a página inicial</a>
        </div>

    </div>

</body>
</html>

