<?php
session_start();
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','recepcao','enfermeiro'])){
    $_SESSION['msg_erro'] = "Acesso negado.";
    header('Location: ../views/dashboard.php');
    exit;
}

include '../../models/Farmacia.php';
include '../../models/Paciente.php';

$medicamentos = Farmacia::listarMedicamentos();
$pacientes = Paciente::listar();
$dispensacoes = Farmacia::listarDispensacoes();

// 🔍 Filtragem por nome do paciente (busca tabela)
if (!empty($_GET['busca_paciente'])) {
    $busca = strtolower(trim($_GET['busca_paciente']));
    $dispensacoes = array_filter($dispensacoes, function($disp) use ($busca) {
        return strpos(strtolower($disp['paciente_nome']), $busca) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Farmácia - Clínica Lumière</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="estilo.css">
<link rel="icon" href="../favicon_round.png" type="image/png"> 
<style>
/* Estilos adicionais */
.search-form {
  margin-bottom: 15px;
  display: flex;
  gap: 10px;
  align-items: center;
}

.search-form input[type="text"] {
  border: 1px solid #aaa;
  border-radius: 8px;
  padding: 8px 12px;
  flex: 1;
  transition: 0.3s;
}

.search-form input[type="text"]:focus {
  border-color: #007bff;
  box-shadow: 0 0 4px rgba(0, 123, 255, 0.4);
}

.search-form button {
  background-color: #118dacff;
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}

.search-form button:hover {
  background-color: #0056b3;
}

/* Campo de busca na área de dispensação */
.paciente-search {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
  gap: 8px;
}

.paciente-search input {
  flex: 1;
  border: 1px solid #aaa;
  border-radius: 6px;
  padding: 6px 10px;
  transition: 0.3s;
}

.paciente-search input:focus {
  border-color: #118dacff;
  box-shadow: 0 0 4px rgba(17, 141, 172, 0.4);
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
<h2>Controle da Farmácia</h2>

<div class="main-container">

    <!-- Mensagens -->
    <?php if(!empty($_SESSION['msg_sucesso'])): ?>
        <div class="messages msg-sucesso"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['msg_sucesso']) ?></div>
        <?php unset($_SESSION['msg_sucesso']); ?>
    <?php endif; ?>

    <?php if(!empty($_SESSION['msg_erro'])): ?>
        <div class="messages msg-erro"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <div class="forms-container">
        <div class="form-section">
            <h3><i class="fa-solid fa-capsules"></i> Cadastrar Medicamento</h3>
            <form method="POST" action="../../controllers/FarmaciaController.php">
                <input type="hidden" name="acao" value="cadastrar">
                <input type="text" name="nome" placeholder="Nome do medicamento" required>
                <textarea name="descricao" placeholder="Descrição do medicamento (opcional)"></textarea>
                <input type="number" name="quantidade" placeholder="Quantidade em estoque" required>
                <button type="submit"><i class="fa-solid fa-plus"></i> Cadastrar</button>
            </form>
        </div>

        <div class="form-section">
            <h3><i class="fa-solid fa-hand-holding-medical"></i> Dispensar Medicamento</h3>
            <form method="POST" action="../../controllers/FarmaciaController.php">
                <input type="hidden" name="acao" value="dispensar">
                
                <label><i class="fa-solid fa-pills"></i> Medicamento:</label>
                <select name="medicamento_id" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach($medicamentos as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nome']) ?> (Estoque: <?= $m['quantidade'] ?>)</option>
                    <?php endforeach; ?>
                </select>

                <label><i class="fa-solid fa-user"></i> Paciente:</label>

                <!-- 🔍 Campo de busca de paciente -->
                <div class="paciente-search">
                    <i class="fa fa-search"></i>
                    <input type="text" id="buscarPaciente" placeholder="Digite para buscar paciente...">
                </div>

                <select id="pacienteSelect" name="paciente_id" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach($pacientes as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="number" name="quantidade" placeholder="Quantidade" required>
                <button type="submit"><i class="fa-solid fa-share-from-square"></i> Dispensar</button>
            </form>
        </div>
    </div>

    <h3><i class="fa-solid fa-boxes-stacked"></i> Saídas de Medicamentos</h3>

    <!-- 🔍 Campo de busca da tabela -->
    <form method="GET" action="" class="search-form">
        <input type="text" name="busca_paciente" placeholder="Buscar por nome do paciente"
               value="<?= htmlspecialchars($_GET['busca_paciente'] ?? '') ?>">
        <button type="submit"><i class="fa fa-search"></i> Buscar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Medicamento</th>
                <th>Paciente</th>
                <th>Quantidade</th>
                <th>Dispensado por</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($dispensacoes)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Nenhuma dispensação registrada</td>
                </tr>
            <?php else: ?>
                <?php foreach($dispensacoes as $dispensa): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($dispensa['data_cadastro'])) ?></td>
                    <td><?= htmlspecialchars($dispensa['medicamento_nome']) ?></td>
                    <td><?= htmlspecialchars($dispensa['paciente_nome']) ?></td>
                    <td align="center"><?= $dispensa['quantidade'] ?></td>
                    <td><?= htmlspecialchars($dispensa['usuario_nome']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="../dashboard.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Voltar ao Início</a>
</div>

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

<!-- 🔍 Script JS para busca dinâmica de pacientes -->
<script>
document.getElementById('buscarPaciente').addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    const select = document.getElementById('pacienteSelect');
    const opcoes = select.querySelectorAll('option');

    opcoes.forEach(opt => {
        const texto = opt.textContent.toLowerCase();
        opt.style.display = texto.includes(filtro) ? '' : 'none';
    });
});
</script>

</body>
</html>
