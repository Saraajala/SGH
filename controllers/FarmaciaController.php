<?php
include '../models/Farmacia.php';
session_start();

if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','recepcao'])){
    header('Location: ../index.php');
    exit;
}

if(isset($_POST['acao'])){

    $medico_id = $_SESSION['id_usuario'];

    if($_POST['acao'] == 'cadastrar'){
        Farmacia::cadastrarMedicamento($_POST['nome'], $_POST['descricao'], $_POST['quantidade']);
        echo "Medicamento cadastrado!";
    }

    if($_POST['acao'] == 'dispensar'){
        $sucesso = Farmacia::dispensar($_POST['medicamento_id'], $_POST['paciente_id'], $medico_id, $_POST['quantidade']);
        echo $sucesso ? "Medicamento dispensado!" : "Estoque insuficiente!";
    }
}
?>
