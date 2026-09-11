<?php
// ============================================================
// MathPlay Solutions — Logout
// ============================================================
// Este arquivo encerra a sessão do usuário e redireciona
// para a página de login.
// ============================================================

// Inicia a sessão para poder destruí-la
session_start();

// Remove todos os dados da sessão
$_SESSION = [];

// Se houver um cookie de sessão, remove ele também
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// Destroi a sessão completamente
session_destroy();

// Redireciona para a página de login
header('Location: /site_antigravity/login.php');
exit();
?>

