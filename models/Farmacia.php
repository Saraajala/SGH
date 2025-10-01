<?php
// Verifica se a classe já foi definida
if (!class_exists('Farmacia')) {
    
    include_once __DIR__ . '/../config.php';
    include_once 'Notificacao.php';

    class Farmacia {

        // Listar todos os medicamentos
        public static function listarMedicamentos(){
            global $pdo;
            $stmt = $pdo->query("SELECT * FROM medicamentos ORDER BY nome ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Cadastrar novo medicamento
        public static function cadastrarMedicamento($nome, $descricao, $quantidade, $usuario_id){
            global $pdo;
            $stmt = $pdo->prepare("INSERT INTO medicamentos (nome, descricao, quantidade, cadastrado_por, data_cadastro) VALUES (?, ?, ?, ?, NOW())");
            return $stmt->execute([$nome, $descricao, $quantidade, $usuario_id]);
        }

        // Dispensar medicamento - CORRIGIDO
        public static function dispensar($medicamento_id, $paciente_id, $quantidade, $usuario_id){
            global $pdo;

            // Primeiro busca o medicamento para pegar nome e descrição
            $stmt = $pdo->prepare("SELECT * FROM medicamentos WHERE id = ?");
            $stmt->execute([$medicamento_id]);
            $medicamento = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$medicamento){
                throw new Exception("Medicamento não encontrado.");
            }

            // Verifica estoque
            if($medicamento['quantidade'] < $quantidade){
                return false; // Estoque insuficiente
            }

            // Atualiza estoque
            $stmt = $pdo->prepare("UPDATE medicamentos SET quantidade = quantidade - ? WHERE id = ?");
            $stmt->execute([$quantidade, $medicamento_id]);

            // CORREÇÃO: Registra dispensação com as colunas CORRETAS da tabela dispensacoes
            $stmt = $pdo->prepare("INSERT INTO dispensacoes (medicamento_id, paciente_id, cadastrado_por, quantidade, nome, descricao, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $medicamento_id, 
                $paciente_id, 
                $usuario_id, 
                $quantidade, 
                $medicamento['nome'], 
                $medicamento['descricao']
            ]);

            // Notificação para paciente
            Notificacao::criar('farmacia', "Medicamento dispensado: {$medicamento['nome']}, quantidade: $quantidade", $paciente_id);

            return true;
        }

        // Listar todas as dispensações - CORRIGIDO
        public static function listarDispensacoes(){
            global $pdo;
            $stmt = $pdo->query("
                SELECT d.*, 
                       m.nome AS medicamento_nome, 
                       p.nome AS paciente_nome, 
                       u.nome AS usuario_nome
                FROM dispensacoes d
                JOIN medicamentos m ON d.medicamento_id = m.id
                JOIN usuarios p ON d.paciente_id = p.id  -- CORREÇÃO: pacientes não existe, usa usuarios
                JOIN usuarios u ON d.cadastrado_por = u.id  -- CORREÇÃO: medico_id não existe, usa cadastrado_por
                ORDER BY d.data_cadastro DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Método auxiliar para buscar medicamento
        public static function buscarMedicamento($id){
            global $pdo;
            $stmt = $pdo->prepare("SELECT * FROM medicamentos WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}
?>