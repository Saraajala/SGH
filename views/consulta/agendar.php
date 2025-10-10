<?php
session_start();
include '../../models/Consulta.php';

if(!isset($_SESSION['id_usuario'])){
    header('Location: ../login.php');
    exit;
}

$perfil = $_SESSION['perfil'];
$user_id = $_SESSION['id_usuario'];

if($perfil == 'paciente'){
    $medicos = Consulta::listarMedicos();
} elseif($perfil == 'medico'){
    $pacientes = Consulta::listarPacientes();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendar Consulta</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="estilo.css">
    <link rel="icon" href="../favicon_round.png" type="image/png"> 
</head>
<body>
 <header class="header clinic-header">
    <nav class="nav">
        <div class="nav-left">
            <a href="../dashboard.php">
                <img src="../logo.png" alt="Logo Clínica Lumière" class="logo">
            </a>
            <h1>Clínica Lumière</h1>
        </div>

        <div class="nav-right">
            <span class="welcome-text">
                <?php
                if ($_SESSION['perfil'] === 'medico') {
                    echo 'Bem-vindo, Dr(a). ';
                } elseif ($_SESSION['perfil'] === 'enfermeiro') {
                    echo 'Bem-vindo, Enfermeiro(a) ';
                } else {
                    echo 'Bem-vindo, ';
                }

                echo htmlspecialchars($_SESSION['nome'] ?? 'Usuário');
                ?>
            </span>

            <ul class="menu-topo">
                <li><a href="../dashboard.php"><i class="fa fa-home icon"></i>Menu</a></li>
                <li><a href="../../index.php"><i class="fa fa-sign-out-alt icon"></i>Sair</a></li>
            </ul>
        </div>
    </nav>
</header>


<br><br><br><br><br>

    <!-- Conteúdo principal -->
    <main>
        <h3>Agendar Consulta</h3>

        <!-- Mensagens -->
<div class="consulta-container">

    <div class="consulta-box">

        <!-- Mensagens -->
        <?php if(!empty($_SESSION['msg_sucesso'])): ?>
            <div class="msg sucesso">
                <?= htmlspecialchars($_SESSION['msg_sucesso']) ?>
            </div>
            <?php unset($_SESSION['msg_sucesso']); ?>
        <?php endif; ?>

        <?php if(!empty($_SESSION['msg_erro'])): ?>
            <div class="msg erro">
                <?= htmlspecialchars($_SESSION['msg_erro']) ?>
            </div>
            <?php unset($_SESSION['msg_erro']); ?>
        <?php endif; ?>

        <!-- Formulário -->
        <form method="POST" action="../../controllers/ConsultaController.php" class="form-consulta">
            <?php if($perfil == 'paciente'): ?>
                <input type="hidden" name="acao" value="agendar">
                <label>Médico:</label>
                <select name="medico_id" required>
                    <option value="">-- Selecione o Médico --</option>
                    <?php foreach($medicos as $medico): ?>
                        <option value="<?= $medico['id'] ?>"><?= htmlspecialchars($medico['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif($perfil == 'medico'): ?>
                <input type="hidden" name="acao" value="marcar">
                <label>Paciente:</label>
                <select name="paciente_id" required>
                    <option value="">-- Selecione o Paciente --</option>
                    <?php foreach($pacientes as $paciente): ?>
                        <option value="<?= $paciente['id'] ?>"><?= htmlspecialchars($paciente['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <label>Data:</label>
            <input type="date" name="data" required min="<?= date('Y-m-d') ?>">

            <label>Hora:</label>
            <input type="time" name="hora" required>

            <button type="submit" class="btn-submit">Agendar Consulta</button>
        </form>
    </main>
<br><br><br><br><br><br>


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
