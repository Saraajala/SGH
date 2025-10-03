<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

include '../../config.php';

$id_usuario = $_SESSION['id_usuario'];
$perfil     = $_SESSION['perfil'];
$data_hoje = date('Y-m-d');

/* =======================================
   1) Buscar todos os eventos do usuário
   ======================================= */
$eventos = [];

/* --- PACIENTE --- */
if ($perfil === 'paciente') {
    $sql = "SELECT c.id, c.data, c.hora, u.nome AS medico, c.status
            FROM consultas c
            JOIN usuarios u ON c.medico_id = u.id
            WHERE c.paciente_id = ? 
            AND (c.status = 'agendada' OR (c.status = 'realizada' AND c.data = ?))
            AND (c.data >= ?)
            ORDER BY c.data, c.hora";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $data_hoje, $data_hoje]);
    $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($consultas as $c) {
        // Definir cor baseada no status
        if ($c['status'] == 'realizada'); // Verde escuro para realizada
        if ($c['status'] == 'cancelada'); // Vermelho para cancelada
        if ($c['status'] == 'nao_compareceu'); // Laranja para não compareceu
        
        $eventos[] = [
            'title' => 'Consulta com Dr(a). '.$c['medico'] . ' (' . ucfirst($c['status']) . ')',
            'start' => $c['data'].'T'.$c['hora'],
            'hora' => $c['hora'],
            'medico' => $c['medico'],
            'status' => $c['status']
        ];
    }
}

/* --- MÉDICO --- */
elseif ($perfil === 'medico') {
    // Consultas (apenas futuras e realizadas do dia atual)
    $sql = "SELECT c.id, c.data, c.hora, u.nome AS paciente, c.status
            FROM consultas c
            JOIN usuarios u ON c.paciente_id = u.id
            WHERE c.medico_id = ? 
            AND (c.status = 'agendada' OR (c.status = 'realizada' AND c.data = ?))
            AND (c.data >= ?)
            ORDER BY c.data, c.hora";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $data_hoje, $data_hoje]);
    $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($consultas as $c) {
        if ($c['status'] == 'realizada'); 
        if ($c['status'] == 'cancelada');
        if ($c['status'] == 'nao_compareceu'); 
        
        $eventos[] = [
            'title' => 'Consulta - '.$c['paciente'] . ' (' . ucfirst($c['status']) . ')',
            'start' => $c['data'].'T'.$c['hora'],
            'hora' => $c['hora'],
            'paciente' => $c['paciente'],
            'status' => $c['status']
        ];
    }

    // Procedimentos (apenas futuros)
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
            'paciente' => $p['paciente']
        ];
    }
}

/* --- ENFERMEIRO --- */
elseif ($perfil === 'enfermeiro') {
    // Internações (apenas futuras e atuais)
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
            'paciente' => $i['paciente']
        ];
    }
}

// Transformar em JSON
$eventosJSON = json_encode($eventos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Calendário Interativo</title>

    <!-- FullCalendar CSS/JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
</head>
<body>

<h2>📅 Calendário Interativo</h2>

<div class="info-box">
    <h3>Informações do Calendário</h3>
    <p><strong>⚠️ Consultas realizadas de dias anteriores não são exibidas.</strong></p>
    <p>Apenas consultas futuras e consultas realizadas <strong>hoje</strong> aparecem no calendário.</p>
</div>

<div class="legend">
    <span class="consulta">Consulta Agendada</span>
    <span class="consulta-realizada">Consulta Realizada</span>
    <span class="consulta-cancelada">Consulta Cancelada</span>
    <span class="consulta-nao-compareceu">Não Compareceu</span>
    <span class="procedimento">Procedimento</span>
    <span class="internacao">Internação</span>
</div>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let eventos = <?php echo $eventosJSON; ?>; // Eventos vindos do PHP

    let calendarEl = document.getElementById('calendar');

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: eventos,
        eventClick: function(info) {
            let props = info.event.extendedProps;
            let detalhes = "📌 " + info.event.title +
                           "\n🗓️ " + info.event.start.toLocaleDateString('pt-BR');
            if (props.hora) detalhes += "\n⏰ Hora: " + props.hora;
            if (props.paciente) detalhes += "\n👤 Paciente: " + props.paciente;
            if (props.medico) detalhes += "\n👨‍⚕️ Médico: " + props.medico;
            if (props.status) detalhes += "\n📍 Status: " + props.status;
            alert(detalhes);
        },
        eventContent: function(arg) {
            // Personalizar o conteúdo do evento
            let title = arg.event.title;
            let timeEl = document.createElement('div');
            timeEl.innerHTML = title;
            timeEl.style.fontSize = '11px';
            timeEl.style.padding = '2px';
            
            let arrayOfDomNodes = [timeEl];
            return { domNodes: arrayOfDomNodes };
        }
    });

    calendar.render();
});
</script>

<p >
    <a href="../dashboard.php">
        ← Voltar ao Dashboard
    </a>
</p>

</body>
</html>