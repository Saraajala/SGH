<?php
// Verifica se a classe já foi definida
if (!class_exists('Notificacao')) {
    
    include_once __DIR__ . '/../config.php';

    class Notificacao {

        // Criar notificação
        public static function criar($tipo, $mensagem, $usuario_id){
            global $pdo;
            $stmt = $pdo->prepare("INSERT INTO notificacoes (tipo, mensagem, usuario_id) VALUES (?, ?, ?)");
            return $stmt->execute([$tipo, $mensagem, $usuario_id]);
        }

        // Listar notificações
        public static function listarPorUsuario($usuario_id){
            global $pdo;
            $stmt = $pdo->prepare("
                SELECT n.*, u.nome AS usuario_nome
                FROM notificacoes n
                LEFT JOIN usuarios u ON n.usuario_id = u.id
                WHERE n.usuario_id = ?
                ORDER BY n.data DESC
            ");
            $stmt->execute([$usuario_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Marcar como lida
        public static function marcarComoLida($id){
            global $pdo;
            $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE id = ?");
            $stmt->execute([$id]);
        }

        // Contador de não lidas
        public static function contarNaoLidas($usuario_id){
            global $pdo;
            $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM notificacoes WHERE usuario_id = ? AND lida = 0");
            $stmt->execute([$usuario_id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['total'];
        }
    }
}
?>