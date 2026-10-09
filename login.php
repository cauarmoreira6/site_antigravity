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

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        $stmt = $conn->prepare('SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario   = $resultado->fetch_assoc();
        $stmt->close();

        if ($usuario) {
            if (password_verify($senha, $usuario['senha'])) {

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome']       = $usuario['nome'];
                $_SESSION['email']      = $usuario['email'];
                $_SESSION['tipo']       = $usuario['tipo'];

                $stmt2 = $conn->prepare('SELECT xp, nivel FROM progresso WHERE usuario_id = ?');
                $stmt2->bind_param('i', $usuario['id']);
                $stmt2->execute();
                $prog = $stmt2->get_result()->fetch_assoc();
                $stmt2->close();

                $_SESSION['xp']    = $prog['xp']    ?? 0;
                $_SESSION['nivel'] = $prog['nivel']  ?? 1;

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
$versao_css = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/login.css?v=<?= $versao_css ?>">
    <style>
        *, *::before, *::after {
            box-sizing: border-box !important;
        }
        .auth-card {
            max-width: 420px !important;
            width: 100% !important;
            padding: 32px 28px !important;
            overflow: hidden !important;
            border-radius: 16px !important;
            background: #ffffff !important;
        }
        .form-group {
            width: 100% !important;
            margin-bottom: 14px !important;
        }
        .input-group {
            width: 100% !important;
            display: block !important;
        }
        .form-control {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 40px !important;
            line-height: normal !important;
            padding: 0 14px !important;
            font-size: 0.9rem !important;
            border: 1.5px solid #dcdde1 !important;
            border-radius: 8px !important;
            background: #f8f9fa !important;
            color: #2d3436 !important;
            box-sizing: border-box !important;
            margin: 0 !important;
        }
        .form-control:focus {
            border-color: #6c63ff !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.12) !important;
            outline: none !important;
        }
        .form-control.password-input {
            padding-right: 48px !important;
        }
        .btn-auth {
            width: 100% !important;
            height: 42px !important;
            border-radius: 8px !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            box-sizing: border-box !important;
            margin-top: 6px !important;
            cursor: pointer !important;
        }
    </style>
</head>
<body class="auth-body">

    <div class="auth-card">

        <!-- Logo/Título -->
        <div class="auth-logo">
            <h1>MathPlay Solutions</h1>
            <p>Entre na sua conta para acessar os conteúdos</p>
        </div>

        <!-- Mensagens de retorno -->
        <?php if ($erro): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'ok'): ?>
            <div class="alert alert-success">Conta criada com sucesso! Faça login.</div>
        <?php endif; ?>

        <!-- Formulário de login -->
        <form action="" method="POST">

            <div class="form-group">
                <label for="email">E-mail</label>
                <div class="input-group">
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
                <label for="senha">Senha</label>
                <div class="input-group">
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        class="form-control password-input"
                        placeholder="Sua senha"
                        required
                    >
                    <button type="button" class="password-toggle" aria-label="Mostrar senha" aria-pressed="false" aria-controls="senha">
                        <svg class="password-toggle-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 5c-5 0-9.27 3.11-11 7 1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-auth">Entrar na MathPlay</button>
        </form>

        <!-- Link para cadastro -->
        <div class="auth-switch">
            Não tem conta ainda?
            <a href="/site_antigravity/cadastro.php">Criar conta</a>
        </div>

        <!-- Link para página inicial -->
        <div class="auth-back">
            <a href="/site_antigravity/index.php">Voltar para a página inicial</a>
        </div>

    </div>

    <script src="/site_antigravity/js/password-toggle.js"></script>
</body>
</html>
