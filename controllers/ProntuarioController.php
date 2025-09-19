<?php
session_start();
include '../models/Prontuario.php';

// Verifica se o usuário é médico
if(!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 'medico'){
    $_SESSION['msg_erro'] = "Acesso negado.";
    header('Location: ../index.php');
    exit;
}

// Recebe dados do formulário
$acao = $_POST['acao'] ?? '';
$paciente_id = $_POST['paciente_id'] ?? null;
$descricao = $_POST['descricao'] ?? '';
$medico_id = $_SESSION['id_usuario'] ?? null; // pega o ID do médico logado

// Valida paciente_id
if(empty($paciente_id)){
    $_SESSION['msg_erro'] = "Selecione um paciente antes de registrar!";
    header('Location: ../views/prontuario/prontuario.php');
    exit;
}

// Valida medico_id
if(empty($medico_id)){
    $_SESSION['msg_erro'] = "Não foi possível identificar o médico. Faça login novamente.";
    header('Location: ../index.php');
    exit;
}

try {
    switch($acao){
        case 'prescricao':
            if(empty($descricao)){
                throw new Exception("A descrição da prescrição não pode ficar vazia.");
            }
            Prontuario::registrarPrescricao($paciente_id, $medico_id, $descricao);
            $_SESSION['msg_sucesso'] = "Prescrição registrada com sucesso!";
            break;

        case 'evolucao':
            if(empty($descricao)){
                throw new Exception("A descrição da evolução não pode ficar vazia.");
            }
            Prontuario::registrarEvolucao($paciente_id, $medico_id, $descricao);
            $_SESSION['msg_sucesso'] = "Evolução registrada com sucesso!";
            break;

        case 'procedimento':
            if(empty($descricao)){
                throw new Exception("A descrição do procedimento não pode ficar vazia.");
            }
            Prontuario::registrarProcedimento($paciente_id, $medico_id, $descricao);
            $_SESSION['msg_sucesso'] = "Procedimento registrado com sucesso!";
            break;

        case 'exame':
            $exame_id = $_POST['exame_id'] ?? null;
            if(empty($exame_id)){
                throw new Exception("Selecione um tipo de exame.");
            }
            Prontuario::registrarExame($paciente_id, $medico_id, $exame_id);
            $_SESSION['msg_sucesso'] = "Exame registrado com sucesso!";
            break;

        default:
            throw new Exception("Ação inválida.");
    }
} catch(Exception $e){
    $_SESSION['msg_erro'] = $e->getMessage();
}

// Redireciona para a página de prontuário
header('Location: ../views/prontuario/prontuario.php');
exit;
