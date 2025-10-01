<?php
// Verifica se a classe já foi definida
if (!class_exists('Consulta')) {
    
    include_once __DIR__ . '/../config.php';
    include_once 'Notificacao.php';

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
                ORDER BY c.data DESC, c.hora DESC
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

        // Listar consultas do médico com informações completas
        public static function listarPorMedicoCompleto($medico_id) {
            global $pdo;
            $stmt = $pdo->prepare("
                SELECT c.*, u.nome AS paciente_nome, u.email AS paciente_email
                FROM consultas c
                JOIN usuarios u ON c.paciente_id = u.id
                WHERE c.medico_id = ?
                ORDER BY c.data DESC, c.hora DESC
            ");
            $stmt->execute([$medico_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Atualizar status da consulta
        public static function atualizarStatus($consulta_id, $status) {
            global $pdo;
            $stmt = $pdo->prepare("UPDATE consultas SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $consulta_id]);
        }

        // Buscar consulta por ID
        public static function buscarPorId($consulta_id) {
            global $pdo;
            $stmt = $pdo->prepare("SELECT * FROM consultas WHERE id = ?");
            $stmt->execute([$consulta_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Listar todos os médicos
        public static function listarMedicos() {
            global $pdo;
            $stmt = $pdo->query("SELECT id, nome FROM usuarios WHERE perfil = 'medico' ORDER BY nome");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Listar todos os pacientes
        public static function listarPacientes() {
            global $pdo;
            $stmt = $pdo->query("SELECT id, nome FROM usuarios WHERE perfil = 'paciente' ORDER BY nome");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Verificar se é o dia da consulta
        public static function ehDiaDaConsulta($consulta_id) {
            global $pdo;
            $stmt = $pdo->prepare("SELECT data FROM consultas WHERE id = ?");
            $stmt->execute([$consulta_id]);
            $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(!$consulta) return false;
            
            $data_consulta = $consulta['data'];
            $data_hoje = date('Y-m-d');
            
            return $data_consulta == $data_hoje;
        }

        // Listar consultas futuras para médico (exclui realizadas de dias anteriores)
        public static function listarConsultasFuturasMedico($medico_id) {
            global $pdo;
            $stmt = $pdo->prepare("
                SELECT c.*, u.nome AS paciente_nome, u.email AS paciente_email
                FROM consultas c
                JOIN usuarios u ON c.paciente_id = u.id
                WHERE c.medico_id = ? 
                AND (c.status = 'agendada' OR (c.status = 'realizada' AND c.data = CURDATE()))
                AND (c.data >= CURDATE())
                ORDER BY c.data ASC, c.hora ASC
            ");
            $stmt->execute([$medico_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Listar consultas futuras para paciente (exclui realizadas de dias anteriores)
        public static function listarConsultasFuturasPaciente($paciente_id) {
            global $pdo;
            $stmt = $pdo->prepare("
                SELECT c.*, u.nome AS medico_nome
                FROM consultas c
                JOIN usuarios u ON c.medico_id = u.id
                WHERE c.paciente_id = ? 
                AND (c.status = 'agendada' OR (c.status = 'realizada' AND c.data = CURDATE()))
                AND (c.data >= CURDATE())
                ORDER BY c.data ASC, c.hora ASC
            ");
            $stmt->execute([$paciente_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Buscar consultas para calendário (exclui realizadas de dias anteriores)
        public static function listarParaCalendario($usuario_id, $perfil) {
            global $pdo;
            
            if($perfil == 'medico') {
                $campo = 'medico_id';
                $join = "JOIN usuarios u ON c.paciente_id = u.id";
                $select_extra = ", CONCAT('Consulta com ', u.nome) AS title";
            } else {
                $campo = 'paciente_id';
                $join = "JOIN usuarios u ON c.medico_id = u.id";
                $select_extra = ", CONCAT('Consulta com Dr. ', u.nome) AS title";
            }
            
            $stmt = $pdo->prepare("
                SELECT c.id, c.data, c.hora, c.status $select_extra
                FROM consultas c
                $join
                WHERE c.$campo = ? 
                AND (c.status = 'agendada' OR (c.status = 'realizada' AND c.data = CURDATE()))
                AND (c.data >= CURDATE())
                ORDER BY c.data, c.hora
            ");
            $stmt->execute([$usuario_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>