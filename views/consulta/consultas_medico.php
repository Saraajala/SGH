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
$nome_medico = $_SESSION['nome']; // Nome do médico
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../favicon_round.png" type="image/png">
    <title>Minhas Consultas - Sistema de Gestão Hospitalar</title>
    <style>
        /* Estilos básicos */
        body { font-family: Arial, sans-serif; margin:0; padding:0; background:#f9fafb; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        h1, h2, h4 { margin: 0 0 16px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        .consulta-hoje { background: #fef3c7; }
        .btn { padding: 6px 12px; margin: 2px; cursor: pointer; border: none; border-radius: 4px; }
        .btn-realizada { background-color: #10b981; color: #fff; }
        .btn-cancelar { background-color: #ef4444; color: #fff; }
        .btn-nao-compareceu { background-color: #f59e0b; color: #fff; }
        .alert { padding: 10px 15px; margin-bottom: 15px; border-radius: 4px; color: #fff; }
        .alert-success { background-color: #10b981; }
        .alert-error { background-color: #ef4444; }
    </style>
</head>
<body>
<header class="header clinic-header">
    <nav class="nav">
        <div class="nav-left">
            <a href="../dashboard.php">
                <img src="../logo.png" alt="Logo Clínica Lumière" class="logo">
            </a>
            <h1>Clínica Lumière</h1>
        </div>

        <div class="nav-right">
            <span class="welcome-text">
                <?php
                if ($_SESSION['perfil'] === 'medico') {
                    echo 'Bem-vindo, Dr(a). ';
                } elseif ($_SESSION['perfil'] === 'enfermeiro') {
                    echo 'Bem-vindo, Enfermeiro(a) ';
                } else {
                    echo 'Bem-vindo, ';
                }

                echo htmlspecialchars($_SESSION['nome'] ?? 'Usuário');
                ?>
            </span>

            <ul class="menu-topo">
                <li><a href="../dashboard.php"><i class="fa fa-home icon"></i>Menu</a></li>
                <li><a href="../../index.php"><i class="fa fa-sign-out-alt icon"></i>Sair</a></li>
            </ul>
        </div>
    </nav>
</header>


<br><br><br><br>   <br><br><br> <h3>🩺 Minhas Consultas</h3>

<div class="container1">

    <!-- Mensagens -->
    <?php if(!empty($_SESSION['msg_sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['msg_sucesso']) ?></div>
        <?php unset($_SESSION['msg_sucesso']); ?>
    <?php endif; ?>
    <?php if(!empty($_SESSION['msg_erro'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <?php if(empty($consultas)): ?>
        <div>
            <p>Nenhuma consulta futura agendada.</p>
        </div>
    <?php else: ?>
        <table>
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
                        <?php if($eh_hoje): ?><br><small>(Hoje)</small><?php endif; ?>
                    </td>
                    <td><?= substr($consulta['hora'], 0, 5) ?></td>
                    <td><?= htmlspecialchars($consulta['paciente_nome']) ?></td>
                    <td><?= ucfirst($consulta['status']) ?></td>
                    <td>
                        <?php if($consulta['status'] == 'agendada' && $eh_hoje): ?>
                            <form method="POST" action="../../controllers/ConsultaController.php" style="display:inline-block;">
                                <input type="hidden" name="acao" value="marcar_realizada">
                                <input type="hidden" name="consulta_id" value="<?= $consulta['id'] ?>">
                                <button type="submit" class="btn btn-realizada">✓ Realizada</button>
                            </form>
                            <form method="POST" action="../../controllers/ConsultaController.php" style="display:inline-block;">
                                <input type="hidden" name="acao" value="cancelar">
                                <input type="hidden" name="consulta_id" value="<?= $consulta['id'] ?>">
                                <button type="submit" class="btn btn-cancelar">✗ Cancelar</button>
                            </form>
                            <form method="POST" action="../../controllers/ConsultaController.php" style="display:inline-block;">
                                <input type="hidden" name="acao" value="nao_compareceu">
                                <input type="hidden" name="consulta_id" value="<?= $consulta['id'] ?>">
                                <button type="submit" class="btn btn-nao-compareceu">⚠ Não Compareceu</button>
                            </form>
                        <?php elseif($consulta['status'] == 'agendada' && !$eh_hoje): ?>
                            <span>Botões disponíveis no dia <?= date('d/m/Y', strtotime($consulta['data'])) ?></span>
                        <?php else: ?>
                            <span>Consulta <?= $consulta['status'] ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<br><br><br><br><br><br><br>
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
</body>
</html>
