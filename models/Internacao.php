<?php
include __DIR__ . '/../config.php';

class Internacao {

    // Listar pacientes internados
    public static function listarInternacoes() {
        global $pdo;
        $stmt = $pdo->query("
            SELECT i.*, p.nome AS paciente_nome, q.ala, q.numero AS quarto_numero, 
                   q.status AS quarto_status, u.nome AS medico_nome
            FROM internacoes i
            JOIN pacientes p ON i.paciente_id = p.id
            JOIN quartos q ON i.quarto_id = q.id
            JOIN usuarios u ON i.medico_id = u.id
            WHERE i.status = 'internado'
            ORDER BY i.data_entrada DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar todos os quartos
    public static function listarQuartos() {
        global $pdo;
        $stmt = $pdo->query("
            SELECT *, 
                   CASE 
                     WHEN status IS NULL OR status = '' THEN 'disponivel'
                     ELSE status 
                   END as status
            FROM quartos 
            ORDER BY ala, numero
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar quartos disponíveis
    public static function listarQuartosDisponiveis() {
        global $pdo;
        $stmt = $pdo->query("
            SELECT * 
            FROM quartos 
            WHERE status = 'disponivel' OR status IS NULL OR status = ''
            ORDER BY ala, numero
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar pacientes não internados
    public static function listarPacientesNaoInternados() {
        global $pdo;
        $stmt = $pdo->query("
            SELECT p.* 
            FROM pacientes p 
            WHERE p.id NOT IN (
                SELECT paciente_id 
                FROM internacoes 
                WHERE status = 'internado'
            )
            ORDER BY p.nome
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verifica se quarto está disponível
    private static function isQuartoDisponivel($quarto_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT status FROM quartos WHERE id = ?");
        $stmt->execute([$quarto_id]);
        $quarto = $stmt->fetch(PDO::FETCH_ASSOC);
        return $quarto && in_array($quarto['status'], ['disponivel', '', NULL]);
    }

    // Atualiza status do quarto automaticamente
    private static function atualizarStatusQuarto($quarto_id) {
        global $pdo;

        // Verifica se há paciente internado no quarto
        $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM internacoes WHERE quarto_id = ? AND status = 'internado'");
        $stmt->execute([$quarto_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado['total'] > 0) {
            $status = 'ocupado';
        } else {
            $status = 'limpeza';
        }

        $stmt = $pdo->prepare("UPDATE quartos SET status = ? WHERE id = ?");
        $stmt->execute([$status, $quarto_id]);
    }

    // Internar paciente
    public static function internar($paciente_id, $quarto_id, $medico_id) {
        global $pdo;
        try {
            $pdo->beginTransaction();

            // Verifica paciente já internado
            $stmt = $pdo->prepare("SELECT * FROM internacoes WHERE paciente_id = ? AND status = 'internado'");
            $stmt->execute([$paciente_id]);
            if ($stmt->rowCount() > 0) throw new Exception("Paciente já está internado!");

            // Verifica quarto disponível
            if (!self::isQuartoDisponivel($quarto_id)) throw new Exception("Quarto não está disponível!");

            // Atualiza quarto para ocupado
            $stmt = $pdo->prepare("UPDATE quartos SET status = 'ocupado' WHERE id = ?");
            $stmt->execute([$quarto_id]);

            // Inserir internação
            $stmt = $pdo->prepare("INSERT INTO internacoes (paciente_id, quarto_id, medico_id, data_entrada, status) VALUES (?, ?, ?, NOW(), 'internado')");
            $stmt->execute([$paciente_id, $quarto_id, $medico_id]);

            $pdo->commit();
            return "Paciente internado com sucesso!";

        } catch(Exception $e) {
            $pdo->rollBack();
            return "Erro: " . $e->getMessage();
        }
    }

    // Dar alta paciente
    public static function darAlta($internacao_id) {
        global $pdo;
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT * FROM internacoes WHERE id = ?");
            $stmt->execute([$internacao_id]);
            $internacao = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$internacao) throw new Exception("Internação não encontrada.");
            if ($internacao['status'] == 'alta') throw new Exception("Paciente já teve alta!");

            // Atualiza internação
            $stmt = $pdo->prepare("UPDATE internacoes SET status = 'alta', data_alta = NOW() WHERE id = ?");
            $stmt->execute([$internacao_id]);

            // Atualiza status do quarto automaticamente
            self::atualizarStatusQuarto($internacao['quarto_id']);

            $pdo->commit();
            return "Paciente teve alta com sucesso!";

        } catch(Exception $e) {
            $pdo->rollBack();
            return "Erro: " . $e->getMessage();
        }
    }

    // Transferir paciente
    public static function transferir($internacao_id, $novo_quarto_id) {
        global $pdo;
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT * FROM internacoes WHERE id = ? AND status = 'internado'");
            $stmt->execute([$internacao_id]);
            $internacao = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$internacao) throw new Exception("Internação não encontrada ou paciente já teve alta.");

            if (!self::isQuartoDisponivel($novo_quarto_id)) throw new Exception("Novo quarto não está disponível!");

            // Atualiza internação
            $stmt = $pdo->prepare("UPDATE internacoes SET quarto_id = ? WHERE id = ?");
            $stmt->execute([$novo_quarto_id, $internacao_id]);

            // Atualiza status dos quartos antigo e novo
            self::atualizarStatusQuarto($internacao['quarto_id']);
            self::atualizarStatusQuarto($novo_quarto_id);

            $pdo->commit();
            return "Paciente transferido com sucesso!";

        } catch(Exception $e) {
            $pdo->rollBack();
            return "Erro: " . $e->getMessage();
        }
    }

    // Corrigir inconsistências
    public static function corrigirInconsistencias() {
        global $pdo;
        $pdo->exec("UPDATE quartos SET status = 'disponivel' WHERE status IS NULL OR status = ''");
        $pdo->exec("UPDATE internacoes SET data_alta = NOW() WHERE status = 'alta' AND data_alta IS NULL");
        return "Inconsistências corrigidas!";
    }
}
?>
