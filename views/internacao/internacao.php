<?php
session_start();
include __DIR__ . '/../../config.php';
include __DIR__ . '/../../models/Internacao.php';

/* ==============================
   Controle de acesso
============================== */
if (!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico', 'enfermeiro'])) {
    header('Location: ../index.php');
    exit;
}

/* ==============================
   Mensagens e dados principais
============================== */
$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

$internacoes = Internacao::listarInternacoes();
$quartos = Internacao::listarQuartos();
$quartos_disponiveis = Internacao::listarQuartosDisponiveis();
$pacientes = Internacao::listarPacientesNaoInternados();

/* ==============================
   Função auxiliar
============================== */
function formatarStatusQuarto($status)
{
    return empty($status) ? 'disponivel' : strtolower($status);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Internações</title>
    <link rel="stylesheet" href="internacao.css">
        <link rel="icon" href="../favicon_round.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- Sidebar -->
<header class="header clinic-header">
    <nav class="nav">
        <div class="nav-left">
<a href="../dashboard.php">
    <img src="../logo.png" alt="Logo Clínica Lumière" class="logo">
</a>             <h1>Clínica Lumière</h1>
        </div>

     <div class="nav-right">
    <span class="welcome-text">
        Bem-vindo, 
        <?= ($_SESSION['perfil'] === 'medico') ? 'Dr(a). ' : '' ?>
        <?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?>
    </span>

    <ul class="menu-topo">
       <li><a href="../dashboard.php"><i class="fa fa-home icon"></i>Menu</a></li>
                <li><a href="../../index.php"><i class="fa fa-sign-out-alt icon"></i>Sair</a></li>
    </ul>
</div>

    </nav>
</header>

<!-- CONTEÚDO PRINCIPAL -->
<main class="main-content">
    <h3>Gestão de Internações</h3>

    <?php if ($mensagem): ?>
        <div class="alert">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>


    <!-- Formulário de Internação -->
    <section class="card">
        <h2>Internar Paciente</h2>
        <form method="POST" action="../../controllers/InternacaoController.php" class="form-inline">
            <input type="hidden" name="acao" value="internar">

            <label>Paciente:</label>
            <select name="paciente_id" required>
                <option value="">Selecione</option>
                <?php foreach ($pacientes as $paciente): ?>
                    <option value="<?= $paciente['id'] ?>"><?= htmlspecialchars($paciente['nome']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Quarto:</label>
            <select name="quarto_id" required>
                <option value="">Selecione</option>
                <?php foreach ($quartos_disponiveis as $quarto): ?>
                    <option value="<?= $quarto['id'] ?>">
                        Ala <?= htmlspecialchars($quarto['ala']) ?> - Quarto <?= htmlspecialchars($quarto['numero']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-primary">Internar</button>
        </form>
    </section>

    <!-- Lista de Pacientes Internados -->
    <section class="card">
        <h2>Pacientes Internados</h2>
        <?php if (empty($internacoes)): ?>
            <p>Nenhum paciente internado no momento.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Ala</th>
                    <th>Quarto</th>
                    <th>Data Entrada</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($internacoes as $internacao): ?>
                <tr>
                    <td><?= htmlspecialchars($internacao['paciente_nome']) ?></td>
                    <td>Dr(a). <?= htmlspecialchars($internacao['medico_nome']) ?></td>
                    <td><?= htmlspecialchars($internacao['ala']) ?></td>
                    <td><?= htmlspecialchars($internacao['quarto_numero']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($internacao['data_entrada'])) ?></td>
                    <td class="acoes">
                        <form method="POST" action="../../controllers/InternacaoController.php" class="inline-form">
                            <input type="hidden" name="acao" value="dar_alta">
                            <input type="hidden" name="internacao_id" value="<?= $internacao['id'] ?>">
                            <button type="submit" class="btn-outline">Dar Alta</button>
                        </form>
                        <form method="POST" action="../../controllers/InternacaoController.php" class="inline-form">
                            <input type="hidden" name="acao" value="transferir">
                            <input type="hidden" name="internacao_id" value="<?= $internacao['id'] ?>">
                            <select name="novo_quarto_id" required>
                                <option value="">Novo quarto</option>
                                <?php foreach ($quartos_disponiveis as $quarto): ?>
                                    <?php if ($quarto['id'] != $internacao['quarto_id']): ?>
                                        <option value="<?= $quarto['id'] ?>">
                                            Ala <?= htmlspecialchars($quarto['ala']) ?> - Quarto <?= htmlspecialchars($quarto['numero']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn-secondary">Transferir</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </section>

    <!-- Status dos Quartos -->
    <section class="card">
        <h2>Status dos Quartos</h2>
        <table>
            <tr>
                <th>Ala</th>
                <th>Quarto</th>
                <th>Status</th>
            </tr>
            <?php foreach ($quartos as $quarto): ?>
            <?php $status = formatarStatusQuarto($quarto['status']); ?>
            <tr>
                <td><?= htmlspecialchars($quarto['ala']) ?></td>
                <td><?= htmlspecialchars($quarto['numero']) ?></td>
                <td class="status-<?= $status ?>"><?= ucfirst($status) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <div class="footer-logo-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM10 18a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="footer-logo-text">Lumière</h3>
                </div>
                <p class="footer-description">Cuidando de você e sua família há mais de 30 anos com excelência e dedicação.</p>
            </div>
            <div class="footer-section">
                <h4 class="footer-title">Serviços</h4>
                <ul class="footer-list">
                    <li>Consultas</li>
                    <li>Procedimentos</li>
                    <li>Internações</li>
                    <li>Exames</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4 class="footer-title">Contato</h4>
                <ul class="footer-list">
                    <li>📞 (11) 1234-5678</li>
                    <li>✉️ contato@clinicalumiere.com</li>
                    <li>📍 Rua Lumière, 123</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4 class="footer-title">Redes Sociais</h4>
                <ul class="footer-list">
                    <li>Facebook</li>
                    <li>Instagram</li>
                    <li>LinkedIn</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">© 2025 Clínica Lumière. Todos os direitos reservados.</div>
    </div>
</footer>

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
