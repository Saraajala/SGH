<?php
include 'config.php';
include 'Notificacao.php';

class Internacao {

    public static function listar(){
        global $pdo;
        return $pdo->query("
            SELECT i.*, p.nome AS paciente_nome, q.ala, q.numero AS quarto_numero, q.status AS quarto_status
            FROM internacoes i
            JOIN pacientes p ON i.paciente_id=p.id
            JOIN quartos q ON i.quarto_id=q.id
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function quartosDisponiveis(){
        global $pdo;
        return $pdo->query("SELECT * FROM quartos WHERE status='Disponível'")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function internar($paciente_id, $quarto_id, $usuario_id){
        global $pdo;
        $pdo->exec("UPDATE quartos SET status='Ocupado' WHERE id=$quarto_id");
        $pdo->exec("INSERT INTO internacoes(paciente_id, quarto_id, data_entrada, status) VALUES($paciente_id, $quarto_id, NOW(), 'Internado')");

        // Notificações
        Notificacao::criar('internacao', "Você foi internado no quarto $quarto_id", $paciente_id);
        Notificacao::criar('internacao', "Paciente ID $paciente_id internado no quarto $quarto_id", $usuario_id);
    }

    public static function altaOuTransferencia($internacao_id, $novo_quarto_id = null, $usuario_id){
        global $pdo;

        $paciente_id = $pdo->query("SELECT paciente_id, quarto_id FROM internacoes WHERE id=$internacao_id")->fetch(PDO::FETCH_ASSOC);
        $quarto_id = $paciente_id['quarto_id'];
        $paciente_id = $paciente_id['paciente_id'];

        // Atualiza internação
        $status = $novo_quarto_id ? 'Transferido' : 'Alta';
        $pdo->exec("UPDATE internacoes SET status='$status', data_saida=NOW() WHERE id=$internacao_id");

        // Coloca quarto antigo em limpeza
        $pdo->exec("UPDATE quartos SET status='Em Limpeza' WHERE id=$quarto_id");

        // Simula limpeza automática
        sleep(10);
        $pdo->exec("UPDATE quartos SET status='Disponível' WHERE id=$quarto_id");

        // Se for transferência, interna novamente
        if($novo_quarto_id){
            self::internar($paciente_id, $novo_quarto_id, $usuario_id);
        }

        // Notificações
        Notificacao::criar('alta', "Paciente ID $paciente_id teve alta ou foi transferido", $usuario_id);
    }
}
?>
