<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../index.php');
    exit;
}

include '../../models/Notificacao.php';
include '../../config.php';

$id_usuario = $_SESSION['id_usuario'];
$stmt = $pdo->prepare("SELECT nome, perfil FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$nome_usuario = $usuario['nome'] ?? 'Usuário';
$perfil = $usuario['perfil'] ?? '';
$tratamento = ($perfil === 'medico') ? 'Dr(a).' : '';

$notificacoes = Notificacao::listarPorUsuario($id_usuario);
$total = count($notificacoes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notificações - Sistema de Gestão Hospitalar</title>
    <link rel="icon" href="../favicon_round.png" type="image/png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="estilo.css">
</head>
<body>

<header class="header clinic-header">
    <nav class="nav">
        <div class="nav-left">
<a href="../dashboard.php">
    <img src="../logo.png" alt="Logo Clínica Lumière" class="logo">
</a>             <h1>Clínica Lumière</h1>
        </div>

        <div class="nav-right">
            <span class="welcome-text">
                Bem-vindo, <?= $tratamento ?> <?= htmlspecialchars($nome_usuario) ?>
            </span>

            <!-- Ícone de notificações apenas -->
            <span class="notification-icon">
                🔔 <?php if($total>0): ?><span class="notification-count"><?= $total ?></span><?php endif; ?>
            </span>

            <ul class="menu-topo">
                <li><a href="../dashboard.php"><i class="fa fa-home icon"></i>Menu</a></li>
                <li><a href="../index.php"><i class="fa fa-sign-out-alt icon"></i>Sair</a></li>
            </ul>
        </div>
    </nav>
</header>
<br><br><br><br>



<div class="container">
    <h3>Central de Notificações</h3>

    <!-- Card resumo -->
    <div class="notification-summary">
        <div>
            <span><?= $total ?></span>
            Total de notificações
        </div>
        <div>
            <span><?= count(array_filter($notificacoes, fn($n)=>$n['lida'])) ?></span>
            Lidas
        </div>
        <div>
            <span><?= count(array_filter($notificacoes, fn($n)=>!$n['lida'])) ?></span>
            Não Lidas
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Mensagem</th>
                    <th>Data/Hora</th>
                    <th>Status</th>
                    <th class="action-cell">Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($notificacoes)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">Nenhuma notificação encontrada.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($notificacoes as $n): ?>
                        <tr>
                            <td>
                                <?php if ($n['tipo'] === 'alerta'): ?>
                                    <span class="type-badge alert">⚠️ <?= htmlspecialchars($n['tipo']) ?></span>
                                <?php elseif ($n['tipo'] === 'info'): ?>
                                    <span class="type-badge info">ℹ️ <?= htmlspecialchars($n['tipo']) ?></span>
                                <?php else: ?>
                                    <span class="type-badge normal"><?= htmlspecialchars($n['tipo']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="message-cell" data-tooltip="<?= htmlspecialchars($n['mensagem']) ?>">
                                <?= htmlspecialchars(substr($n['mensagem'],0,50)) ?>...
                            </td>
                            <td><?= (new DateTime($n['data']))->format('d/m/Y H:i') ?></td>
                            <td>
                                <?php if ($n['lida']): ?>
                                    <span class="status-badge read">✔️ Lida</span>
                                <?php else: ?>
                                    <span class="status-badge unread">📩 Não Lida</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-cell">
                                <?php if (!$n['lida']): ?>
                                    <a class="action-btn" href="../../controllers/NotificacaoController.php?acao=lida&id=<?= $n['id'] ?>">Marcar Lida</a>
                                <?php else: ?> — <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <div class="footer-logo-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM10 18a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="footer-logo-text">Lumière</h3>
                </div>
                <p class="footer-description">Cuidando de você e sua família há mais de 30 anos com excelência e dedicação.</p>
            </div>
            <div class="footer-section">
                <h4 class="footer-title">Serviços</h4>
                <ul class="footer-list">
                    <li>Consultas</li>
                    <li>Procedimentos</li>
                    <li>Internações</li>
                    <li>Exames</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4 class="footer-title">Contato</h4>
                <ul class="footer-list">
                    <li>📞 (11) 1234-5678</li>
                    <li>✉️ contato@clinicalumiere.com</li>
                    <li>📍 Rua Lumière, 123</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4 class="footer-title">Redes Sociais</h4>
                <ul class="footer-list">
                    <li>Facebook</li>
                    <li>Instagram</li>
                    <li>LinkedIn</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">© 2025 Clínica Lumière. Todos os direitos reservados.</div>
    </div>
</footer>

</body>
</html>
