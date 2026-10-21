<?php
// ============================================================
// MathPlay Solutions — Perfil do Aluno
// ============================================================
require_once 'includes/verificar_login.php';
require_once 'includes/conexao.php';

$uid = $_SESSION['usuario_id'];
$erro_foto = '';
$sucesso_foto = '';
$diretorio_fotos = __DIR__ . '/uploads/profile_photos';
$prefixo_fotos = 'uploads/profile_photos/';

function removerFotoPerfilAnterior($diretorio, $avatar_path)
{
    if (!is_string($avatar_path)) {
        return;
    }

    $nome_arquivo = basename($avatar_path);
    if (!preg_match('/\A[a-f0-9]{32}\.(?:jpg|png|webp|avif)\z/', $nome_arquivo)) {
        return;
    }

    $caminho = $diretorio . DIRECTORY_SEPARATOR . $nome_arquivo;
    if (is_file($caminho) && !unlink($caminho)) {
        error_log('Não foi possível remover um arquivo antigo de foto de perfil.');
    }
}

if (empty($_SESSION['csrf_foto_perfil'])) {
    $_SESSION['csrf_foto_perfil'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_foto_perfil'])) {
    $token_enviado = $_POST['csrf_foto_perfil'] ?? '';
    if (!is_string($token_enviado) || !hash_equals($_SESSION['csrf_foto_perfil'], $token_enviado)) {
        $erro_foto = 'Não foi possível validar a solicitação. Atualize a página e tente novamente.';
    } else {
        $stmt = $conn->prepare('SELECT avatar_path FROM usuarios WHERE id = ?');
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $avatar_atual = $stmt->get_result()->fetch_assoc()['avatar_path'] ?? null;
        $stmt->close();

        if ($_POST['acao_foto_perfil'] === 'remover') {
            $stmt = $conn->prepare('UPDATE usuarios SET avatar_path = NULL WHERE id = ?');
            $stmt->bind_param('i', $uid);
            if ($stmt->execute()) {
                $_SESSION['avatar_path'] = null;
                $sucesso_foto = 'Foto de perfil removida.';
                removerFotoPerfilAnterior($diretorio_fotos, $avatar_atual);
            } else {
                $erro_foto = 'Não foi possível remover a foto. Tente novamente.';
            }
            $stmt->close();
        } elseif ($_POST['acao_foto_perfil'] === 'enviar') {
            $arquivo = $_FILES['foto_perfil'] ?? null;
            if (!is_array($arquivo) || !isset($arquivo['error']) || !is_int($arquivo['error'])) {
                $erro_foto = 'Selecione uma imagem para enviar.';
            } elseif ($arquivo['error'] !== UPLOAD_ERR_OK) {
                $erro_foto = match ($arquivo['error']) {
                    UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'A imagem deve ter no máximo 5 MB.',
                    UPLOAD_ERR_NO_FILE => 'Selecione uma imagem para enviar.',
                    default => 'Não foi possível receber a imagem. Tente novamente.',
                };
            } elseif (!isset($arquivo['size']) || !is_numeric($arquivo['size'])) {
                $erro_foto = 'Não foi possível validar o tamanho da imagem.';
            } elseif ($arquivo['size'] > 5 * 1024 * 1024) {
                $erro_foto = 'A imagem deve ter no máximo 5 MB.';
            } elseif (!isset($arquivo['tmp_name']) || !is_string($arquivo['tmp_name']) || !is_uploaded_file($arquivo['tmp_name'])) {
                $erro_foto = 'O arquivo enviado não pôde ser validado.';
            } else {
                $imagem = getimagesize($arquivo['tmp_name']);
                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($arquivo['tmp_name']);
                $tipos_permitidos = [
                    'image/jpeg' => ['extensao' => 'jpg', 'tipo' => IMAGETYPE_JPEG],
                    'image/png' => ['extensao' => 'png', 'tipo' => IMAGETYPE_PNG],
                    'image/webp' => ['extensao' => 'webp', 'tipo' => IMAGETYPE_WEBP],
                    'image/avif' => ['extensao' => 'avif', 'tipo' => IMAGETYPE_AVIF],
                ];

                if ($imagem === false || !isset($tipos_permitidos[$mime]) || $imagem[2] !== $tipos_permitidos[$mime]['tipo']) {
                    $erro_foto = 'Envie uma imagem JPG, PNG ou WebP.';
                } elseif ($imagem[0] > 6000 || $imagem[1] > 6000 || $imagem[0] * $imagem[1] > 20000000) {
                    $erro_foto = 'A imagem tem dimensões muito grandes. Reduza o tamanho e tente novamente.';
                } else {
                    if (!is_dir($diretorio_fotos) && !mkdir($diretorio_fotos, 0755, true) && !is_dir($diretorio_fotos)) {
                        $erro_foto = 'Não foi possível preparar o armazenamento da foto. Tente novamente mais tarde.';
                    } else {
                        $nome_arquivo = bin2hex(random_bytes(16)) . '.' . $tipos_permitidos[$mime]['extensao'];
                        $caminho_novo = $diretorio_fotos . DIRECTORY_SEPARATOR . $nome_arquivo;
                        if (!move_uploaded_file($arquivo['tmp_name'], $caminho_novo)) {
                            $erro_foto = 'Não foi possível salvar a foto. Tente novamente.';
                        } else {
                            $avatar_novo = $prefixo_fotos . $nome_arquivo;
                            $stmt = $conn->prepare('UPDATE usuarios SET avatar_path = ? WHERE id = ?');
                            $stmt->bind_param('si', $avatar_novo, $uid);
                            if ($stmt->execute()) {
                                $_SESSION['avatar_path'] = $avatar_novo;
                                $sucesso_foto = 'Foto de perfil atualizada.';
                                removerFotoPerfilAnterior($diretorio_fotos, $avatar_atual);
                            } else {
                                if (!unlink($caminho_novo)) {
                                    error_log('Não foi possível remover o novo arquivo de foto após falha ao atualizar o banco.');
                                }
                                $erro_foto = 'Não foi possível atualizar a foto no perfil. Tente novamente.';
                            }
                            $stmt->close();
                        }
                    }
                }
            }
        }
    }
}

// Busca dados do usuário
$stmt = $conn->prepare('SELECT * FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Busca progresso
$stmt = $conn->prepare('SELECT * FROM progresso WHERE usuario_id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$prog = $stmt->get_result()->fetch_assoc();
$stmt->close();

$xp           = $prog['xp']           ?? 0;
$nivel        = $prog['nivel']        ?? 1;
$pontuacao    = $prog['pontuacao']    ?? 0;
$acertos      = $prog['acertos']      ?? 0;
$erros        = $prog['erros']        ?? 0;
$jogos_feitos = $prog['jogos_feitos'] ?? 0;

// Busca contagem de conquistas
$stmt = $conn->prepare('SELECT COUNT(*) as total FROM conquistas WHERE usuario_id = ?');
$stmt->bind_param('i', $uid);
$stmt->execute();
$total_medalhas = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Cálculo de taxa de acerto
$total_resp  = $acertos + $erros;
$taxa_acerto = ($total_resp > 0) ? round($acertos / $total_resp * 100) : 0;

// Cálculo de nível e XP
$titulos = [1=>'Iniciante',2=>'Aprendiz',3=>'Explorador',4=>'Aventureiro',5=>'Desafiador',6=>'Mestre MathPlay'];
$titulo  = $titulos[$nivel] ?? 'Mestre MathPlay';
$xp_limites = [0, 0, 100, 250, 500, 800, 1200, 9999];
$xp_prox    = $xp_limites[$nivel + 1] ?? 9999;
$xp_ini     = $xp_limites[$nivel]     ?? 0;
$pct_xp     = ($xp_prox > $xp_ini) ? min(100, round(($xp - $xp_ini) / ($xp_prox - $xp_ini) * 100)) : 100;

$inicial = strtoupper(substr($usuario['nome'] ?? 'U', 0, 1));
$avatar_path = $usuario['avatar_path'] ?? null;
$_SESSION['avatar_path'] = $avatar_path;
$avatar_nome = is_string($avatar_path) ? basename($avatar_path) : '';
$avatar_url = preg_match('/\A[a-f0-9]{32}\.(?:jpg|png|webp|avif)\z/', $avatar_nome)
    ? '/site_antigravity/uploads/profile_photos/' . rawurlencode($avatar_nome)
    : null;
$membro_desde = date('d/m/Y', strtotime($usuario['criado_em'] ?? 'now'));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil — MathPlay Solutions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="/site_antigravity/css/dashboard.css?v=20260616-accessibility">
</head>
<body>
<div class="app-layout">

    <?php require_once 'includes/header.php'; ?>

    <main id="main-content" class="main-content" tabindex="-1">

        <div class="topbar">
            <div>
                <h1><span class="google-icon" aria-hidden="true">person</span> Meu Perfil</h1>
                <p>Suas informações e estatísticas pessoais</p>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:minmax(0,320px) minmax(0,1fr);gap:24px;align-items:start;">

            <!-- ---- CARD DO PERFIL (esquerda) ---- -->
            <div>
                <div class="card" style="text-align:center;margin-bottom:24px;">
                    <!-- Avatar -->
                    <div class="profile-avatar">
                        <?php if ($avatar_url !== null): ?>
                            <img src="<?= htmlspecialchars($avatar_url) ?>" alt="Foto de perfil de <?= htmlspecialchars($usuario['nome']) ?>" width="90" height="90">
                        <?php else: ?>
                            <?= htmlspecialchars($inicial) ?>
                        <?php endif; ?>
                    </div>

                    <form class="profile-photo-form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_foto_perfil" value="<?= htmlspecialchars($_SESSION['csrf_foto_perfil']) ?>">
                        <label class="profile-photo-label" for="foto_perfil">Foto de perfil</label>
                        <div class="profile-file-picker">
                            <input id="foto_perfil" name="foto_perfil" type="file" accept="image/jpeg,image/png,image/webp,image/avif,.avif" required>
                            <label class="profile-file-button" for="foto_perfil">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19 5h-3.17L14 3H8L6.17 5H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm1 14H4V7h3.05l1.83-2h4.24l1.83 2H20v12ZM12 8a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm0 8a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                                </svg>
                                Escolher foto
                            </label>
                            <span class="profile-file-name" id="profileFileName" aria-live="polite">JPG, PNG, WebP ou AVIF · até 5 MB</span>
                        </div>
                        <button class="btn btn-primary btn-sm" type="submit" name="acao_foto_perfil" value="enviar">Enviar foto</button>
                        <?php if ($avatar_url !== null): ?>
                            <button class="btn btn-outline btn-sm" type="submit" name="acao_foto_perfil" value="remover" formnovalidate>Remover foto</button>
                        <?php endif; ?>
                    </form>
                    <?php if ($erro_foto !== ''): ?>
                        <p class="profile-photo-message profile-photo-error" role="alert"><?= htmlspecialchars($erro_foto) ?></p>
                    <?php elseif ($sucesso_foto !== ''): ?>
                        <p class="profile-photo-message profile-photo-success" role="status"><?= htmlspecialchars($sucesso_foto) ?></p>
                    <?php endif; ?>

                    <h2 style="font-size:1.3rem;font-weight:800;margin-bottom:4px;">
                        <?= htmlspecialchars($usuario['nome']) ?>
                    </h2>

                    <p style="color:#636e72;font-size:0.9rem;margin-bottom:8px;">
                        <?= htmlspecialchars($usuario['email']) ?>
                    </p>

                    <span class="badge badge-primary" style="font-size:0.8rem;">
                        <?php if ($usuario['tipo'] === 'admin'): ?>
                            <span class="google-icon" aria-hidden="true">school</span> Professor
                        <?php else: ?>
                            <span class="google-icon" aria-hidden="true">sports_esports</span> Aluno
                        <?php endif; ?>
                    </span>

                    <div style="margin:20px 0;padding:16px;background:#f0f2f5;border-radius:10px;">
                        <div style="font-size:0.8rem;color:#636e72;">Membro desde</div>
                        <div style="font-weight:700;color:#2d3436;"><?= $membro_desde ?></div>
                    </div>

                    <!-- Nível e XP -->
                    <div style="background:linear-gradient(135deg,#6c63ff,#a855f7);border-radius:12px;padding:16px;color:#fff;text-align:left;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                            <span style="font-weight:700;"><span class="google-icon" aria-hidden="true">star</span> Nível <?= $nivel ?></span>
                            <span style="font-size:0.85rem;opacity:0.85;"><?= $xp ?>/<?= $xp_prox ?> XP</span>
                        </div>
                        <div style="background:rgba(255,255,255,0.2);border-radius:50px;height:10px;overflow:hidden;">
                            <div style="height:100%;width:<?= $pct_xp ?>%;background:linear-gradient(90deg,#ffd700,#ff6b35);border-radius:50px;transition:width 1s ease;"></div>
                        </div>
                        <div style="text-align:center;font-size:0.85rem;margin-top:8px;opacity:0.9;"><?= $titulo ?></div>
                    </div>
                </div>

                <!-- Medalhas -->
                <div class="card">
                    <div class="card-title"><span class="google-icon" aria-hidden="true">military_tech</span> Medalhas</div>
                    <div style="text-align:center;padding:10px 0;">
                        <span class="google-icon profile-medal-icon" aria-hidden="true">military_tech</span>
                        <div style="font-size:3rem;font-weight:900;color:#f39c12;"><?= $total_medalhas ?></div>
                        <div style="font-size:0.85rem;color:#636e72;">de 7 possíveis</div>
                    </div>
                    <a href="/site_antigravity/conquistas.php" class="btn btn-outline btn-sm w-100" style="text-align:center;">Ver Conquistas →</a>
                </div>
            </div>

            <!-- ---- ESTATÍSTICAS (direita) ---- -->
            <div>
                <div class="card" style="margin-bottom:24px;">
                    <div class="card-title"><span class="google-icon" aria-hidden="true">analytics</span> Estatísticas Completas</div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:16px;">
                        <?php
                        $stats = [
                            ['emoji_events', 'Pontuação',   number_format($pontuacao),   '#6c63ff'],
                            ['star', 'XP Total',     number_format($xp),          '#a855f7'],
                            ['check_circle', 'Acertos',      $acertos,                    '#2ecc71'],
                            ['cancel', 'Erros',        $erros,                      '#e74c3c'],
                            ['sports_esports', 'Partidas',     $jogos_feitos,               '#ff6b35'],
                            ['track_changes', 'Taxa Acerto',  $taxa_acerto.'%',            '#f39c12'],
                        ];
                        foreach ($stats as [$icon, $label, $value, $color]): ?>
                        <div style="background:#f0f2f5;border-radius:12px;padding:16px;text-align:center;">
                            <div class="google-icon profile-stat-icon" aria-hidden="true"><?= $icon ?></div>
                            <div style="font-size:1.4rem;font-weight:800;color:<?= $color ?>;"><?= $value ?></div>
                            <div style="font-size:0.75rem;color:#636e72;font-weight:600;"><?= $label ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Resultados por jogo -->
                <div class="card">
                    <div class="card-title"><span class="google-icon" aria-hidden="true">sports_esports</span> Desempenho por Jogo</div>
                    <?php
                    // Busca estatísticas agregadas por jogo
                    $stmt = $conn->prepare('SELECT jogo_nome, COUNT(*) as partidas, SUM(pontuacao) as total_pts, SUM(acertos) as total_acertos, MAX(pontuacao) as melhor
                                           FROM resultados WHERE usuario_id = ? GROUP BY jogo_id, jogo_nome ORDER BY jogo_id');
                    $stmt->bind_param('i', $uid);
                    $stmt->execute();
                    $por_jogo = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    ?>
                    <?php if (empty($por_jogo)): ?>
                        <p style="color:#b2bec3;text-align:center;padding:20px;">
                            Você ainda não jogou. <a href="/site_antigravity/jogos.php" style="color:#6c63ff;">Comece agora! <span class="google-icon" aria-hidden="true">sports_esports</span></a>
                        </p>
                    <?php else: ?>
                        <div style="overflow-x:auto;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Jogo</th>
                                        <th>Partidas</th>
                                        <th>Total Pts</th>
                                        <th>Acertos</th>
                                        <th>Melhor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($por_jogo as $j): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($j['jogo_nome']) ?></td>
                                        <td><?= $j['partidas'] ?></td>
                                        <td><?= number_format($j['total_pts']) ?></td>
                                        <td><?= $j['total_acertos'] ?></td>
                                        <td style="color:#6c63ff;font-weight:700;"><?= $j['melhor'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </main>
</div>

<script>
    const seletorFotoPerfil = document.getElementById('foto_perfil');
    const nomeArquivoFotoPerfil = document.getElementById('profileFileName');

    seletorFotoPerfil.addEventListener('change', function() {
        nomeArquivoFotoPerfil.textContent = this.files.length
            ? this.files[0].name
            : 'JPG, PNG, WebP ou AVIF · até 5 MB';
    });
</script>

<!-- CSS extra para responsividade do perfil -->
<style>
@media (max-width: 900px) {
    .main-content > div[style*="grid-template-columns:320px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

</body>
</html>
