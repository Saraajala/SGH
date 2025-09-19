<?php
include __DIR__ . '/../config.php';
include 'Notificacao.php';

class Prontuario {

    // Evolução
    public static function registrarEvolucao($paciente_id, $medico_id, $descricao){
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO evolucoes (paciente_id, medico_id, descricao, data) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$paciente_id, $medico_id, $descricao]);
    }

    public static function listarEvolucoes($paciente_id){
        global $pdo;
        $stmt = $pdo->prepare("SELECT e.*, u.nome as medico_nome 
                               FROM evolucoes e 
                               LEFT JOIN usuarios u ON e.medico_id = u.id 
                               WHERE e.paciente_id = ? 
                               ORDER BY e.data DESC");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Prescrição
    public static function registrarPrescricao($paciente_id, $medico_id, $descricao){
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO prescricoes (paciente_id, medico_id, descricao, data) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$paciente_id, $medico_id, $descricao]);
    }

    public static function listarPrescricoes($paciente_id){
        global $pdo;
        $stmt = $pdo->prepare("SELECT p.*, u.nome as medico_nome 
                               FROM prescricoes p 
                               LEFT JOIN usuarios u ON p.medico_id = u.id 
                               WHERE p.paciente_id = ? 
                               ORDER BY p.data DESC");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Procedimentos
    public static function registrarProcedimento($paciente_id, $descricao){
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO procedimentos (paciente_id, descricao, data) VALUES (?, ?, NOW())");
        return $stmt->execute([$paciente_id, $descricao]);
    }

    public static function listarProcedimentos($paciente_id){
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM procedimentos WHERE paciente_id = ? ORDER BY data DESC");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Exames
    public static function registrarExame($paciente_id, $medico_id, $exame_id){
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO exames (paciente_id, medico_id, exame_id, data) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$paciente_id, $medico_id, $exame_id]);
    }

    public static function listarExames($paciente_id){
        global $pdo;
        $stmt = $pdo->prepare("SELECT e.*, ec.nome as exame_nome, u.nome as medico_nome 
                               FROM exames e 
                               JOIN exames_cadastrados ec ON e.exame_id = ec.id
                               LEFT JOIN usuarios u ON e.medico_id = u.id
                               WHERE e.paciente_id = ? 
                               ORDER BY e.data DESC");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
