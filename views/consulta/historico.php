<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil'] != 'paciente') {
    header("Location: ../login.php");
    exit;
}

include '../../config.php';

// Pega ID do paciente logado
$paciente_id = $_SESSION['id_usuario'];

// Busca consultas do paciente
$sql = "SELECT c.id, c.data, c.hora, c.status, u.nome AS medico_nome
        FROM consultas c
        JOIN usuarios u ON c.medico_id = u.id
        WHERE c.paciente_id = '$paciente_id'
        ORDER BY c.data DESC, c.hora DESC";
$stmt = $pdo->query($sql);
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Histórico de Consultas</h2>

<?php if(count($consultas) > 0): ?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Data</th>
        <th>Hora</th>
        <th>Médico</th>
        <th>Status</th>
    </tr>
    <?php foreach($consultas as $c): ?>
    <tr>
        <td><?= $c['id'] ?></td>
        <td><?= date("d/m/Y", strtotime($c['data'])) ?></td>
        <td><?= $c['hora'] ?></td>
        <td><?= $c['medico_nome'] ?></td>
        <td><?= ucfirst($c['status']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p>Você ainda não possui consultas no histórico.</p>
<?php endif; ?>

<p><a href="../dashboard.php">Voltar ao Dashboard</a></p>
