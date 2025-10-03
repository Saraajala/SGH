<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../index.php');
    exit;
}

include '../../models/Notificacao.php';

$notificacoes = Notificacao::listarPorUsuario($_SESSION['id_usuario']);
$naoLidas = Notificacao::contarNaoLidas($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Notificações</title>
</head>
<body>

<div class="contador">
    🔔 Você tem <strong><?= $naoLidas ?></strong> notificação<?= $naoLidas != 1 ? 'es' : '' ?> não lida<?= $naoLidas != 1 ? 's' : '' ?>.
</div>

<h2>Notificações</h2>

<table>
    <tr>
        <th>Tipo</th>
        <th>Mensagem</th>
        <th>Data</th>
        <th>Status</th>
        <th>Ação</th>
    </tr>

    <?php if (empty($notificacoes)): ?>
        <tr><td>Nenhuma notificação encontrada.</td></tr>
    <?php else: ?>
        <?php foreach ($notificacoes as $n): ?>
            <tr>
                <td><?= htmlspecialchars($n['tipo']) ?></td>
                <td>
                    <?php 
                        $mensagem = $n['mensagem'];
                        // Formata datas no formato YYYY-MM-DD às HH:MM
                        if (preg_match('/(\d{4}-\d{2}-\d{2})\s+às\s+(\d{2}:\d{2})/', $mensagem, $matches)) {
                            $dataHora = new DateTime($matches[1] . ' ' . $matches[2]);
                            $mensagem = str_replace($matches[0], $dataHora->format('d/m/Y \à\s H:i'), $mensagem);
                        }
                        echo htmlspecialchars($mensagem);
                    ?>
                </td>
                <td>
                    <?php
                        $data = new DateTime($n['data']);
                        echo $data->format('d/m/Y H:i');
                    ?>
                </td>
                <td class="<?= $n['lida'] ? 'lida' : 'nao-lida' ?>">
                    <?= $n['lida'] ? 'Lida' : 'Não Lida' ?>
                </td>
                <td>
                    <?php if (!$n['lida']): ?>
                        <a class="botao" href="../../controllers/NotificacaoController.php?acao=lida&id=<?= $n['id'] ?>">Marcar como lida</a>
                    <?php else: ?> - <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<a href="../dashboard.php">Voltar ao início</a>

</body>
</html>
