<?php
session_start();
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','recepcao','enfermeiro'])){
    $_SESSION['msg_erro'] = "Acesso negado.";
    header('Location: ../views/dashboard.php');
    exit;
}

include '../../models/Farmacia.php';
include '../../models/Paciente.php';

$medicamentos = Farmacia::listarMedicamentos();
$pacientes = Paciente::listar();
$dispensacoes = Farmacia::listarDispensacoes();
?>

<h2>Farmácia</h2>

<!-- Mensagens -->
<?php if(!empty($_SESSION['msg_sucesso'])): ?>
    <div style="color:green;"><?= htmlspecialchars($_SESSION['msg_sucesso']) ?></div>
    <?php unset($_SESSION['msg_sucesso']); ?>
<?php endif; ?>

<?php if(!empty($_SESSION['msg_erro'])): ?>
    <div style="color:red;"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
    <?php unset($_SESSION['msg_erro']); ?>
<?php endif; ?>

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
    <select name="medicamento_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach($medicamentos as $m): ?>
            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nome']) ?> (Estoque: <?= $m['quantidade'] ?>)</option>
        <?php endforeach; ?>
    </select><br>

    <label>Paciente:</label>
    <select name="paciente_id" required>
        <option value="">-- Selecione --</option>
        <?php foreach($pacientes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <input type="number" name="quantidade" placeholder="Quantidade" required><br>
    <button type="submit">Dispensar</button>
</form>



<h3>Saídas de Medicamentos</h3>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>Data</th>
            <th>Medicamento</th>
            <th>Paciente</th>
            <th>Quantidade</th>
            <th>Dispensado por</th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($dispensacoes)): ?>
            <tr>
                <td colspan="5" align="center">Nenhuma dispensação registrada</td>
            </tr>
        <?php else: ?>
            <?php foreach($dispensacoes as $dispensa): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($dispensa['data_cadastro'])) ?></td>
                <td><?= htmlspecialchars($dispensa['medicamento_nome']) ?></td>
                <td><?= htmlspecialchars($dispensa['paciente_nome']) ?></td>
                <td align="center"><?= $dispensa['quantidade'] ?></td>
                <td><?= htmlspecialchars($dispensa['usuario_nome']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<a href="../dashboard.php">voltar ao início</a>