<?php
session_start();
if(!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 'medico'){
    header('Location: ../index.php');
    exit;
}

include '../../models/Paciente.php';
include '../../models/Prontuario.php';

$pacientes = Paciente::listar();
?>

<h2>Prontuário Eletrônico</h2>

<form method="POST" action="../../controllers/ProntuarioController.php" enctype="multipart/form-data">
    <label>Paciente:</label>
    <select name="paciente_id" required>
        <?php foreach($pacientes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <h3>Registrar Evolução</h3>
    <textarea name="descricao" placeholder="Descrição da evolução"></textarea><br>
    <input type="hidden" name="acao" value="evolucao">
    <button type="submit">Registrar Evolução</button>
</form>

<form method="POST" action="../../controllers/ProntuarioController.php">
    <h3>Registrar Prescrição</h3>
    <input type="hidden" name="acao" value="prescricao">
    <input type="hidden" name="paciente_id" value="<?= $p['id'] ?>">
    <input type="text" name="medicamento" placeholder="Medicamento" required><br>
    <input type="text" name="posologia" placeholder="Posologia" required><br>
    <button type="submit">Registrar Prescrição</button>
</form>

<form method="POST" action="../../controllers/ProntuarioController.php">
    <h3>Registrar Procedimento</h3>
    <input type="hidden" name="acao" value="procedimento">
    <input type="hidden" name="paciente_id" value="<?= $p['id'] ?>">
    <textarea name="descricao" placeholder="Descrição do procedimento"></textarea><br>
    <button type="submit">Registrar Procedimento</button>
</form>

<form method="POST" action="../../controllers/ProntuarioController.php" enctype="multipart/form-data">
    <h3>Registrar Exame</h3>
    <input type="hidden" name="acao" value="exame">
    <input type="hidden" name="paciente_id" value="<?= $p['id'] ?>">
    <input type="file" name="arquivo" required><br>
    <textarea name="descricao" placeholder="Descrição do exame"></textarea><br>
    <button type="submit">Registrar Exame</button>
</form>

<h3>Histórico</h3>
<?php
foreach($pacientes as $p){
    echo "<h4>".$p['nome']."</h4>";

    echo "<b>Evoluções:</b><br>";
    $evol = Prontuario::listarEvolucoes($p['id']);
    foreach($evol as $e) echo $e['data'].": ".$e['descricao']."<br>";

    echo "<b>Prescrições:</b><br>";
    $presc = Prontuario::listarPrescricoes($p['id']);
    foreach($presc as $pr) echo $pr['data'].": ".$pr['medicamento']." - ".$pr['posologia']."<br>";

    echo "<b>Procedimentos:</b><br>";
    $proc = Prontuario::listarProcedimentos($p['id']);
    foreach($proc as $prc) echo $prc['data'].": ".$prc['descricao']."<br>";

    echo "<b>Exames:</b><br>";
    $exs = Prontuario::listarExames($p['id']);
    foreach($exs as $ex) echo $ex['data'].": <a href='../../".$ex['arquivo']."' target='_blank'>".$ex['arquivo']."</a> - ".$ex['descricao']."<br>";

    echo "<hr>";
}
?>
