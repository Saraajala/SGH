<?php
include '../models/Prontuario.php';
session_start();

if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico'])){
    header('Location: ../index.php');
    exit;
}

if(isset($_POST['acao'])){

    $paciente_id = $_POST['paciente_id'];
    $medico_id = $_SESSION['id_usuario'];

    if($_POST['acao'] == 'evolucao'){
        Prontuario::registrarEvolucao($paciente_id, $medico_id, $_POST['descricao']);
        echo "Evolução registrada!";
    }

    if($_POST['acao'] == 'prescricao'){
        Prontuario::registrarPrescricao($paciente_id, $medico_id, $_POST['medicamento'], $_POST['posologia']);
        echo "Prescrição registrada!";
    }

    if($_POST['acao'] == 'procedimento'){
        Prontuario::registrarProcedimento($paciente_id, $medico_id, $_POST['descricao']);
        echo "Procedimento registrado!";
    }

    if($_POST['acao'] == 'exame' && isset($_FILES['arquivo'])){
        $nome_arquivo = 'uploads/'.basename($_FILES['arquivo']['name']);
        move_uploaded_file($_FILES['arquivo']['tmp_name'], '../../'.$nome_arquivo);
        Prontuario::registrarExame($paciente_id, $medico_id, $nome_arquivo, $_POST['descricao']);
        echo "Exame registrado!";
    }
}
?>
