<?php
session_start();
if(!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 'medico'){
    header('Location: ../index.php');
    exit;
}

include '../../models/Paciente.php';
include '../../models/Prontuario.php';
include '../../config.php';

$pacientes = Paciente::listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prontuário Eletrônico - SGH</title>
</head>
<body>
<h2>Prontuário Eletrônico</h2>

<!-- Mensagens -->
<?php if(!empty($_SESSION['msg_sucesso'])): ?>
    <div class="msg-sucesso"><?= htmlspecialchars($_SESSION['msg_sucesso']) ?></div>
    <?php unset($_SESSION['msg_sucesso']); ?>
<?php endif; ?>

<?php if(!empty($_SESSION['msg_erro'])): ?>
    <div class="msg-erro"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
    <?php unset($_SESSION['msg_erro']); ?>
<?php endif; ?>

<!-- Seleção de Paciente -->
<div class="section">
    <label><strong>Selecionar Paciente:</strong></label>
    <select id="selectPaciente" required>
        <option value="">-- Selecione um paciente --</option>
        <?php foreach($pacientes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?> (ID: <?= $p['id'] ?>)</option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Formulários de registro -->
<?php
$acoes = [
    'evolucao' => 'Registrar Evolução',
    'prescricao' => 'Registrar Prescrição',
    'procedimento' => 'Registrar Procedimento',
    'exame' => 'Registrar Exame'
];
foreach($acoes as $acao => $label):
?>
<div class="section">
    <h3><?= $label ?></h3>
    <form method="POST" action="../../controllers/ProntuarioController.php" class="form-prontuario">
        <input type="hidden" name="paciente_id" class="paciente_id_hidden">
        <?php if($acao !== 'exame'): ?>
            <textarea name="descricao" placeholder="Descreva..." required></textarea><br>
        <?php else: ?>
            <label><strong>Tipo de Exame:</strong></label>
            <select name="exame_id" required>
                <option value="">-- Selecione o tipo de exame --</option>
                <?php
                $exames = $pdo->query("SELECT * FROM exames_cadastrados ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
                foreach($exames as $ex):
                ?>
                    <option value="<?= $ex['id'] ?>"><?= htmlspecialchars($ex['nome']) ?></option>
                <?php endforeach; ?>
            </select><br><br>
        <?php endif; ?>
        <input type="hidden" name="acao" value="<?= $acao ?>">
        <button type="submit"><?= $label ?></button>
    </form>
</div>
<?php endforeach; ?>

<hr>

<!-- Histórico -->
<div class="historico">
<h3>Histórico Completo</h3>
<?php if(empty($pacientes)): ?>
    <p>Nenhum paciente cadastrado.</p>
<?php else: ?>
    <?php foreach($pacientes as $p): ?>
        <div class="paciente-section">
            <h4><?= htmlspecialchars($p['nome']) ?> (ID: <?= $p['id'] ?>)</h4>

            <h5>Evoluções:</h5>
            <?php $evol = Prontuario::listarEvolucoes($p['id']); ?>
            <?php if(empty($evol)): ?>
                <p>Nenhuma evolução registrada.</p>
            <?php else: ?>
                <?php foreach($evol as $e): ?>
                    <?php $data = date('d/m/Y H:i', strtotime($e['data'])); ?>
                    <p><strong><?= $data ?></strong> - Dr(a). <?= htmlspecialchars($e['medico_nome'] ?? 'N/A') ?>: <?= htmlspecialchars($e['descricao']) ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <h5>Prescrições:</h5>
            <?php $presc = Prontuario::listarPrescricoes($p['id']); ?>
            <?php if(empty($presc)): ?>
                <p>Nenhuma prescrição registrada.</p>
            <?php else: ?>
                <?php foreach($presc as $pr): ?>
                    <?php $data = date('d/m/Y H:i', strtotime($pr['data'])); ?>
                    <p><strong><?= $data ?></strong> - Dr(a). <?= htmlspecialchars($pr['medico_nome'] ?? 'N/A') ?>: <?= htmlspecialchars($pr['descricao']) ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <h5>Procedimentos:</h5>
            <?php $proc = Prontuario::listarProcedimentos($p['id']); ?>
            <?php if(empty($proc)): ?>
                <p>Nenhum procedimento registrado.</p>
            <?php else: ?>
                <?php foreach($proc as $prc): ?>
                    <?php $data = date('d/m/Y H:i', strtotime($prc['data'])); ?>
                    <p><strong><?= $data ?></strong>: <?= htmlspecialchars($prc['descricao']) ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <h5>Exames:</h5>
            <?php $exs = Prontuario::listarExames($p['id']); ?>
            <?php if(empty($exs)): ?>
                <p>Nenhum exame registrado.</p>
            <?php else: ?>
                <?php foreach($exs as $ex): ?>
                    <?php $data = date('d/m/Y H:i', strtotime($ex['data'])); ?>
                    <p><strong><?= $data ?></strong> - Dr(a). <?= htmlspecialchars($ex['medico_nome'] ?? 'N/A') ?>: <?= htmlspecialchars($ex['exame_nome']) ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<a href="../dashboard.php"><button type="button">Voltar ao Dashboard</button></a>

<script>
const selectPaciente = document.getElementById('selectPaciente');
const forms = document.querySelectorAll('.form-prontuario');

function atualizarPacienteHidden() {
    const pacienteId = selectPaciente.value;
    forms.forEach(form => {
        const hidden = form.querySelector('.paciente_id_hidden');
        if(hidden) hidden.value = pacienteId;
    });
}

// Inicializa e atualiza ao mudar o select
atualizarPacienteHidden();
selectPaciente.addEventListener('change', atualizarPacienteHidden);

// Validação de envio
forms.forEach(form => {
    form.addEventListener('submit', function(e){
        const pacienteId = this.querySelector('.paciente_id_hidden').value;
        if(!pacienteId){
            e.preventDefault();
            alert('Selecione um paciente antes de registrar.');
        }
    });
});
</script>
</body>
</html>
