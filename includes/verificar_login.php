<?php
// ============================================================
// MathPlay Solutions — Verificação de Login
// ============================================================
// Este arquivo verifica se o usuário está logado.
// Deve ser incluído no início de qualquer página protegida.
// Se o usuário não estiver logado, redireciona para o login.
// ============================================================

// Inicia a sessão se ainda não foi iniciada
// A sessão permite manter dados do usuário entre as páginas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a variável de sessão 'usuario_id' existe
// Se não existir, significa que o usuário não está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para a página de login
    header('Location: /site_antigravity/login.php');
    exit(); // Para a execução do restante do código
}

// ============================================================
// Função: verificar_admin()
// Verifica se o usuário logado é admin/professor.
// Use esta função no início das páginas da área admin.
// ============================================================
function verificar_admin() {
    if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
        // Se não for admin, redireciona para o dashboard do aluno
        header('Location: /site_antigravity/dashboard.php');
        exit();
    }
}
?>

