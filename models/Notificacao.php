<?php
include __DIR__ . '/../config.php';

class Notificacao {

    // Cria a notificação com mensagem já formatada
    public static function criar($tipo, $mensagem, $usuario_id){
        global $pdo;

        // Evitar SQL injection usando prepare
        $stmt = $pdo->prepare("INSERT INTO notificacoes(tipo, mensagem, usuario_id) VALUES(?, ?, ?)");
        $stmt->execute([$tipo, $mensagem, $usuario_id]);
    }

    // Lista notificações de um usuário com nome incluso (caso queira exibir)
    public static function listarPorUsuario($usuario_id){
        global $pdo;
        return $pdo->query("
            SELECT n.*, u.nome AS usuario_nome
            FROM notificacoes n
            LEFT JOIN usuarios u ON n.usuario_id = u.id
            WHERE n.usuario_id = $usuario_id
            ORDER BY n.data_criacao DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Marca uma notificação como lida
    public static function marcarComoLida($id){
        global $pdo;
        $stmt = $pdo->prepare("UPDATE notificacoes SET lida=1 WHERE id=?");
        $stmt->execute([$id]);
    }
}
?>
