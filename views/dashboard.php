<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$nome   = $_SESSION['nome'];
$perfil = $_SESSION['perfil'];
?>

<h2>Bem-vindo, <?= htmlspecialchars($nome) ?> (<?= ucfirst($perfil) ?>)</h2>
<p>Escolha uma opção do menu abaixo:</p>

<ul>
    <?php if($perfil == 'administrador'): ?>
        <li><a href="paciente/cadastro.php">Cadastrar Pacientes</a></li>
        <li><a href="consulta/agendar.php">Gerenciar Consultas</a></li>
        <li><a href="internacao/internar.php">Internações</a></li>
        <li><a href="internacao/alta_transferencia.php">Alta / Transferência</a></li>
        <li><a href="farmacia/farmacia.php">Farmácia</a></li>
        <li><a href="notificacao/listar.php">Notificações</a></li>
        <li><a href="calendario/calendario.php">Calendário</a></li>
        <li><a href="relatorios/relatorios.php">Relatórios</a></li>
    <?php endif; ?>

    <?php if($perfil == 'medico'): ?>
        <li><a href="consulta/agendar.php">Marcar Consulta</a></li>
        <li><a href="prontuario/prontuario.php">Prontuário Eletrônico</a></li>
        <li><a href="internacao/internacao.php">Internar Paciente</a></li>
        <li><a href="notificacao/listar.php">Notificações</a></li>
        <li><a href="calendario/calendario.php">Calendário</a></li>
    <?php endif; ?>

    <?php if($perfil == 'enfermeiro'): ?>
        <li><a href="internacao/internar.php">Internar Paciente</a></li>
        <li><a href="internacao/alta_transferencia.php">Alta / Transferência</a></li>
        <li><a href="prontuario/prontuario.php">Registrar Procedimentos</a></li>
        <li><a href="notificacao/listar.php">Notificações</a></li>
        <li><a href="calendario/calendario.php">Calendário</a></li>
    <?php endif; ?>

    <?php if($perfil == 'paciente'): ?>
        <li><a href="consulta/agendar.php">Agendar Consulta</a></li>
        <li><a href="consulta/historico.php">Histórico de Consultas</a></li>
        <li><a href="notificacao/listar.php">Notificações</a></li>
        <li><a href="calendario/calendario.php">Calendário</a></li>
    <?php endif; ?>
</ul>

<p><a href="../logout.php">Sair</a></p>
