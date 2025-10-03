<?php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['usuario_logado'];
$usuario = $_SESSION['usuarios'][$email];
?>

<h1>Bem-vindo(a), <?= $usuario['nome'] ?>!</h1>

<?php if($usuario['tipo'] === 'paciente'): ?>
    <p>Este é o perfil de um PACIENTE.</p>
    <!-- Aqui você pode colocar campos específicos de paciente -->
<?php else: ?>
    <p>Este é o perfil de um MÉDICO.</p>
    <!-- Aqui você pode colocar campos específicos de médico -->
<?php endif; ?>

<a href="logout.php">Sair</a>
