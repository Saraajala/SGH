<?php
session_start();
include '../models/Prontuario.php';

// Verifica se é médico
if(!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 'medico'){
    $_SESSION['msg_erro'] = "Acesso negado.";
    header('Location: ../index.php');
    exit;
}

$acao = $_POST['acao'] ?? '';
$paciente_id = $_POST['paciente_id'] ?? null;
$descricao = $_POST['descricao'] ?? '';
$id_registro = $_POST['id'] ?? null;
$medico_id = $_SESSION['id_usuario'] ?? null;

if(empty($medico_id)){
    $_SESSION['msg_erro'] = "Não foi possível identificar o médico. Faça login novamente.";
    header('Location: ../index.php');
    exit;
}

try {
    switch($acao){
        // -------- Inserções --------
        case 'prescricao':
            if(empty($paciente_id) || empty($descricao)) throw new Exception("Descrição ou paciente inválidos.");
            Prontuario::registrarPrescricao($paciente_id, $medico_id, $descricao);
            $_SESSION['msg_sucesso'] = "Prescrição registrada com sucesso!";
            break;

        case 'evolucao':
            if(empty($paciente_id) || empty($descricao)) throw new Exception("Descrição ou paciente inválidos.");
            Prontuario::registrarEvolucao($paciente_id, $medico_id, $descricao);
            $_SESSION['msg_sucesso'] = "Evolução registrada com sucesso!";
            break;

        case 'procedimento':
            if(empty($paciente_id) || empty($descricao)) throw new Exception("Descrição ou paciente inválidos.");
            Prontuario::registrarProcedimento($paciente_id, $medico_id, $descricao);
            $_SESSION['msg_sucesso'] = "Procedimento registrado com sucesso!";
            break;

        case 'exame':
            $exame_id = $_POST['exame_id'] ?? null;
            if(empty($paciente_id) || empty($exame_id)) throw new Exception("Paciente ou exame inválido.");
            Prontuario::registrarExame($paciente_id, $medico_id, $exame_id);
            $_SESSION['msg_sucesso'] = "Exame registrado com sucesso!";
            break;

        // -------- Exclusões --------
        case 'excluir_evolucao':
            if(!$id_registro || !Prontuario::excluirEvolucao($id_registro, $medico_id)){
                throw new Exception("Você não tem permissão para excluir esta evolução.");
            }
            $_SESSION['msg_sucesso'] = "Evolução excluída com sucesso!";
            break;

        case 'excluir_prescricao':
            if(!$id_registro || !Prontuario::excluirPrescricao($id_registro, $medico_id)){
                throw new Exception("Você não tem permissão para excluir esta prescrição.");
            }
            $_SESSION['msg_sucesso'] = "Prescrição excluída com sucesso!";
            break;

        case 'excluir_procedimento':
            if(!$id_registro || !Prontuario::excluirProcedimento($id_registro, $medico_id)){
                throw new Exception("Você não tem permissão para excluir este procedimento.");
            }
            $_SESSION['msg_sucesso'] = "Procedimento excluído com sucesso!";
            break;

        case 'excluir_exame':
            if(!$id_registro || !Prontuario::excluirExame($id_registro, $medico_id)){
                throw new Exception("Você não tem permissão para excluir este exame.");
            }
            $_SESSION['msg_sucesso'] = "Exame excluído com sucesso!";
            break;

        default:
            throw new Exception("Ação inválida.");
    }

} catch(Exception $e){
    $_SESSION['msg_erro'] = $e->getMessage();
}

header('Location: ../views/prontuario/prontuario.php');
exit;
