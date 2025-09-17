
<?php
session_start();
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])){
    header('Location: ../index.php');
    exit;
}

include '../../models/Internacao.php';

$internacoes = Internacao::listar();
$quartos = Internacao::quartosDisponiveis();
?>

<h2>Alta ou Transferência</h2>
<form method="POST" action="../../controllers/InternacaoController.php">
    <input type="hidden" name="acao" value="alta_transferencia">

    <label>Internação:</label>
    <select name="internacao_id" required>
        <?php foreach($internacoes as $i): ?>
            <option value="<?= $i['id'] ?>">Paciente <?= $i['paciente_nome'] ?> - Quarto <?= $i['quarto_numero'] ?> (<?= $i['quarto_status'] ?>)</option>
        <?php endforeach; ?>
    </select><br>

    <label>Novo Quarto (para transferência):</label>
    <select name="novo_quarto_id">
        <option value="">-- Alta --</option>
        <?php foreach($quartos as $q): ?>
            <option value="<?= $q['id'] ?>">Ala <?= $q['ala'] ?> - Quarto <?= $q['numero'] ?></option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Executar</button>
</form>
