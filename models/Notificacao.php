<?php
include '../../config.php';

class Notificacao {

    public static function criar($tipo, $mensagem, $usuario_id){
        global $pdo;
        $pdo->exec("INSERT INTO notificacoes(tipo,mensagem,usuario_id) VALUES('$tipo','$mensagem',$usuario_id)");
    }

    public static function listarPorUsuario($usuario_id){
        global $pdo;
        return $pdo->query("SELECT * FROM notificacoes WHERE usuario_id=$usuario_id ORDER BY data_criacao DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function marcarComoLida($id){
        global $pdo;
        $pdo->exec("UPDATE notificacoes SET lida=1 WHERE id=$id");
    }
}
?>
