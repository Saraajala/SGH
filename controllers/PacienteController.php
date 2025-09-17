<?php
include '../models/Paciente.php';
session_start();

// Restrição: apenas médico, enfermeiro ou paciente podem acessar
if(!isset($_SESSION['perfil'])){
    header('Location: ../index.php');
    exit;
}

if(isset($_POST['acao'])){

    if($_POST['acao'] == 'cadastrar'){
        Paciente::cadastrar($_POST['nome'], $_POST['idade'], $_POST['contato'],
                            $_POST['endereco'], $_POST['historico']);
        header('Location: ../views/paciente/listar.php');
    }

    if($_POST['acao'] == 'atualizar'){
        Paciente::atualizar($_POST['id'], $_POST['nome'], $_POST['idade'], $_POST['contato'],
                             $_POST['endereco'], $_POST['historico']);
        header('Location: ../views/paciente/listar.php');
    }

    if($_POST['acao'] == 'deletar'){
        Paciente::deletar($_POST['id']);
        header('Location: ../views/paciente/listar.php');
    }
}
?>
