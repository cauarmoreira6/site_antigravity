<?php
// ============================================================
// MathPlay Solutions — Cadastro
// ============================================================
session_start();

// Se já está logado, redireciona para o dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: /site_antigravity/dashboard.php');
    exit();
}

require_once 'includes/conexao.php';

$erro = '';

// ---- Processa o formulário de cadastro ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $conf  = trim($_POST['confirmar_senha'] ?? '');
    $tipo  = $_POST['tipo'] ?? 'aluno';

    // Validações básicas
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos obrigatórios.';

    } elseif (strlen($nome) < 3) {
        $erro = 'O nome deve ter pelo menos 3 letras.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // filter_var valida se o e-mail tem o formato correto
        $erro = 'Digite um e-mail válido.';

    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';

    } elseif ($senha !== $conf) {
        $erro = 'As senhas não coincidem.';

    } elseif (!in_array($tipo, ['aluno', 'admin'])) {
        $erro = 'Tipo de usuário inválido.';

    } else {
        // Verifica se o e-mail já está cadastrado
        $stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = 'Este e-mail já está cadastrado. Tente fazer login.';
            $stmt->close();
        } else {
            $stmt->close();

            // Gera o hash seguro da senha
            // password_hash() cria uma versão criptografada da senha
            // NUNCA armazene senhas em texto puro!
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere o novo usuário
            $stmt = $conn->prepare('INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $nome, $email, $hash, $tipo);

            if ($stmt->execute()) {
                $novo_id = $conn->insert_id; // Pega o ID do usuário recém-criado
                $stmt->close();

                // Cria o registro de progresso inicial para o novo usuário
                $stmt2 = $conn->prepare('INSERT INTO progresso (usuario_id, xp, nivel, pontuacao, acertos, erros, jogos_feitos) VALUES (?, 0, 1, 0, 0, 0, 0)');
                $stmt2->bind_param('i', $novo_id);
                $stmt2->execute();
                $stmt2->close();

                // Redireciona para o login com mensagem de sucesso
                header('Location: /site_antigravity/login.php?cadastro=ok');
                exit();
            } else {
                $erro = 'Erro ao criar conta. Tente novamente.';
                $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta — MathPlay Solutions</title>
    <link rel="stylesheet" href="/site_antigravity/css/login.css">
</head>
<body class="auth-body">

    <div class="auth-card">

        <!-- Logo -->
        <div class="auth-logo">
            <div class="logo-circle">🧮</div>
            <h1>Criar Conta</h1>
            <p>Junte-se à MathPlay e comece sua aventura!</p>
        </div>

        <!-- Mensagem de erro -->
        <?php if ($erro): ?>
            <div class="alert alert-error">❌ <?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <!-- Formulário de cadastro -->
        <form action="" method="POST">

            <div class="form-group">
                <label for="nome">👤 Nome Completo</label>
                <div class="input-group">
                    <span class="input-icon">👤</span>
                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        class="form-control"
                        placeholder="Seu nome completo"
                        value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                        required
                    >
                </div>
            </div>

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
                        placeholder="Mínimo 6 caracteres"
                        required
                    >
                </div>
                <span class="senha-dica">Use pelo menos 6 caracteres</span>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">🔒 Confirmar Senha</label>
                <div class="input-group">
                    <span class="input-icon">✅</span>
                    <input
                        type="password"
                        id="confirmar_senha"
                        name="confirmar_senha"
                        class="form-control"
                        placeholder="Repita sua senha"
                        required
                    >
                </div>
            </div>

            <!-- Seletor de tipo de usuário -->
            <div class="form-group">
                <label>🎓 Tipo de conta</label>
                <div class="tipo-selector">
                    <label class="tipo-option">
                        <input type="radio" name="tipo" value="aluno"
                            <?= (($_POST['tipo'] ?? 'aluno') === 'aluno') ? 'checked' : '' ?>>
                        <div class="tipo-label">
                            <span class="tipo-icon">🎮</span>
                            Aluno
                        </div>
                    </label>
                    <label class="tipo-option">
                        <input type="radio" name="tipo" value="admin"
                            <?= (($_POST['tipo'] ?? '') === 'admin') ? 'checked' : '' ?>>
                        <div class="tipo-label">
                            <span class="tipo-icon">👨‍🏫</span>
                            Professor
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-auth">🚀 Criar Minha Conta</button>
        </form>

        <!-- Link para login -->
        <div class="auth-switch">
            Já tem uma conta?
            <a href="/site_antigravity/login.php">Fazer login</a>
        </div>

        <div class="auth-back">
            <a href="/site_antigravity/index.php">← Voltar para a página inicial</a>
        </div>

    </div>

    <!-- Validação extra com JavaScript (verifica se as senhas coincidem) -->
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const senha  = document.getElementById('senha').value;
            const conf   = document.getElementById('confirmar_senha').value;

            if (senha !== conf) {
                e.preventDefault();  // Impede o envio do formulário
                alert('⚠️ As senhas não coincidem! Verifique e tente novamente.');
                document.getElementById('confirmar_senha').focus();
            }
        });
    </script>

</body>
</html>

