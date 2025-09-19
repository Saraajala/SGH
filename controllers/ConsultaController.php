<?php
session_start();
include '../models/Consulta.php';

if(!isset($_SESSION['id_usuario'], $_SESSION['perfil'])){
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['id_usuario'];
$perfil = $_SESSION['perfil'];

if(isset($_POST['acao'])){

    if($_POST['acao'] == 'agendar' && $perfil == 'paciente'){
        // Paciente agenda consulta
        $paciente_id = $user_id;
        $medico_id = $_POST['medico_id'];
        $data = $_POST['data'];
        $hora = $_POST['hora'];

        $sucesso = Consulta::cadastrar($paciente_id, $medico_id, $data, $hora);

    } elseif($_POST['acao'] == 'marcar' && $perfil == 'medico'){
        // Médico agenda consulta
        $medico_id = $user_id;
        $paciente_id = $_POST['paciente_id'];
        $data = $_POST['data'];
        $hora = $_POST['hora'];

        $sucesso = Consulta::cadastrar($paciente_id, $medico_id, $data, $hora);

    } else {
        $_SESSION['msg_erro'] = "Ação ou perfil inválidos.";
        header("Location: ../views/dashboard.php");
        exit;
    }

    if($sucesso){
        $_SESSION['msg_sucesso'] = "Consulta agendada com sucesso!";
    } else {
        $alternativas = Consulta::sugestaoHorarios($medico_id, $data);
        $_SESSION['msg_erro'] = "Horário ocupado! Alternativas: ".implode(', ', $alternativas);
    }

    header("Location: ../views/dashboard.php");
    exit;
}
?>
