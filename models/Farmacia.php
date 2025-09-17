<?php
include 'config.php';
include 'Notificacao.php';

class Farmacia {

    public static function listarMedicamentos(){
        global $pdo;
        return $pdo->query("SELECT * FROM medicamentos")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cadastrarMedicamento($nome, $descricao, $quantidade){
        global $pdo;
        $pdo->exec("INSERT INTO medicamentos(nome, descricao, quantidade) VALUES('$nome','$descricao',$quantidade)");
    }

    public static function dispensar($medicamento_id, $paciente_id, $medico_id, $quantidade){
        global $pdo;

        // Verifica estoque
        $estoque = $pdo->query("SELECT quantidade FROM medicamentos WHERE id=$medicamento_id")->fetchColumn();
        if($estoque < $quantidade){
            return false;
        }

        // Atualiza estoque e registra dispensação
        $pdo->exec("UPDATE medicamentos SET quantidade=quantidade-$quantidade WHERE id=$medicamento_id");
        $pdo->exec("INSERT INTO dispensacoes(medicamento_id, paciente_id, medico_id, quantidade) VALUES($medicamento_id,$paciente_id,$medico_id,$quantidade)");

        // Notificação para paciente
        Notificacao::criar('consulta', "Medicamento dispensado: ID $medicamento_id, quantidade: $quantidade", $paciente_id);

        return true;
    }

    public static function listarDispensacoes(){
        global $pdo;
        return $pdo->query("SELECT d.*, m.nome AS medicamento_nome, p.nome AS paciente_nome, u.nome AS medico_nome
                           FROM dispensacoes d
                           JOIN medicamentos m ON d.medicamento_id=m.id
                           JOIN pacientes p ON d.paciente_id=p.id
                           JOIN usuarios u ON d.medico_id=u.id
                           ORDER BY d.data DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
