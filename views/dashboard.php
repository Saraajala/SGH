<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

include '../models/Notificacao.php';

$nome   = $_SESSION['nome'];
$perfil = $_SESSION['perfil'];
$usuario_id = $_SESSION['id_usuario'];

// Contar notificações não lidas
$nao_lidas = Notificacao::contarNaoLidas($usuario_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SGH</title>
</head>
<body>
    <h2>Bem-vindo, <?= htmlspecialchars($nome) ?> (<?= ucfirst($perfil) ?>)</h2>
    
    <!-- Contador de notificações -->
    <?php if($nao_lidas > 0): ?>
        <div>
             Você tem <strong><?= $nao_lidas ?></strong> notificação<?= $nao_lidas != 1 ? 'es' : '' ?> não lida<?= $nao_lidas != 1 ? 's' : '' ?>.
            <a href="notificacao/listar.php">Ver notificações</a>
        </div>
    <?php endif; ?>

    <p>Escolha uma opção do menu abaixo:</p>

    <!-- Mensagens -->
    <?php if(!empty($_SESSION['msg_sucesso'])): ?>
        <div>
            <?= htmlspecialchars($_SESSION['msg_sucesso']) ?>
        </div>
        <?php unset($_SESSION['msg_sucesso']); ?>
    <?php endif; ?>

    <?php if(!empty($_SESSION['msg_erro'])): ?>
        <div>
            <?= htmlspecialchars($_SESSION['msg_erro']) ?>
        </div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <ul>
        <?php if($perfil == 'medico'): ?>
            <li><a href="consulta/consultas_medico.php">Gerenciar Minhas Consultas</a></li>
            <li><a href="consulta/agendar.php">Agendar Consulta para Paciente</a></li>
            <li><a href="prontuario/prontuario.php">Prontuário Eletrônico</a></li>
            <li><a href="internacao/internacao.php">Internar Paciente</a></li>
            <li><a href="notificacao/listar.php">Notificações (<?= $nao_lidas ?>)</a></li>
            <li><a href="calendario/calendario.php">Calendário</a></li>
        <?php endif; ?>

        <?php if($perfil == 'enfermeiro'): ?>
            <li><a href="internacao/internacao.php">Internar Paciente</a></li>
            <li><a href="prontuario/prontuario.php">Prontuário Eletrônico</a></li>
            <li><a href="farmacia/farmacia.php">Farmácia</a></li>
        <?php endif; ?>

        <?php if($perfil == 'paciente'): ?>
            <li><a href="consulta/agendar.php">Agendar Consulta</a></li>
            <li><a href="consulta/historico.php">Histórico de Consultas</a></li>
            <li><a href="notificacao/listar.php">Notificações (<?= $nao_lidas ?>)</a></li>
            <li><a href="calendario/calendario.php">Calendário</a></li>
        <?php endif; ?>

    </ul>

    <p><a href="../logout.php">🚪 Sair</a></p>
</body>
</html>

