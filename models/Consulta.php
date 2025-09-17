<?php
include '../../config.php';
include 'Notificacao.php';

class Consulta {

    public static function listarPorMedico($medico_id){
        global $pdo;
        return $pdo->query("SELECT c.*, p.nome AS paciente_nome 
                            FROM consultas c
                            JOIN pacientes p ON c.paciente_id=p.id
                            WHERE c.medico_id=$medico_id")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarPorPaciente($paciente_id){
        global $pdo;
        return $pdo->query("SELECT c.*, u.nome AS medico_nome 
                            FROM consultas c
                            JOIN usuarios u ON c.medico_id=u.id
                            WHERE c.paciente_id=$paciente_id")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cadastrar($paciente_id, $medico_id, $data, $hora){
        global $pdo;

        // Verifica disponibilidade do médico
        $verifica = $pdo->query("SELECT * FROM consultas WHERE medico_id=$medico_id AND data='$data' AND hora='$hora'")->fetch();
        if($verifica){
            return false;
        }

        $pdo->exec("INSERT INTO consultas(paciente_id, medico_id, data, hora) VALUES($paciente_id, $medico_id, '$data', '$hora')");

        // Notificações
        Notificacao::criar('consulta', "Consulta agendada para $data às $hora", $paciente_id);
        Notificacao::criar('consulta', "Consulta agendada com paciente ID $paciente_id para $data às $hora", $medico_id);

        return true;
    }

    // Sugestão de horários alternativos
    public static function sugestaoHorarios($medico_id, $data){
        global $pdo;
        $ocupados = $pdo->query("SELECT hora FROM consultas WHERE medico_id=$medico_id AND data='$data'")->fetchAll(PDO::FETCH_COLUMN);
        $todos_horarios = ['08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00'];
        return array_diff($todos_horarios, $ocupados);
    }
}
?>
