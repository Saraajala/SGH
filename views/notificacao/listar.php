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
    <style>
        body { font-family: Arial, sans-serif; background: #f2f2f2; margin: 0; padding: 20px; }
        h2 { text-align: center; color: #333; }
        .contador {
            background: #ffcc00;
            color: #333;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #4CAF50; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        a.botao { background: #4CAF50; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; }
        a.botao:hover { background: #45a049; }
        .lida { color: green; font-weight: bold; }
        .nao-lida { color: red; font-weight: bold; }
    </style>
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
        <tr><td colspan="5">Nenhuma notificação encontrada.</td></tr>
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
