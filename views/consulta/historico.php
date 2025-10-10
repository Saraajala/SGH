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
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ===== TABELA ESTILIZADA ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Segoe UI', sans-serif;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0f7fa;
            font-size: 0.95rem;
        }
        th {
            background-color: #00a3a3;
            color: #fff;
            font-weight: 600;
        }
        tbody tr {
            background: #f9fdff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
        }
        tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 160, 160, 0.2);
        }
        .consulta-hoje {
            background-color: #d1f7d6 !important;
            font-weight: 600;
        }
        /* ===== STATUS ===== */
        .status-agendada { color: #0b8457; font-weight: 600; }
        .status-cancelada { color: #b10000; font-weight: 600; }
        .status-concluida { color: #005e6e; font-weight: 600; }
        /* ===== ÍCONES ===== */
        /* ===== ANIMAÇÃO DE APARECER ===== */
        tbody tr {
            opacity: 0;
            transform: translateY(10px);
            animation: aparecer 0.5s forwards;
        }
        @keyframes aparecer {
            to { opacity: 1; transform: translateY(0); }
        }
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

<br><br><br><br><br>
<div class="container">
    <h3>Minhas Consultas Futuras</h3>

    <?php if(count($consultas) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th><i class="fa fa-calendar icon"></i>Data</th>
                    <th><i class="fa fa-clock icon"></i>Hora</th>
                    <th><i class="fa fa-user-md icon"></i>Médico</th>
                    <th><i class="fa fa-info-circle icon"></i>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($consultas as $c): 
                    $eh_hoje = $c['data'] == $data_hoje;
                    $classe_linha = $eh_hoje ? 'consulta-hoje' : '';
                ?>
                <tr class="<?= $classe_linha ?>">
                    <td>
                        <?= date("d/m/Y", strtotime($c['data'])) ?>
                        <?php if($eh_hoje): ?>
                            <br><small>(Hoje)</small>
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
</div>
<br><br><br>
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
