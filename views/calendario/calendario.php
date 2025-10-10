<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

include '../../config.php';

$id_usuario = $_SESSION['id_usuario'];
$perfil     = $_SESSION['perfil'];
$data_hoje  = date('Y-m-d');

/* Pegar dados do usuário logado */
$stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$nome_usuario = $usuario['nome'] ?? 'Usuário';
$tratamento = ($perfil == 'medico') ? 'Dr(a).' : '';

/* Buscar todos os eventos do usuário */
$eventos = [];

/* Função para gerar className dos status */
function getStatusClass($status){
    switch($status){
        case 'agendada': return 'consulta';
        case 'realizada': return 'consulta-realizada';
        case 'cancelada': return 'consulta-cancelada';
        case 'nao-compareceu': return 'consulta-nao-compareceu';
        default: return '';
    }
}

/* Paciente */
if ($perfil === 'paciente') {
    $sql = "SELECT c.id, c.data, c.hora, u.nome AS medico, c.status
            FROM consultas c
            JOIN usuarios u ON c.medico_id = u.id
            WHERE c.paciente_id = ?
            AND (c.status IN ('agendada','realizada','cancelada','nao-compareceu')
                 AND (c.data >= ? OR c.status='realizada' AND c.data = ?))
            ORDER BY c.data, c.hora";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $data_hoje, $data_hoje]);
    $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($consultas as $c) {
        $eventos[] = [
            'title' => 'Consulta com Dr(a). '.$c['medico'] . ' (' . ucfirst($c['status']) . ')',
            'start' => $c['data'].'T'.$c['hora'],
            'hora' => $c['hora'],
            'medico' => $c['medico'],
            'status' => $c['status'],
            'tipo' => 'consulta',
            'className' => getStatusClass($c['status'])
        ];
    }
}

/* Médico */
elseif ($perfil === 'medico') {
    $sql = "SELECT c.id, c.data, c.hora, u.nome AS paciente, c.status
            FROM consultas c
            JOIN usuarios u ON c.paciente_id = u.id
            WHERE c.medico_id = ?
            AND (c.status IN ('agendada','realizada','cancelada','nao-compareceu')
                 AND (c.data >= ? OR c.status='realizada' AND c.data = ?))
            ORDER BY c.data, c.hora";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $data_hoje, $data_hoje]);
    $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($consultas as $c) {
        $eventos[] = [
            'title' => 'Consulta - '.$c['paciente'] . ' (' . ucfirst($c['status']) . ')',
            'start' => $c['data'].'T'.$c['hora'],
            'hora' => $c['hora'],
            'paciente' => $c['paciente'],
            'status' => $c['status'],
            'tipo' => 'consulta',
            'className' => getStatusClass($c['status'])
        ];
    }

    $sql = "SELECT pr.id, pr.data, pr.descricao, u.nome AS paciente
            FROM procedimentos pr
            JOIN usuarios u ON pr.paciente_id = u.id
            WHERE pr.medico_id = ?
            AND (pr.data >= ?)
            ORDER BY pr.data";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $data_hoje]);
    $procedimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($procedimentos as $p) {
        $eventos[] = [
            'title' => 'Procedimento: '.$p['descricao'].' - '.$p['paciente'],
            'start' => $p['data'],
            'paciente' => $p['paciente'],
            'tipo' => 'procedimento',
            'className' => 'procedimento'
        ];
    }
}

/* Enfermeiro */
elseif ($perfil === 'enfermeiro') {
    $sql = "SELECT i.data_internacao, i.data_alta, u.nome AS paciente, q.numero AS quarto
            FROM internacoes i
            JOIN usuarios u ON i.paciente_id = u.id
            JOIN quartos q ON i.quarto_id = q.id
            WHERE (i.data_internacao >= ? OR i.data_alta IS NULL OR i.data_alta >= ?)
            ORDER BY i.data_internacao DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data_hoje, $data_hoje]);
    $internacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($internacoes as $i) {
        $eventos[] = [
            'title' => 'Internação - '.$i['paciente'].' (Quarto '.$i['quarto'].')',
            'start' => $i['data_internacao'],
            'end'   => $i['data_alta'] ?: null,
            'paciente' => $i['paciente'],
            'tipo' => 'internacao',
            'className' => 'internacao'
        ];
    }
}

$eventosJSON = json_encode($eventos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Calendário Interativo</title>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<link rel="stylesheet" href="estilo.css">
<link rel="icon" href="../favicon_round.png" type="image/png">

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Inter', sans-serif; background:#f0f4f8; color:#1f2937; min-height:100vh; }

/* Header */
.header { background:white; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); position:sticky; top:0; z-index:50; }
.nav { padding:16px 0; }
.nav-container { display:flex; justify-content:space-between; align-items:center; }
.logo { display:flex; align-items:center; gap:12px; }
.logo-text { font-size:24px; font-weight:700; color:#1f2937; }
.nav-links { display:none; gap:32px; }
@media(min-width:768px){ .nav-links{ display:flex; } }
.nav-link { color:#374151; text-decoration:none; transition:color 0.3s ease; }
.nav-link:hover { color:#1b8aa6; }
.btn-primary { background:#1b8aa6; color:white; padding:8px 24px; border:none; border-radius:8px; font-weight:500; cursor:pointer; transition:background-color 0.3s ease; }
.btn-primary:hover { background:#166e87; }

/* Título */
.calendar-title { text-align:center; font-size:32px; font-weight:700; color:#1b8aa6; margin:32px 0 16px; }

/* Legenda */
.legend { display:flex; justify-content:center; flex-wrap:wrap; gap:12px; margin-bottom:24px; }
.legend span { padding:6px 12px; border-radius:6px; font-size:14px; font-weight:500; color:white; }
.legend .consulta { background:#1b8aa6; }
.legend .consulta-realizada { background:#0c7183; }
.legend .consulta-cancelada { background:#e53e3e; }
.legend .consulta-nao-compareceu { background:#f59e0b; }
.legend .procedimento { background:#66d9d9; }
.legend .internacao { background:#f6d365; }

/* Calendário */
#calendar { max-width:1000px; margin:0 auto 48px; background:white; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,0.1); padding:20px; }
.fc .fc-daygrid-day { border-radius:8px; transition:all 0.3s ease; }
.fc .fc-daygrid-day:hover { background:rgba(27,138,166,0.05); transform:translateY(-2px); box-shadow:0 6px 20px rgba(27,138,166,0.1); }
.fc .fc-day-today { background:linear-gradient(135deg, #66D9D9 0%, #8BCAD9 100%); color:white; }

/* Eventos via className */
.fc-event { border:none; border-radius:8px; padding:6px 8px; font-size:13px; font-weight:500; color:white; box-shadow:0 4px 15px rgba(27,138,166,0.3); cursor:pointer; transition:transform 0.2s ease, box-shadow 0.2s ease; }
.fc-event:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(27,138,166,0.5); }
.fc-event.consulta { background:#1b8aa6 !important; }
.fc-event.consulta-realizada { background:#0c7183 !important; }
.fc-event.consulta-cancelada { background:#e53e3e !important; }
.fc-event.consulta-nao-compareceu { background:#f59e0b !important; }
.fc-event.procedimento { background:#66d9d9 !important; }
.fc-event.internacao { background:#f6d365 !important; }

/* Event card e overlay */
.event-card { position:fixed; top:50%; left:50%; transform:translate(-50%, -50%) scale(0); width:300px; background:white; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.2); padding:16px; z-index:999; transition:transform 0.3s ease, opacity 0.3s ease; opacity:0; font-family:'Inter',sans-serif; }
.event-card.visible { transform:translate(-50%, -50%) scale(1); opacity:1; }
.event-card .card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
.event-card .card-header h3 { font-size:18px; font-weight:600; color:#1b8aa6; }
.event-card .card-header span { cursor:pointer; font-size:16px; color:#e53e3e; }
.event-card .card-body p { margin:4px 0; font-size:14px; color:#374151; }
#calendar-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); z-index:998; }
#calendar-overlay.visible { display:block; }
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
                <li><a href="../index.php"><i class="fa fa-sign-out-alt icon"></i>Sair</a></li>
            </ul>
        </div>
    </nav>
</header>


<h2 class="calendar-title">📅 Calendário Interativo</h2>

<!-- LEGENDA COLORIDA ACIMA DO CALENDÁRIO -->
<div class="legend">
    <span class="consulta">Consulta Agendada</span>
    <span class="consulta-realizada">Consulta Realizada</span>
    <span class="consulta-cancelada">Consulta Cancelada</span>
    <span class="consulta-nao-compareceu">Não Compareceu</span>
    <span class="procedimento">Procedimento</span>
    <span class="internacao">Internação</span>
</div>

<div id="calendar"></div>

<div id="event-card" class="event-card">
    <div class="card-header">
        <h3 id="event-title"></h3>
        <span id="close-card">✖</span>
    </div>
    <div class="card-body">
        <p id="event-date"></p>
        <p id="event-time"></p>
        <p id="event-patient"></p>
        <p id="event-doctor"></p>
        <p id="event-status"></p>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    let eventos = <?php echo $eventosJSON; ?>;
    let calendarEl = document.getElementById('calendar');
    let eventCard = document.getElementById('event-card');

    let overlay = document.createElement('div');
    overlay.id = 'calendar-overlay';
    document.body.appendChild(overlay);

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        headerToolbar: { left:'prev,next today', center:'title', right:'dayGridMonth,timeGridWeek,timeGridDay' },
        events: eventos,
        eventClick: function(info) {
            let props = info.event.extendedProps;

            document.getElementById('event-title').textContent = info.event.title;
            document.getElementById('event-date').textContent = "🗓️ " + info.event.start.toLocaleDateString('pt-BR');
            document.getElementById('event-time').textContent = props.hora ? "⏰ Hora: " + props.hora : "";
            document.getElementById('event-patient').textContent = props.paciente ? "👤 Paciente: " + props.paciente : "";
            document.getElementById('event-doctor').textContent = props.medico ? "👨‍⚕️ Médico: " + props.medico : "";
            document.getElementById('event-status').textContent = props.status ? "📍 Status: " + props.status : "";

            eventCard.classList.add('visible');
            overlay.classList.add('visible');
        }
    });

    calendar.render();

    document.getElementById('close-card').addEventListener('click', () => {
        eventCard.classList.remove('visible');
        overlay.classList.remove('visible');
    });
    overlay.addEventListener('click', () => {
        eventCard.classList.remove('visible');
        overlay.classList.remove('visible');
    });
});
</script>
</body>
</html>