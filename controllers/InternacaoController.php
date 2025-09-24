<?php
session_start();
require_once 'app/models/Internacao.php';

// Apenas médico ou enfermeiro podem acessar
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])){
    header('Location: ../index.php');
    exit;
}

$model = new Internacao();

// Ação recebida via POST
$acao = $_POST['acao'] ?? '';

if($acao == 'internar'){
    $res = $model->internar($_POST['paciente_id'], $_POST['quarto_id'], $_POST['medico_id']);
    $_SESSION['mensagem'] = $res['erro'] ?? $res['success'];
} elseif($acao == 'dar_alta') {
    $res = $model->darAlta($_POST['internacao_id']);
    $_SESSION['mensagem'] = $res['erro'] ?? $res['success'];
} elseif($acao == 'transferir') {
    $res = $model->transferir($_POST['internacao_id'], $_POST['novo_quarto_id']);
    $_SESSION['mensagem'] = $res['erro'] ?? $res['success'];
}

header('Location: ../views/internacoes/index.php');
exit;
?>
