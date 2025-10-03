<?php
session_start();
if(!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 'medico'){
    $_SESSION['msg_erro'] = "Acesso negado.";
    header('Location: ../dashboard.php');
    exit;
}

include '../../models/Consulta.php';

$medico_id = $_SESSION['id_usuario'];
$consultas = Consulta::listarConsultasFuturasMedico($medico_id);
$data_hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Consultas</title>
</head>
<body>
    <h2>Minhas Consultas Futuras</h2>

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

    <?php if(empty($consultas)): ?>
        <p>Nenhuma consulta futura agendada.</p>
    <?php else: ?>
        <table border="1" width="100%" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($consultas as $consulta): 
                    $eh_hoje = $consulta['data'] == $data_hoje;
                    $classe_linha = $eh_hoje ? 'consulta-hoje' : '';
                ?>
                <tr class="<?= $classe_linha ?>">
                    <td>
                        <?= date('d/m/Y', strtotime($consulta['data'])) ?>
                        <?php if($eh_hoje): ?>
                            <br><small>(Hoje)</small>
                        <?php endif; ?>
                    </td>
                    <td><?= substr($consulta['hora'], 0, 5) ?></td>
                    <td><?= htmlspecialchars($consulta['paciente_nome']) ?></td>
                    <td>
                        <span class="status-<?= $consulta['status'] ?>">
                            <?= ucfirst($consulta['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($consulta['status'] == 'agendada' && $eh_hoje): ?>
                            <!-- Botões só aparecem no dia da consulta -->
                            <form method="POST" action="../../controllers/ConsultaController.php">
                                <input type="hidden" name="acao" value="marcar_realizada">
                                <input type="hidden" name="consulta_id" value="<?= $consulta['id'] ?>">
                                <button type="submit" class="btn btn-realizada">✓ Realizada</button>
                            </form>
                            
                            <form method="POST" action="../../controllers/ConsultaController.php">
                                <input type="hidden" name="acao" value="cancelar">
                                <input type="hidden" name="consulta_id" value="<?= $consulta['id'] ?>">
                                <button type="submit" class="btn btn-cancelar">✗ Cancelar</button>
                            </form>
                            
                            <form method="POST" action="../../controllers/ConsultaController.php">
                                <input type="hidden" name="acao" value="nao_compareceu">
                                <input type="hidden" name="consulta_id" value="<?= $consulta['id'] ?>">
                                <button type="submit" class="btn btn-nao-compareceu">⚠ Não Compareceu</button>
                            </form>
                        <?php elseif($consulta['status'] == 'agendada' && !$eh_hoje): ?>
                            <span>
                                Botões disponíveis no dia <?= date('d/m/Y', strtotime($consulta['data'])) ?>
                            </span>
                        <?php else: ?>
                            <span>Consulta <?= $consulta['status'] ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p><small>Consultas realizadas de dias anteriores não são exibidas.</small></p>
    <?php endif; ?>

    <br>
    <a href="../dashboard.php">← Voltar ao Início</a>
</body>
</html>