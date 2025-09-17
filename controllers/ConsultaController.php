<?php
include '../models/Consulta.php';
session_start();

if(!isset($_SESSION['perfil'])){
    header('Location: ../index.php');
    exit;
}

if(isset($_POST['acao'])){

    if($_POST['acao'] == 'cadastrar'){
        $sucesso = Consulta::cadastrar($_POST['paciente_id'], $_POST['medico_id'], $_POST['data'], $_POST['hora']);
        if($sucesso){
            echo "Consulta agendada com sucesso!";
        } else {
            // Sugestão de horários alternativos
            $alternativas = Consulta::sugestaoHorarios($_POST['medico_id'], $_POST['data']);
            echo "Horário ocupado! Horários alternativos disponíveis: ".implode(', ',$alternativas);
        }
    }
}
?>
