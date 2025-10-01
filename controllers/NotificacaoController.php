<?php
session_start();
include __DIR__ . '/../models/Notificacao.php';

// Verifica se usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../index.php');
    exit;
}

// Se o botão "Marcar como lida" for clicado
if (isset($_GET['acao']) && $_GET['acao'] === 'lida' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    Notificacao::marcarComoLida($id);
    header('Location: ../views/notificacao/listar.php');
    exit;
}
?>
