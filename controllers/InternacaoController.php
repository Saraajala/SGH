<?php
include '../models/Internacao.php';
session_start();

// Apenas médico ou enfermeiro
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])){
    header('Location: ../index.php');
    exit;
}

if(isset($_POST['acao'])){

    if($_POST['acao'] == 'internar'){
        Internacao::internar($_POST['paciente_id'], $_POST['quarto_id'], $_SESSION['id_usuario']);
        echo "Paciente internado com sucesso!";
    }

    if($_POST['acao'] == 'alta_transferencia'){
        $novo_quarto = !empty($_POST['novo_quarto_id']) ? $_POST['novo_quarto_id'] : null;
        Internacao::altaOuTransferencia($_POST['internacao_id'], $novo_quarto, $_SESSION['id_usuario']);
        echo "Operação realizada com sucesso!";
    }
}
?>
