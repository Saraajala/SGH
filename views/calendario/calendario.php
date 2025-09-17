<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

include '../../config.php';

$id_usuario = $_SESSION['id_usuario'];
$perfil = $_SESSION['perfil'];

echo "<h2>Calendário Simplificado</h2>";

/* === Paciente: consultas próprias === */
if ($perfil == 'paciente') {
    $sql = "SELECT c.data, c.hora, u.nome AS medico, c.status
            FROM consultas c
            JOIN usuarios u ON c.medico_id = u.id
            WHERE c.paciente_id = ?
            ORDER BY c.data, c.hora";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Minhas Consultas</h3>";
    if ($eventos) {
        echo "<table border='1' cellpadding='5'>
                <tr><th>Data</th><th>Hora</th><th>Médico</th><th>Status</th></tr>";
        foreach($eventos as $e){
            echo "<tr>
                    <td>".date("d/m/Y", strtotime($e['data']))."</td>
                    <td>{$e['hora']}</td>
                    <td>{$e['medico']}</td>
                    <td>{$e['status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Você não tem consultas agendadas.</p>";
    }
}

/* === Médico: consultas + procedimentos === */
elseif ($perfil == 'medico') {
    // Consultas
    $sql = "SELECT c.data, c.hora, p.nome AS paciente, c.status
            FROM consultas c
            JOIN pacientes p ON c.paciente_id = p.id
            WHERE c.medico_id = ?
            ORDER BY c.data, c.hora";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);
    $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Minhas Consultas</h3>";
    if ($consultas) {
        echo "<table border='1' cellpadding='5'>
                <tr><th>Data</th><th>Hora</th><th>Paciente</th><th>Status</th></tr>";
        foreach($consultas as $c){
            echo "<tr>
                    <td>".date("d/m/Y", strtotime($c['data']))."</td>
                    <td>{$c['hora']}</td>
                    <td>{$c['paciente']}</td>
                    <td>{$c['status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhuma consulta agendada.</p>";
    }

    // Procedimentos
    $sql = "SELECT pr.data, pr.descricao, p.nome AS paciente
            FROM procedimentos pr
            JOIN pacientes p ON pr.paciente_id = p.id
            WHERE pr.medico_id = ?
            ORDER BY pr.data";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);
    $procedimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Meus Procedimentos</h3>";
    if ($procedimentos) {
        echo "<table border='1' cellpadding='5'>
                <tr><th>Data</th><th>Paciente</th><th>Descrição</th></tr>";
        foreach($procedimentos as $pr){
            echo "<tr>
                    <td>".date("d/m/Y", strtotime($pr['data']))."</td>
                    <td>{$pr['paciente']}</td>
                    <td>{$pr['descricao']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum procedimento registrado.</p>";
    }
}

/* === Enfermeiro: internações === */
elseif ($perfil == 'enfermeiro') {
    $sql = "SELECT i.data_internacao, i.data_alta, p.nome AS paciente, q.numero AS quarto
            FROM internacoes i
            JOIN pacientes p ON i.paciente_id = p.id
            JOIN quartos q ON i.quarto_id = q.id
            ORDER BY i.data_internacao DESC";
    $stmt = $pdo->query($sql);
    $internacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Internações e Altas</h3>";
    if ($internacoes) {
        echo "<table border='1' cellpadding='5'>
                <tr><th>Data Internação</th><th>Data Alta</th><th>Paciente</th><th>Quarto</th></tr>";
        foreach($internacoes as $i){
            echo "<tr>
                    <td>".date("d/m/Y", strtotime($i['data_internacao']))."</td>
                    <td>".($i['data_alta'] ? date("d/m/Y", strtotime($i['data_alta'])) : "Em aberto")."</td>
                    <td>{$i['paciente']}</td>
                    <td>{$i['quarto']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhuma internação encontrada.</p>";
    }
}

/* === Administrador: tudo === */
elseif ($perfil == 'adm') {
    echo "<p>Como administrador, você pode acessar os relatórios completos em <a href='relatorios/relatorios.php'>Relatórios</a>.</p>";
}
?>

<p><a href="../dashboard.php">Voltar ao Dashboard</a></p>
