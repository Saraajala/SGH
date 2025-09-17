
<?php
session_start();
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])){
    header('Location: ../index.php');
    exit;
}

include '../../models/Paciente.php';
include '../../models/Internacao.php';

// Listar pacientes
$pacientes = Paciente::listar();

// Listar quartos disponíveis
$quartos = Internacao::quartosDisponiveis();
?>

<h2>Internar Paciente</h2>
<form method="POST" action="../../controllers/InternacaoController.php">
    <input type="hidden" name="acao" value="internar">

    <label>Paciente:</label>
    <select name="paciente_id" required>
        <?php foreach($pacientes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Quarto:</label>
    <select name="quarto_id" required>
        <?php foreach($quartos as $q): ?>
            <option value="<?= $q['id'] ?>">Ala <?= $q['ala'] ?> - Quarto <?= $q['numero'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Internar</button>
</form>
