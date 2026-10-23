<?php
// ============================================================
// Educa Play — Header/Navbar Reutilizável
// ============================================================
// Este arquivo é incluído em todas as páginas internas.
// Ele gera a sidebar de navegação com links e dados do usuário.
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nome da página atual (ex: dashboard.php, usuarios.php)
$pagina_atual = basename($_SERVER['PHP_SELF']);

// Verifica se está dentro da pasta /admin/
$esta_em_admin = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);

$inicial = strtoupper(substr($_SESSION['nome'] ?? 'U', 0, 1));
$avatar_nome = is_string($_SESSION['avatar_path'] ?? null) ? basename($_SESSION['avatar_path']) : '';
$avatar_url = preg_match('/\A[a-f0-9]{32}\.(?:jpg|png|webp|avif)\z/', $avatar_nome)
    ? '/site_antigravity/uploads/profile_photos/' . rawurlencode($avatar_nome)
    : null;

$xp    = $_SESSION['xp']    ?? 0;
$nivel = $_SESSION['nivel'] ?? 1;

$titulos = [
    1 => 'Iniciante',
    2 => 'Aprendiz',
    3 => 'Explorador',
    4 => 'Aventureiro',
    5 => 'Desafiador',
    6 => 'Mestre Educa Play'
];
$titulo_nivel = $titulos[$nivel] ?? 'Mestre Educa Play';

$xp_por_nivel = [0, 0, 100, 250, 500, 800, 1200];
$xp_proximo   = $xp_por_nivel[$nivel + 1] ?? 9999;
$xp_atual_nivel = $xp_por_nivel[$nivel] ?? 0;
$xp_diff      = $xp_proximo - $xp_atual_nivel;
$xp_progresso = ($xp_diff > 0) ? min(100, round(($xp - $xp_atual_nivel) / $xp_diff * 100)) : 100;

$is_admin = (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin');
?>

<?php require_once __DIR__ . '/accessibility.php'; ?>

<!-- ============ BOTÃO DE MENU MOBILE ============ -->
<button class="menu-toggle" id="menuToggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="sidebar">Menu</button>

<!-- ============ SIDEBAR ============ -->
<aside class="sidebar" id="sidebar" aria-label="Área do usuário">

    <!-- Logo -->
    <div class="sidebar-brand">
        <img
            class="sidebar-logo"
            src="/site_antigravity/assets/images/educa-play-logo.png"
            alt="Educa Play"
            width="72"
            height="72"
        >
    </div>

    <!-- Mini perfil do usuário -->
    <div class="sidebar-user">
        <div class="user-avatar">
            <?php if ($avatar_url !== null): ?>
                <img src="<?= htmlspecialchars($avatar_url) ?>" alt="" width="44" height="44">
            <?php else: ?>
                <?= htmlspecialchars($inicial) ?>
            <?php endif; ?>
        </div>
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?></div>
            <div class="user-level">Nível <?= $nivel ?> — <?= $titulo_nivel ?></div>
        </div>
    </div>

    <!-- Links de navegação -->
    <nav class="sidebar-nav" aria-label="Navegação principal">

        <div class="nav-section-label">Principal</div>

        <a href="/site_antigravity/dashboard.php"
           aria-current="<?= (!$esta_em_admin && $pagina_atual === 'dashboard.php') ? 'page' : 'false' ?>"
           class="nav-item <?= (!$esta_em_admin && $pagina_atual === 'dashboard.php') ? 'active' : '' ?>">
            Dashboard
        </a>

        <a href="/site_antigravity/jogos.php"
           aria-current="<?= $pagina_atual === 'jogos.php' ? 'page' : 'false' ?>"
           class="nav-item <?= $pagina_atual === 'jogos.php' ? 'active' : '' ?>">
            Jogos
        </a>

        <a href="/site_antigravity/ranking.php"
           aria-current="<?= $pagina_atual === 'ranking.php' ? 'page' : 'false' ?>"
           class="nav-item <?= $pagina_atual === 'ranking.php' ? 'active' : '' ?>">
            Ranking
        </a>

        <div class="nav-section-label">Meu Perfil</div>

        <a href="/site_antigravity/perfil.php"
           aria-current="<?= $pagina_atual === 'perfil.php' ? 'page' : 'false' ?>"
           class="nav-item <?= $pagina_atual === 'perfil.php' ? 'active' : '' ?>">
            Perfil
        </a>

        <a href="/site_antigravity/conquistas.php"
           aria-current="<?= $pagina_atual === 'conquistas.php' ? 'page' : 'false' ?>"
           class="nav-item <?= $pagina_atual === 'conquistas.php' ? 'active' : '' ?>">
            Conquistas
        </a>

        <a href="/site_antigravity/progresso.php"
           aria-current="<?= $pagina_atual === 'progresso.php' ? 'page' : 'false' ?>"
           class="nav-item <?= $pagina_atual === 'progresso.php' ? 'active' : '' ?>">
            Progresso
        </a>

        <?php if ($is_admin): ?>
        <div class="nav-section-label">Administração</div>

        <a href="/site_antigravity/admin/dashboard.php"
           aria-current="<?= ($esta_em_admin && $pagina_atual === 'dashboard.php') ? 'page' : 'false' ?>"
           class="nav-item <?= ($esta_em_admin && $pagina_atual === 'dashboard.php') ? 'active' : '' ?>">
            Painel Admin
        </a>

        <a href="/site_antigravity/admin/usuarios.php"
           aria-current="<?= ($esta_em_admin && $pagina_atual === 'usuarios.php') ? 'page' : 'false' ?>"
           class="nav-item <?= ($esta_em_admin && $pagina_atual === 'usuarios.php') ? 'active' : '' ?>">
            Alunos
        </a>

        <a href="/site_antigravity/admin/resultados.php"
           aria-current="<?= ($esta_em_admin && $pagina_atual === 'resultados.php') ? 'page' : 'false' ?>"
           class="nav-item <?= ($esta_em_admin && $pagina_atual === 'resultados.php') ? 'active' : '' ?>">
            Resultados
        </a>
        <?php endif; ?>

    </nav>

    <!-- Rodapé da sidebar -->
    <div class="sidebar-footer">
        <a href="/site_antigravity/logout.php" class="nav-item">
            Sair
        </a>
    </div>

</aside>

<!-- Script para abrir/fechar sidebar no mobile -->
<script>
    const menuToggle = document.getElementById('menuToggle');
    const sidebar    = document.getElementById('sidebar');

    menuToggle.addEventListener('click', function () {
        const aberto = sidebar.classList.toggle('open');
        menuToggle.setAttribute('aria-expanded', String(aberto));
        menuToggle.setAttribute('aria-label', aberto ? 'Fechar menu' : 'Abrir menu');
    });

    document.addEventListener('click', function (e) {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('open');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Abrir menu');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Abrir menu');
            menuToggle.focus();
        }
    });
</script>
