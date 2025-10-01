<?php
include __DIR__ . '/../config.php';

class Internacao {

    // Listar pacientes internados
    public static function listarInternacoes() {
        global $pdo;
        $stmt = $pdo->query("
            SELECT i.*, 
                   u_p.nome AS paciente_nome, 
                   u_m.nome AS medico_nome, 
                   q.ala, q.numero AS quarto_numero, q.status AS quarto_status
            FROM internacoes i
            JOIN usuarios u_p ON i.paciente_id = u_p.id
            JOIN usuarios u_m ON i.medico_id = u_m.id
            JOIN quartos q ON i.quarto_id = q.id
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

    // Listar pacientes que não estão internados
    public static function listarPacientesNaoInternados() {
        global $pdo;
        $stmt = $pdo->query("
            SELECT u.* 
            FROM usuarios u
            WHERE u.perfil = 'paciente'
            AND u.id NOT IN (
                SELECT paciente_id 
                FROM internacoes 
                WHERE status = 'internado'
            )
            ORDER BY u.nome
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

        $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM internacoes WHERE quarto_id = ? AND status = 'internado'");
        $stmt->execute([$quarto_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $status = ($resultado['total'] > 0) ? 'ocupado' : 'limpeza';

        $stmt = $pdo->prepare("UPDATE quartos SET status = ? WHERE id = ?");
        $stmt->execute([$status, $quarto_id]);
    }

    // Internar paciente
    public static function internar($paciente_id, $quarto_id, $medico_id) {
        global $pdo;
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT * FROM internacoes WHERE paciente_id = ? AND status = 'internado'");
            $stmt->execute([$paciente_id]);
            if ($stmt->rowCount() > 0) throw new Exception("Paciente já está internado!");

            if (!self::isQuartoDisponivel($quarto_id)) throw new Exception("Quarto não está disponível!");

            $stmt = $pdo->prepare("UPDATE quartos SET status = 'ocupado' WHERE id = ?");
            $stmt->execute([$quarto_id]);

            $stmt = $pdo->prepare("INSERT INTO internacoes (paciente_id, quarto_id, medico_id, data_entrada, status) VALUES (?, ?, ?, NOW(), 'internado')");
            $stmt->execute([$paciente_id, $quarto_id, $medico_id]);

            $pdo->commit();
            return "sucesso: Paciente internado com sucesso!";

        } catch(Exception $e) {
            $pdo->rollBack();
            return "erro: " . $e->getMessage();
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

            $stmt = $pdo->prepare("UPDATE internacoes SET status = 'alta', data_alta = NOW() WHERE id = ?");
            $stmt->execute([$internacao_id]);

            self::atualizarStatusQuarto($internacao['quarto_id']);

            $pdo->commit();
            return "sucesso: Paciente teve alta com sucesso!";

        } catch(Exception $e) {
            $pdo->rollBack();
            return "erro: " . $e->getMessage();
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

            $stmt = $pdo->prepare("UPDATE internacoes SET quarto_id = ? WHERE id = ?");
            $stmt->execute([$novo_quarto_id, $internacao_id]);

            self::atualizarStatusQuarto($internacao['quarto_id']);
            self::atualizarStatusQuarto($novo_quarto_id);

            $pdo->commit();
            return "sucesso: Paciente transferido com sucesso!";

        } catch(Exception $e) {
            $pdo->rollBack();
            return "erro: " . $e->getMessage();
        }
    }
}
?>
