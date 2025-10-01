<?php
session_start();
include __DIR__ . '/../../config.php';
include __DIR__ . '/../../models/Internacao.php';

if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])){
    header('Location: ../index.php');
    exit;
}

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

$internacoes = Internacao::listarInternacoes();
$quartos = Internacao::listarQuartos();
$quartos_disponiveis = Internacao::listarQuartosDisponiveis();
$pacientes = Internacao::listarPacientesNaoInternados();

function formatarStatusQuarto($status) {
    return empty($status) ? 'disponivel' : strtolower($status);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Internações</title>
</head>
<body>
    <h1>Gestão de Internações</h1>

    <?php if($mensagem): ?>
        <div style="padding:10px; margin:10px; border:1px solid #ccc;">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <h2>Internar Paciente</h2>
    <form method="POST" action="../../controllers/InternacaoController.php">
        <input type="hidden" name="acao" value="internar">

        <label>Paciente:</label>
        <select name="paciente_id" required>
            <option value="">Selecione o paciente</option>
            <?php foreach($pacientes as $paciente): ?>
                <option value="<?= $paciente['id'] ?>"><?= htmlspecialchars($paciente['nome']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Quarto:</label>
        <select name="quarto_id" required>
            <option value="">Selecione o quarto</option>
            <?php foreach($quartos_disponiveis as $quarto): ?>
                <option value="<?= $quarto['id'] ?>">
                    Ala <?= htmlspecialchars($quarto['ala']) ?> - Quarto <?= htmlspecialchars($quarto['numero']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Internar Paciente</button>
    </form>

    <h2>Pacientes Internados</h2>
    <?php if(empty($internacoes)): ?>
        <p>Nenhum paciente internado no momento.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Ala</th>
                <th>Quarto</th>
                <th>Data Entrada</th>
                <th>Ações</th>
            </tr>
            <?php foreach($internacoes as $internacao): ?>
            <tr>
                <td><?= htmlspecialchars($internacao['paciente_nome']) ?></td>
                <td><?= htmlspecialchars($internacao['medico_nome']) ?></td>
                <td><?= htmlspecialchars($internacao['ala']) ?></td>
                <td><?= htmlspecialchars($internacao['quarto_numero']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($internacao['data_entrada'])) ?></td>
                <td>
                    <form method="POST" action="../../controllers/InternacaoController.php" style="display:inline;">
                        <input type="hidden" name="acao" value="dar_alta">
                        <input type="hidden" name="internacao_id" value="<?= $internacao['id'] ?>">
                        <button type="submit">Dar Alta</button>
                    </form>
                    <form method="POST" action="../../controllers/InternacaoController.php" style="display:inline;">
                        <input type="hidden" name="acao" value="transferir">
                        <input type="hidden" name="internacao_id" value="<?= $internacao['id'] ?>">
                        <select name="novo_quarto_id" required>
                            <option value="">Novo quarto</option>
                            <?php foreach($quartos_disponiveis as $quarto): ?>
                                <?php if($quarto['id'] != $internacao['quarto_id']): ?>
                                    <option value="<?= $quarto['id'] ?>">
                                        Ala <?= htmlspecialchars($quarto['ala']) ?> - Quarto <?= htmlspecialchars($quarto['numero']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Transferir</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <h2>Status dos Quartos</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Ala</th>
            <th>Quarto</th>
            <th>Status</th>
        </tr>
        <?php foreach($quartos as $quarto): ?>
        <?php $status = formatarStatusQuarto($quarto['status']); ?>
        <tr>
            <td><?= htmlspecialchars($quarto['ala']) ?></td>
            <td><?= htmlspecialchars($quarto['numero']) ?></td>
            <td class="status-<?= $status ?>"><?= ucfirst($status) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br><br>

    <a href="../dashboard.php">Voltar ao início</a>

    <script>
    document.addEventListener("DOMContentLoaded", function(){
        let linhas = document.querySelectorAll("td.status-limpeza");
        linhas.forEach(function(td){
            let quarto = td.closest("tr").children[1].innerText;
            setTimeout(function(){
                fetch("../../controllers/InternacaoController.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "acao=liberar_quarto&numero_quarto=" + quarto
                }).then(() => location.reload());
            }, 10000);
        });
    });
    </script>
</body>
</html>
