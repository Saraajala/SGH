<?php
session_start();
include '../../models/Consulta.php';

if(!isset($_SESSION['id_usuario'])){
    header('Location: ../login.php');
    exit;
}

$perfil = $_SESSION['perfil'];
$user_id = $_SESSION['id_usuario'];

if($perfil == 'paciente'){
    $medicos = Consulta::listarMedicos();
} elseif($perfil == 'medico'){
    $pacientes = Consulta::listarPacientes();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendar Consulta</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="estilo.css">
    <link rel="icon" href="../favicon_round.png" type="image/png"> 
    <style>
        /* Campo de busca (mantendo o estilo da sua página) */
        .search-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-bottom: 35px;
            position: relative;
            animation: fadeIn 0.5s ease;
        }

        .search-bar input {
            width: 100%;
            max-width: 420px;
            padding: 12px 14px 12px 40px;
            border: 2px solid #d8f0f9;
            border-radius: 14px;
            background-color: #f9fdff;
            font-size: 0.95rem;
            color: #005e6e;
            outline: none;
            transition: all 0.25s ease;
            background-image: url('https://cdn-icons-png.flaticon.com/512/622/622669.png');
            background-size: 18px;
            background-repeat: no-repeat;
            background-position: 12px center;
        }

        .search-bar input:focus {
            border-color: #48cae4;
            box-shadow: 0 0 0 4px rgba(72, 202, 228, 0.15);
        }

        .search-bar button {
            background: linear-gradient(135deg, #00a3a3, #00a3a3);
            border: none;
            border-radius: 12px;
            color: white;
            padding: 10px 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-bar button:hover {
            background: linear-gradient(135deg, #009393, #00bfbf);
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 163, 163, 0.25);
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
                <li><a href="../dashboard.php"><i class="fa fa-home icon"></i> Menu</a></li>
                <li><a href="../../index.php"><i class="fa fa-sign-out-alt icon"></i> Sair</a></li>
            </ul>
        </div>
    </nav>
</header>
<br><br><br><br><br>
<main style="display: flex; flex-direction: column; align-items: center; justify-content: flex-start; min-height: 80vh; margin-top: 60px;">
    
    <!-- Campo de busca -->
    <div class="search-bar">
        <input type="text" id="campoBusca" placeholder="Buscar <?= ($perfil == 'paciente') ? 'médico...' : 'paciente...' ?>" onkeyup="filtrarLista()">
        <button type="button"><i class="fa fa-search"></i></button>
    </div>

    <!-- Caixa de consulta -->
    <div class="consulta-container">
        <div class="consulta-box">
            <h3 style="text-align:center; color:#00a3a3; margin-bottom:25px;">Agendar Consulta</h3>

            <!-- Mensagens -->
            <?php if(!empty($_SESSION['msg_sucesso'])): ?>
                <div class="msg sucesso">
                    <?= htmlspecialchars($_SESSION['msg_sucesso']) ?>
                </div>
                <?php unset($_SESSION['msg_sucesso']); ?>
            <?php endif; ?>

            <?php if(!empty($_SESSION['msg_erro'])): ?>
                <div class="msg erro">
                    <?= htmlspecialchars($_SESSION['msg_erro']) ?>
                </div>
                <?php unset($_SESSION['msg_erro']); ?>
            <?php endif; ?>

            <!-- Formulário -->
            <form method="POST" action="../../controllers/ConsultaController.php" class="form-consulta">
                <?php if($perfil == 'paciente'): ?>
                    <input type="hidden" name="acao" value="agendar">
                    <label for="medico_id">Médico:</label>
                    <select id="medico_id" name="medico_id" required>
                        <option value="">-- Selecione o Médico --</option>
                        <?php foreach($medicos as $medico): ?>
                            <option value="<?= $medico['id'] ?>"><?= htmlspecialchars($medico['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php elseif($perfil == 'medico'): ?>
                    <input type="hidden" name="acao" value="marcar">
                    <label for="paciente_id">Paciente:</label>
                    <select id="paciente_id" name="paciente_id" required>
                        <option value="">-- Selecione o Paciente --</option>
                        <?php foreach($pacientes as $paciente): ?>
                            <option value="<?= $paciente['id'] ?>"><?= htmlspecialchars($paciente['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required min="<?= date('Y-m-d') ?>">

                <label for="hora">Hora:</label>
                <input type="time" id="hora" name="hora" required>

                <button type="submit" class="btn-submit">
                    <i class="fa fa-calendar-check"></i> Agendar Consulta
                </button>
            </form>
        </div>
    </div>
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
function filtrarLista() {
    const termo = document.getElementById('campoBusca').value.toLowerCase();
    const select = document.querySelector('<?php echo ($perfil == "paciente") ? "#medico_id" : "#paciente_id"; ?>');
    const options = select.querySelectorAll('option');

    options.forEach(option => {
        if (option.text.toLowerCase().includes(termo) || option.value === '') {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
}
</script>

</body>
</html>
