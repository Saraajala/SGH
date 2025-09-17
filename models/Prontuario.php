<?php
include 'config.php';
include 'Notificacao.php';

class Prontuario {

    // Evolução
    public static function registrarEvolucao($paciente_id, $medico_id, $descricao){
        global $pdo;
        $pdo->exec("INSERT INTO evolucoes(paciente_id, medico_id, descricao) VALUES($paciente_id, $medico_id, '$descricao')");
        Notificacao::criar('consulta', "Nova evolução registrada para paciente ID $paciente_id", $paciente_id);
    }

    public static function listarEvolucoes($paciente_id){
        global $pdo;
        return $pdo->query("SELECT * FROM evolucoes WHERE paciente_id=$paciente_id ORDER BY data DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Prescrição
    public static function registrarPrescricao($paciente_id, $medico_id, $medicamento, $posologia){
        global $pdo;
        $pdo->exec("INSERT INTO prescricoes(paciente_id, medico_id, medicamento, posologia) VALUES($paciente_id, $medico_id, '$medicamento', '$posologia')");
        Notificacao::criar('consulta', "Nova prescrição registrada para paciente ID $paciente_id", $paciente_id);
    }

    public static function listarPrescricoes($paciente_id){
        global $pdo;
        return $pdo->query("SELECT * FROM prescricoes WHERE paciente_id=$paciente_id ORDER BY data DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Procedimentos
    public static function registrarProcedimento($paciente_id, $medico_id, $descricao){
        global $pdo;
        $pdo->exec("INSERT INTO procedimentos(paciente_id, medico_id, descricao) VALUES($paciente_id, $medico_id, '$descricao')");
        Notificacao::criar('consulta', "Novo procedimento registrado para paciente ID $paciente_id", $paciente_id);
    }

    public static function listarProcedimentos($paciente_id){
        global $pdo;
        return $pdo->query("SELECT * FROM procedimentos WHERE paciente_id=$paciente_id ORDER BY data DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Upload de exames
    public static function registrarExame($paciente_id, $medico_id, $arquivo, $descricao){
        global $pdo;
        $pdo->exec("INSERT INTO exames(paciente_id, medico_id, arquivo, descricao) VALUES($paciente_id, $medico_id, '$arquivo', '$descricao')");
        Notificacao::criar('consulta', "Novo exame registrado para paciente ID $paciente_id", $paciente_id);
    }

    public static function listarExames($paciente_id){
        global $pdo;
        return $pdo->query("SELECT * FROM exames WHERE paciente_id=$paciente_id ORDER BY data DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
