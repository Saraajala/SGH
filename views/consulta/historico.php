<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil'] != 'paciente') {
    header("Location: ../login.php");
    exit;
}

include '../../models/Consulta.php';

$paciente_id = $_SESSION['id_usuario'];
$consultas = Consulta::listarConsultasFuturasPaciente($paciente_id);
$data_hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Consultas Futuras</title>
    <style>
        .status-agendada { color: blue; font-weight: bold; }
        .status-realizada { color: green; font-weight: bold; }
        .status-cancelada { color: red; font-weight: bold; }
        .status-nao_compareceu { color: orange; font-weight: bold; }
        .consulta-hoje { background-color: #e8f5e8; }
    </style>
</head>
<body>
    <h2>Minhas Consultas Futuras</h2>

    <?php if(count($consultas) > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Data</th>
                <th>Hora</th>
                <th>Médico</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($consultas as $c): 
                $eh_hoje = $c['data'] == $data_hoje;
                $classe_linha = $eh_hoje ? 'consulta-hoje' : '';
            ?>
            <tr class="<?= $classe_linha ?>">
                <td><?= $c['id'] ?></td>
                <td>
                    <?= date("d/m/Y", strtotime($c['data'])) ?>
                    <?php if($eh_hoje): ?>
                        <br><small style="color: green;">(Hoje)</small>
                    <?php endif; ?>
                </td>
                <td><?= substr($c['hora'], 0, 5) ?></td>
                <td><?= htmlspecialchars($c['medico_nome']) ?></td>
                <td>
                    <span class="status-<?= $c['status'] ?>">
                        <?= ucfirst($c['status']) ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p><small>Consultas realizadas de dias anteriores não são exibidas.</small></p>
    <?php else: ?>
        <p>Você não possui consultas futuras agendadas.</p>
    <?php endif; ?>

    <br>
    <a href="../dashboard.php">← Voltar ao Dashboard</a>
</body>
</html>