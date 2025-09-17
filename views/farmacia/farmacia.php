
<?php
session_start();
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','recepcao'])){
    header('Location: ../index.php');
    exit;
}

include '../../models/Farmacia.php';
include '../../models/Paciente.php';

$medicamentos = Farmacia::listarMedicamentos();
$pacientes = Paciente::listar();
?>

<h2>Farmácia</h2>

<h3>Cadastrar Medicamento</h3>
<form method="POST" action="../../controllers/FarmaciaController.php">
    <input type="hidden" name="acao" value="cadastrar">
    <input type="text" name="nome" placeholder="Nome" required><br>
    <textarea name="descricao" placeholder="Descrição"></textarea><br>
    <input type="number" name="quantidade" placeholder="Quantidade" required><br>
    <button type="submit">Cadastrar</button>
</form>

<h3>Dispensar Medicamento</h3>
<form method="POST" action="../../controllers/FarmaciaController.php">
    <input type="hidden" name="acao" value="dispensar">
    <label>Medicamento:</label>
    <select name="medicamento_id">
        <?php foreach($medicamentos as $m): ?>
            <option value="<?= $m['id'] ?>"><?= $m['nome'] ?> (Estoque: <?= $m['quantidade'] ?>)</option>
        <?php endforeach; ?>
    </select><br>
    <label>Paciente:</label>
    <select name="paciente_id">
        <?php foreach($pacientes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
        <?php endforeach; ?>
    </select><br>
    <input type="number" name="quantidade" placeholder="Quantidade" required><br>
    <button type="submit">Dispensar</button>
</form>
