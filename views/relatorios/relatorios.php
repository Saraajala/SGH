<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil'] != 'adm') {
    header("Location: ../login.php");
    exit;
}

include '../../config.php';

echo "<h2>Relatórios do Sistema</h2>";

/* 1 - Ocupação de leitos por ala */
$sql = "SELECT ala, 
               COUNT(*) AS total, 
               SUM(CASE WHEN status='Ocupado' THEN 1 ELSE 0 END) AS ocupados,
               SUM(CASE WHEN status='Disponível' THEN 1 ELSE 0 END) AS disponiveis,
               SUM(CASE WHEN status='Em Limpeza' THEN 1 ELSE 0 END) AS limpeza
        FROM quartos
        GROUP BY ala";
$ocupacao = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Ocupação de Leitos por Ala</h3>";
if ($ocupacao) {
    echo "<table border='1' cellpadding='5'>
            <tr><th>Ala</th><th>Total</th><th>Ocupados</th><th>Disponíveis</th><th>Em Limpeza</th></tr>";
    foreach($ocupacao as $o){
        echo "<tr>
                <td>{$o['ala']}</td>
                <td>{$o['total']}</td>
                <td>{$o['ocupados']}</td>
                <td>{$o['disponiveis']}</td>
                <td>{$o['limpeza']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhum dado encontrado.</p>";
}

/* 2 - Tempo médio de internação */
$sql = "SELECT AVG(DATEDIFF(data_alta, data_internacao)) AS media_dias
        FROM internacoes
        WHERE data_alta IS NOT NULL";
$tempo = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

echo "<h3>Tempo Médio de Internação</h3>";
if ($tempo && $tempo['media_dias']) {
    echo "<p>Média: ".round($tempo['media_dias'], 1)." dias</p>";
} else {
    echo "<p>Ainda não há internações finalizadas.</p>";
}

/* 3 - Consultas por especialidade/médico */
$sql = "SELECT u.nome AS medico, u.especialidade, COUNT(c.id) AS total
        FROM consultas c
        JOIN usuarios u ON c.medico_id = u.id
        GROUP BY u.id, u.especialidade";
$consultas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Consultas por Médico e Especialidade</h3>";
if ($consultas) {
    echo "<table border='1' cellpadding='5'>
            <tr><th>Médico</th><th>Especialidade</th><th>Total de Consultas</th></tr>";
    foreach($consultas as $c){
        echo "<tr>
                <td>{$c['medico']}</td>
                <td>{$c['especialidade']}</td>
                <td>{$c['total']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhuma consulta registrada.</p>";
}

/* 4 - Procedimentos realizados */
$sql = "SELECT p.nome AS paciente, u.nome AS medico, pr.descricao, pr.data
        FROM procedimentos pr
        JOIN pacientes p ON pr.paciente_id = p.id
        JOIN usuarios u ON pr.medico_id = u.id
        ORDER BY pr.data DESC";
$procedimentos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Procedimentos Realizados</h3>";
if ($procedimentos) {
    echo "<table border='1' cellpadding='5'>
            <tr><th>Data</th><th>Paciente</th><th>Médico</th><th>Procedimento</th></tr>";
    foreach($procedimentos as $pr){
        echo "<tr>
                <td>".date("d/m/Y", strtotime($pr['data']))."</td>
                <td>{$pr['paciente']}</td>
                <td>{$pr['medico']}</td>
                <td>{$pr['descricao']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhum procedimento registrado.</p>";
}
?>

<p><a href="../dashboard.php">Voltar ao Dashboard</a></p>
