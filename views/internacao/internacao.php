<?php
session_start();
include __DIR__ . '/../../config.php';
include __DIR__ . '/../../models/Internacao.php';

// Apenas médico ou enfermeiro
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])){
    header('Location: ../index.php');
    exit;
}

// Mensagem de ação
$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

// Carregar dados
$internacoes = Internacao::listarInternacoes();
$quartos = Internacao::listarQuartos();
$quartos_disponiveis = Internacao::listarQuartosDisponiveis();
$pacientes_nao_internados = Internacao::listarPacientesNaoInternados();

// Função para formatar status dos quartos
function formatarStatusQuarto($status) {
    return empty($status) ? 'disponivel' : strtolower($status);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Internações</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section { margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .mensagem { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        form { margin: 10px 0; }
        button { padding: 5px 10px; margin: 2px; }
        .status-disponivel { color: green; font-weight: bold; }
        .status-ocupado { color: red; font-weight: bold; }
        .status-limpeza { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestão de Internações</h1>

        <?php if($mensagem): ?>
            <div class="mensagem <?php echo strpos($mensagem, 'sucesso') !== false ? 'sucesso' : 'erro'; ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <!-- Internar Paciente -->
        <div class="section">
            <h2>Internar Paciente</h2>
            <form method="POST" action="../../controllers/InternacaoController.php">
                <input type="hidden" name="acao" value="internar">
                
                <label>Paciente:</label>
                <select name="paciente_id" required>
                    <option value="">Selecione o paciente</option>
                    <?php foreach($pacientes_nao_internados as $paciente): ?>
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
        </div>

        <!-- Pacientes Internados -->
        <div class="section">
            <h2>Pacientes Internados</h2>
            <?php if(empty($internacoes)): ?>
                <p>Nenhum paciente internado no momento.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Ala</th>
                            <th>Quarto</th>
                            <th>Data Entrada</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($internacoes as $internacao): ?>
                        <tr>
                            <td><?= htmlspecialchars($internacao['paciente_nome']) ?></td>
                            <td><?= htmlspecialchars($internacao['medico_nome']) ?></td>
                            <td><?= htmlspecialchars($internacao['ala']) ?></td>
                            <td><?= htmlspecialchars($internacao['quarto_numero']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($internacao['data_entrada'])) ?></td>
                            <td>
                                <!-- Dar Alta -->
                                <form method="POST" action="../../controllers/InternacaoController.php" style="display: inline;">
                                    <input type="hidden" name="acao" value="dar_alta">
                                    <input type="hidden" name="internacao_id" value="<?= $internacao['id'] ?>">
                                    <button type="submit" onclick="return confirm('Deseja dar alta a este paciente?')">Dar Alta</button>
                                </form>
                                <!-- Transferir -->
                                <form method="POST" action="../../controllers/InternacaoController.php" style="display: inline;">
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
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Status dos Quartos -->
        <div class="section">
            <h2>Status dos Quartos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Ala</th>
                        <th>Quarto</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($quartos as $quarto): ?>
                    <?php $status = formatarStatusQuarto($quarto['status']); ?>
                    <tr>
                        <td><?= htmlspecialchars($quarto['ala']) ?></td>
                        <td><?= htmlspecialchars($quarto['numero']) ?></td>
                        <td class="status-<?= $status ?>"><?= ucfirst($status) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
