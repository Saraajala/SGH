<?php
include '../../config.php';
include 'Notificacao.php';

class Consulta {

    // Listar consultas para médicos
    public static function listarPorMedico($medico_id){
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT c.*, u.nome AS paciente_nome
            FROM consultas c
            JOIN usuarios u ON c.paciente_id = u.id
            WHERE c.medico_id = ?
            ORDER BY c.data, c.hora
        ");
        $stmt->execute([$medico_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar consultas para pacientes
    public static function listarPorPaciente($paciente_id){
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT c.*, u.nome AS medico_nome
            FROM consultas c
            JOIN usuarios u ON c.medico_id = u.id
            WHERE c.paciente_id = ?
            ORDER BY c.data, c.hora
        ");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cadastrar consulta
    public static function cadastrar($paciente_id, $medico_id, $data, $hora){
        global $pdo;

        // Verifica disponibilidade do médico
        $stmt = $pdo->prepare("SELECT 1 FROM consultas WHERE medico_id = ? AND data = ? AND hora = ?");
        $stmt->execute([$medico_id, $data, $hora]);
        if($stmt->fetch()){
            return false;
        }

        // Inserir consulta
        $stmt = $pdo->prepare("INSERT INTO consultas (paciente_id, medico_id, data, hora) VALUES (?, ?, ?, ?)");
        $stmt->execute([$paciente_id, $medico_id, $data, $hora]);

        // Buscar nomes para notificações
        $paciente = $pdo->query("SELECT nome FROM usuarios WHERE id = $paciente_id")->fetchColumn();
        $medico = $pdo->query("SELECT nome FROM usuarios WHERE id = $medico_id")->fetchColumn();

        Notificacao::criar('consulta', "Consulta agendada para $data às $hora com médico $medico", $paciente_id);
        Notificacao::criar('consulta', "Consulta agendada para $data às $hora com paciente $paciente", $medico_id);

        return true;
    }

    // Sugestão de horários alternativos
    public static function sugestaoHorarios($medico_id, $data){
        global $pdo;
        $ocupados = $pdo->prepare("SELECT hora FROM consultas WHERE medico_id = ? AND data = ?");
        $ocupados->execute([$medico_id, $data]);
        $ocupados = $ocupados->fetchAll(PDO::FETCH_COLUMN);

        $todos_horarios = ['08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00'];
        return array_diff($todos_horarios, $ocupados);
    }
}
?>
