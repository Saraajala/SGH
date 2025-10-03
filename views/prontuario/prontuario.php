<?php
session_start();
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])){
    $_SESSION['msg_erro'] = "Acesso negado.";
    header('Location: ../views/dashboard.php');
    exit;
}

$perfil = $_SESSION['perfil'];
include '../../models/Paciente.php';
include '../../models/Prontuario.php';
include '../../config.php';

$pacientes = Paciente::listar();
$medico_id_logado = $_SESSION['id_usuario'] ?? null;
?>

<h2>Prontuário Eletrônico</h2>

<!-- Mensagens -->
<?php if(!empty($_SESSION['msg_sucesso'])): ?>
    <div><?= htmlspecialchars($_SESSION['msg_sucesso']) ?></div>
    <?php unset($_SESSION['msg_sucesso']); ?>
<?php endif; ?>

<?php if(!empty($_SESSION['msg_erro'])): ?>
    <div><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
    <?php unset($_SESSION['msg_erro']); ?>
<?php endif; ?>

<?php if($perfil == 'medico'): ?>
<!-- Formulários de registro disponíveis apenas para médico -->
<div>
    <label><strong>Selecionar Paciente:</strong></label>
    <select id="selectPaciente" required>
        <option value="">-- Selecione um paciente --</option>
        <?php foreach($pacientes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?> (ID: <?= $p['id'] ?>)</option>
        <?php endforeach; ?>
    </select>
</div>

<?php
$acoes = [
    'evolucao' => 'Registrar Evolução',
    'prescricao' => 'Registrar Prescrição',
    'procedimento' => 'Registrar Procedimento',
    'exame' => 'Registrar Exame'
];
foreach($acoes as $acao => $label):
?>
<div>
    <h3><?= $label ?></h3>
    <form method="POST" action="../../controllers/ProntuarioController.php" class="form-prontuario">
        <input type="hidden" name="paciente_id" class="paciente_id_hidden">
        <?php if($acao !== 'exame'): ?>
            <textarea name="descricao" placeholder="Descreva..." required></textarea>
        <?php else: ?>
            <label>Tipo de Exame:</label>
            <select name="exame_id" required>
                <option value="">-- Selecione --</option>
                <?php
                $exames = $pdo->query("SELECT * FROM exames_cadastrados ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
                foreach($exames as $ex):
                ?>
                    <option value="<?= $ex['id'] ?>"><?= htmlspecialchars($ex['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <input type="hidden" name="acao" value="<?= $acao ?>">
        <button type="submit"><?= $label ?></button>
    </form>
</div>
<?php endforeach; ?>

<hr>
<?php endif; ?>

<!-- Histórico completo (disponível para todos) -->
<div>
<h3>Histórico Completo</h3>
<?php foreach($pacientes as $p): ?>
    <div>
        <h4><?= htmlspecialchars($p['nome']) ?> (ID: <?= $p['id'] ?>)</h4>

        <!-- Evoluções -->
        <h5>Evoluções:</h5>
        <?php $evol = Prontuario::listarEvolucoes($p['id']); ?>
        <?php if(empty($evol)): ?>
            <p>Nenhuma evolução registrada.</p>
        <?php else: ?>
            <?php foreach($evol as $e): ?>
                <p>
                    <strong><?= date('d/m/Y H:i', strtotime($e['data'])) ?></strong> - 
                    Dr(a). <?= htmlspecialchars($e['medico_nome']) ?>: 
                    <?= htmlspecialchars($e['descricao']) ?>
                    <?php if($perfil=='medico' && $e['medico_id'] == $medico_id_logado): ?>
                        <form method="POST" action="../../controllers/ProntuarioController.php">
                            <input type="hidden" name="acao" value="excluir_evolucao">
                            <input type="hidden" name="id" value="<?= $e['id'] ?>">
                            <button type="submit" onclick="return confirm('Excluir evolução?')">Excluir</button>
                        </form>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Prescrições -->
        <h5>Prescrições:</h5>
        <?php $presc = Prontuario::listarPrescricoes($p['id']); ?>
        <?php if(empty($presc)): ?>
            <p>Nenhuma prescrição registrada.</p>
        <?php else: ?>
            <?php foreach($presc as $pr): ?>
                <p>
                    <strong><?= date('d/m/Y H:i', strtotime($pr['data'])) ?></strong> - 
                    Dr(a). <?= htmlspecialchars($pr['medico_nome']) ?>: 
                    <?= htmlspecialchars($pr['descricao']) ?>
                    <?php if($perfil=='medico' && $pr['medico_id'] == $medico_id_logado): ?>
                        <form method="POST" action="../../controllers/ProntuarioController.php">
                            <input type="hidden" name="acao" value="excluir_prescricao">
                            <input type="hidden" name="id" value="<?= $pr['id'] ?>">
                            <button type="submit" onclick="return confirm('Excluir prescrição?')">Excluir</button>
                        </form>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Procedimentos -->
        <h5>Procedimentos:</h5>
        <?php $proc = Prontuario::listarProcedimentos($p['id']); ?>
        <?php if(empty($proc)): ?>
            <p>Nenhum procedimento registrado.</p>
        <?php else: ?>
            <?php foreach($proc as $prc): ?>
                <p>
                    <strong><?= date('d/m/Y H:i', strtotime($prc['data'])) ?></strong> - 
                    Dr(a). <?= htmlspecialchars($prc['medico_nome']) ?>: 
                    <?= htmlspecialchars($prc['descricao']) ?>
                    <?php if($perfil=='medico' && $prc['medico_id'] == $medico_id_logado): ?>
                        <form method="POST" action="../../controllers/ProntuarioController.php">
                            <input type="hidden" name="acao" value="excluir_procedimento">
                            <input type="hidden" name="id" value="<?= $prc['id'] ?>">
                            <button type="submit" onclick="return confirm('Excluir procedimento?')">Excluir</button>
                        </form>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Exames -->
        <h5>Exames:</h5>
        <?php $exs = Prontuario::listarExames($p['id']); ?>
        <?php if(empty($exs)): ?>
            <p>Nenhum exame registrado.</p>
        <?php else: ?>
            <?php foreach($exs as $ex): ?>
                <p>
                    <strong><?= date('d/m/Y H:i', strtotime($ex['data'])) ?></strong> - 
                    Dr(a). <?= htmlspecialchars($ex['medico_nome']) ?>: 
                    <?= htmlspecialchars($ex['exame_nome']) ?>
                    <?php if($perfil=='medico' && $ex['medico_id'] == $medico_id_logado): ?>
                        <form method="POST" action="../../controllers/ProntuarioController.php" style="display:inline;">
                            <input type="hidden" name="acao" value="excluir_exame">
                            <input type="hidden" name="id" value="<?= $ex['id'] ?>">
                            <button type="submit" onclick="return confirm('Excluir exame?')">Excluir</button>
                        </form>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
    <hr>
<?php endforeach; ?>
</div>

<a href="../dashboard.php"><button>Voltar ao Dashboard</button></a>

<?php if($perfil=='medico'): ?>
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

atualizarPacienteHidden();
selectPaciente.addEventListener('change', atualizarPacienteHidden);
</script>
<?php endif; ?>
