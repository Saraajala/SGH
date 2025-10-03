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
</head>
<body>
    <h2>Agendar Consulta</h2>

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

    <form method="POST" action="../../controllers/ConsultaController.php">
        <?php if($perfil == 'paciente'): ?>
            <input type="hidden" name="acao" value="agendar">
            <label>Médico:</label>
            <select name="medico_id" required>
                <option value="">-- Selecione o Médico --</option>
                <?php foreach($medicos as $medico): ?>
                    <option value="<?= $medico['id'] ?>"><?= htmlspecialchars($medico['nome']) ?></option>
                <?php endforeach; ?>
            </select><br><br>
        <?php elseif($perfil == 'medico'): ?>
            <input type="hidden" name="acao" value="marcar">
            <label>Paciente:</label>
            <select name="paciente_id" required>
                <option value="">-- Selecione o Paciente --</option>
                <?php foreach($pacientes as $paciente): ?>
                    <option value="<?= $paciente['id'] ?>"><?= htmlspecialchars($paciente['nome']) ?></option>
                <?php endforeach; ?>
            </select><br><br>
        <?php endif; ?>

        <label>Data:</label>
        <input type="date" name="data" required min="<?= date('Y-m-d') ?>"><br><br>

        <label>Hora:</label>
        <input type="time" name="hora" required><br><br>

        <button type="submit">Agendar Consulta</button>
    </form>

    <br>
    <a href="../dashboard.php">← Voltar ao Dashboard</a>
</body>
</html>