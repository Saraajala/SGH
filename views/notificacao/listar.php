
<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header('Location: ../index.php');
    exit;
}

include '../../models/Notificacao.php';

$notificacoes = Notificacao::listarPorUsuario($_SESSION['id_usuario']);
?>

<h2>Notificações</h2>
<table border="1">
    <tr>
        <th>Tipo</th>
        <th>Mensagem</th>
        <th>Data</th>
        <th>Lida</th>
    </tr>
    <?php foreach($notificacoes as $n): ?>
        <tr>
            <td><?= $n['tipo'] ?></td>
            <td><?= $n['mensagem'] ?></td>
            <td><?= $n['data_criacao'] ?></td>
            <td><?= $n['lida'] ? 'Sim' : 'Não' ?></td>
        </tr>
    <?php endforeach; ?>
</table>
