<?php
session_start();

// Controle de acesso
if (!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])) {
    $_SESSION['msg_erro'] = "Acesso negado.";
    header('Location: ../views/dashboard.php');
    exit;
}

// Perfil do usuário
$perfil = $_SESSION['perfil'];

// Incluindo modelos e configuração
include '../../models/Paciente.php';
include '../../models/Prontuario.php';
include '../../config.php';

// Lista de pacientes
$pacientes = Paciente::listar();

// ID do usuário logado
$medico_id_logado = $_SESSION['id_usuario'] ?? null;

// Nome do usuário para exibir no header
$nome_usuario = 'Usuário';
if ($medico_id_logado) {
    $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->execute([$medico_id_logado]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario) {
        $nome_usuario = $usuario['nome'];
    }
}

// Prefixo de tratamento (Dr(a).) para médicos
$tratamento = ($perfil === 'medico') ? 'Dr(a).' : '';
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Prontuario - Sistema de Gestão Hospitalar</title>
    <link rel="icon" href="../favicon_round.png" type="image/png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="estilo.css">
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

<br><br><br><br>
<div class="container">
    <h3>Prontuário Eletrônico</h3>

    <!-- Mensagens -->
    <?php if(!empty($_SESSION['msg_sucesso'])): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($_SESSION['msg_sucesso']) ?></div>
        <?php unset($_SESSION['msg_sucesso']); ?>
    <?php endif; ?>
    <?php if(!empty($_SESSION['msg_erro'])): ?>
        <div class="alert alert-error">❌ <?= htmlspecialchars($_SESSION['msg_erro']) ?></div>
        <?php unset($_SESSION['msg_erro']); ?>
    <?php endif; ?>

    <?php if($perfil == 'medico'): ?>
    <!-- Seleção de paciente -->
    <div class="patient-selector">
        <label>🧑‍⚕️ <strong>Selecionar Paciente:</strong></label>
        <select id="selectPaciente" required>
            <option value="">-- Selecione um paciente --</option>
            <?php foreach($pacientes as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?> </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Formulários Médicos -->
    <div class="forms-section">
        <?php
        $acoes = [
            'evolucao' => ['label'=>'Registrar Evolução','icon'=>'📝'],
            'prescricao' => ['label'=>'Registrar Prescrição','icon'=>'💊'],
            'procedimento' => ['label'=>'Registrar Procedimento','icon'=>'🔪'],
            'exame' => ['label'=>'Registrar Exame','icon'=>'🔬']
        ];
        foreach($acoes as $acao => $info):
        ?>
        <div class="form-card">
            <h3><?= $info['icon'] ?> <?= $info['label'] ?></h3>
            <form method="POST" action="../../controllers/ProntuarioController.php" class="form-prontuario unified-form">
                <input type="hidden" name="paciente_id" class="paciente_id_hidden">
                <?php if($acao !== 'exame'): ?>
                    <textarea name="descricao" placeholder="Descreva..." required></textarea>
                <?php else: ?>
                    <label>Tipo de Exame:</label>
                    <select name="exame_id" required>
                        <option value="">-- Selecione --</option>
                        <?php
                        $exames = $pdo->query("SELECT * FROM exames_cadastrados ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
                        foreach($exames as $ex):
                        ?>
                            <option value="<?= $ex['id'] ?>"><?= htmlspecialchars($ex['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <input type="hidden" name="acao" value="<?= $acao ?>">
                <button type="submit"><?= $info['icon'] ?> <?= $info['label'] ?></button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Histórico Completo -->
    <div class="history-section">
        <h3 class="section-title">📋 Histórico Completo</h3>
        <?php foreach($pacientes as $p): ?>
        <div class="patient-history">
            <div class="patient-name">🧑 <?= htmlspecialchars($p['nome']) ?> </div>

            <?php 
            $registros = [
                'Evoluções' => ['lista'=>Prontuario::listarEvolucoes($p['id']),'icon'=>'📝','acao_excluir'=>'excluir_evolucao'],
                'Prescrições' => ['lista'=>Prontuario::listarPrescricoes($p['id']),'icon'=>'💊','acao_excluir'=>'excluir_prescricao'],
                'Procedimentos' => ['lista'=>Prontuario::listarProcedimentos($p['id']),'icon'=>'🔪','acao_excluir'=>'excluir_procedimento'],
                'Exames' => ['lista'=>Prontuario::listarExames($p['id']),'icon'=>'🔬','acao_excluir'=>'excluir_exame']
            ];
            foreach($registros as $titulo => $info):
            ?>
            <div class="record-section">
                <h5><?= $info['icon'] ?> <?= $titulo ?>:</h5>
                <?php if(empty($info['lista'])): ?>
                    <p class="no-records">Nenhum registro.</p>
                <?php else: ?>
                    <?php foreach($info['lista'] as $item): ?>
                    <div class="record-item">
                        <div class="record-content">
                            <div class="record-date">📅 <?= date('d/m/Y H:i', strtotime($item['data'])) ?></div>
                            <div class="record-doctor">👨‍⚕️ <?= $tratamento ?> <?= htmlspecialchars($item['medico_nome']) ?></div>
                            <div class="record-description"><?= $info['icon'] ?> <?= htmlspecialchars($item['descricao'] ?? $item['exame_nome'] ?? '') ?></div>
                        </div>
                        <?php if($perfil=='medico' && $item['medico_id'] == $medico_id_logado): ?>
                        <form method="POST" action="../../controllers/ProntuarioController.php">
                            <input type="hidden" name="acao" value="<?= $info['acao_excluir'] ?>">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="delete-btn" onclick="return confirm('Excluir registro?')">🗑️ Excluir</button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
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


<?php if($perfil=='medico'): ?>
<script>
const selectPaciente = document.getElementById('selectPaciente');
const forms = document.querySelectorAll('.form-prontuario');

function atualizarPacienteHidden() {
    const pacienteId = selectPaciente.value;
    forms.forEach(form => {
        const hidden = form.querySelector('.paciente_id_hidden');
        if(hidden) hidden.value = pacienteId;
    });
}

atualizarPacienteHidden();
selectPaciente.addEventListener('change', atualizarPacienteHidden);
</script>
<?php endif; ?>
</body>
</html>