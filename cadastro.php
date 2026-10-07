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

            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $conn->prepare('INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $nome, $email, $hash, $tipo);

            if ($stmt->execute()) {
                $novo_id = $conn->insert_id;
                $stmt->close();

                // Cria o registro de progresso inicial
                $stmt2 = $conn->prepare('INSERT INTO progresso (usuario_id, xp, nivel, pontuacao, acertos, erros, jogos_feitos) VALUES (?, 0, 1, 0, 0, 0, 0)');
                $stmt2->bind_param('i', $novo_id);
                $stmt2->execute();
                $stmt2->close();

                header('Location: /site_antigravity/login.php?cadastro=ok');
                exit();
            } else {
                $erro = 'Erro ao criar conta. Tente novamente.';
                $stmt->close();
            }
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
    <title>Criar Conta — MathPlay Solutions</title>
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
        
        /* Seletor de Tipo com destaque nítido */
        .tipo-selector {
            display: flex !important;
            gap: 12px !important;
            width: 100% !important;
            margin-top: 4px !important;
        }
        .tipo-option {
            flex: 1 !important;
            cursor: pointer !important;
            display: block !important;
        }
        .tipo-option input[type="radio"] {
            display: none !important;
        }
        .tipo-label {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 40px !important;
            border: 2px solid #dcdde1 !important;
            border-radius: 8px !important;
            font-size: 0.88rem !important;
            font-weight: 600 !important;
            color: #636e72 !important;
            background: #f8f9fa !important;
            cursor: pointer !important;
            box-sizing: border-box !important;
            width: 100% !important;
            transition: all 0.2s ease !important;
            user-select: none !important;
        }
        .tipo-label:hover {
            border-color: #a29bfe !important;
            background: #ffffff !important;
        }
        
        /* Efeito de destaque quando SELECIONADO: fundo roxo escuro, texto branco e sombra */
        .tipo-option input[type="radio"]:checked + .tipo-label {
            border-color: #6c63ff !important;
            background: #6c63ff !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 12px rgba(108, 99, 255, 0.35) !important;
            transform: translateY(-1px) !important;
        }
        
        .btn-auth {
            width: 100% !important;
            height: 42px !important;
            border-radius: 8px !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            box-sizing: border-box !important;
            margin-top: 10px !important;
            cursor: pointer !important;
        }
    </style>
</head>
<body class="auth-body">

    <div class="auth-card">

        <!-- Título -->
        <div class="auth-logo">
            <h1>Criar Conta</h1>
            <p>Junte-se à MathPlay e comece sua jornada educacional</p>
        </div>

        <!-- Mensagem de erro -->
        <?php if ($erro): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <!-- Formulário de cadastro -->
        <form action="" method="POST">

            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <div class="input-group">
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
                        class="form-control"
                        placeholder="Mínimo 6 caracteres"
                        required
                    >
                </div>
                <span class="senha-dica">Use pelo menos 6 caracteres</span>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <div class="input-group">
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

            <!-- Seletor de tipo de usuário com destaque imediato -->
            <div class="form-group">
                <label>Tipo de Conta</label>
                <div class="tipo-selector">
                    <label class="tipo-option">
                        <input type="radio" name="tipo" value="aluno"
                            <?= (($_POST['tipo'] ?? 'aluno') === 'aluno') ? 'checked' : '' ?>>
                        <div class="tipo-label">Aluno</div>
                    </label>
                    <label class="tipo-option">
                        <input type="radio" name="tipo" value="admin"
                            <?= (($_POST['tipo'] ?? '') === 'admin') ? 'checked' : '' ?>>
                        <div class="tipo-label">Professor</div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-auth">Criar Minha Conta</button>
        </form>

        <!-- Link para login -->
        <div class="auth-switch">
            Já tem uma conta?
            <a href="/site_antigravity/login.php">Fazer login</a>
        </div>

        <div class="auth-back">
            <a href="/site_antigravity/index.php">Voltar para a página inicial</a>
        </div>

    </div>

    <!-- Validação no cliente -->
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const senha  = document.getElementById('senha').value;
            const conf   = document.getElementById('confirmar_senha').value;

            if (senha !== conf) {
                e.preventDefault();
                alert('As senhas não coincidem. Verifique e tente novamente.');
                document.getElementById('confirmar_senha').focus();
            }
        });
    </script>

</body>
</html>
