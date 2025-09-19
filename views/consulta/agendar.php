<?php
session_start();
if(!isset($_SESSION['id_usuario'], $_SESSION['perfil'])){
    header('Location: ../index.php');
    exit;
}

include '../../config.php';
include '../../models/Consulta.php';

$user_id = $_SESSION['id_usuario'];
$perfil = $_SESSION['perfil'];

// Opções para select
if($perfil == 'medico'){
    $stmt = $pdo->query("SELECT id, nome FROM usuarios WHERE perfil='paciente' ORDER BY nome ASC");
    $opcoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $label = "Paciente";
    $campo_name = "paciente_id";
    $acao = "marcar";
} elseif($perfil == 'paciente'){
    $stmt = $pdo->query("SELECT id, nome FROM usuarios WHERE perfil='medico' ORDER BY nome ASC");
    $opcoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $label = "Médico";
    $campo_name = "medico_id";
    $acao = "agendar";
} else {
    echo "Perfil inválido.";
    exit;
}
?>

<h2><?= $perfil == 'medico' ? "Marcar Consulta" : "Agendar Consulta" ?></h2>

<form method="POST" action="../../controllers/ConsultaController.php">
    <input type="hidden" name="acao" value="<?= $acao ?>">

    <label><?= $label ?>:</label>
    <select name="<?= $campo_name ?>" required>
        <option value="">-- Selecione --</option>
        <?php foreach($opcoes as $o): ?>
            <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['nome']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Data:</label>
    <input type="date" name="data" required><br><br>

    <label>Hora:</label>
    <input type="time" name="hora" required><br><br>

    <button type="submit"><?= $perfil == 'medico' ? "Marcar Consulta" : "Agendar Consulta" ?></button>
</form>

<hr>
<h3>Consultas Agendadas:</h3>
<?php
$consultas = $perfil == 'medico' ? Consulta::listarPorMedico($user_id) : Consulta::listarPorPaciente($user_id);
if($consultas){
    echo "<ul>";
    foreach($consultas as $c){
        $data_formatada = date('d/m/Y', strtotime($c['data']));
        $hora_formatada = date('H:i', strtotime($c['hora']));

        if($perfil == 'medico'){
            echo "<li>Paciente: {$c['paciente_nome']} | Data: {$data_formatada} | Hora: {$hora_formatada}</li>";
        } else {
            echo "<li>Médico: {$c['medico_nome']} | Data: {$data_formatada} | Hora: {$hora_formatada}</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>Nenhuma consulta agendada.</p>";
}
?>

<br>
<a href="../dashboard.php">
    <button type="button">Voltar ao Dashboard</button>
</a>
