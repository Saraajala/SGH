
<?php
session_start();
if(!isset($_SESSION['perfil'])){
    header('Location: ../index.php');
    exit;
}

include '../../models/Usuario.php';
include '../../models/Consulta.php';

// Lista médicos disponíveis
$medicos = $pdo->query("SELECT id, nome FROM usuarios WHERE perfil='medico'")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Agendar Consulta</h2>
<form method="POST" action="../../controllers/ConsultaController.php">
    <input type="hidden" name="acao" value="cadastrar">

    <label>Paciente ID:</label>
    <input type="number" name="paciente_id" required><br>

    <label>Médico:</label>
    <select name="medico_id" required>
        <?php foreach($medicos as $m): ?>
            <option value="<?= $m['id'] ?>"><?= $m['nome'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Data:</label>
    <input type="date" name="data" required><br>

    <label>Hora:</label>
    <input type="time" name="hora" required><br>

    <button type="submit">Agendar</button>
</form>

<?php
// Mostrar sugestão de horários se data e médico selecionados
if(isset($_POST['medico_id'], $_POST['data'])){
    $alternativas = Consulta::sugestaoHorarios($_POST['medico_id'], $_POST['data']);
    echo "Horários alternativos disponíveis: ".implode(', ', $alternativas);
}
?>
