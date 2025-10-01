<?php
session_start();
include '../models/Prontuario.php';
include '../models/Notificacao.php';

// Permissões: médico pode registrar e excluir, enfermeiro apenas visualizar
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])){
    $_SESSION['msg_erro'] = "Acesso negado.";
    header('Location: ../views/dashboard.php');
    exit;
}

$perfil = $_SESSION['perfil'];
$acao = $_POST['acao'] ?? '';
$paciente_id = $_POST['paciente_id'] ?? null;
$descricao = $_POST['descricao'] ?? '';
$id_registro = $_POST['id'] ?? null;
$medico_id = $_SESSION['id_usuario'] ?? null;

if(empty($medico_id) && $perfil == 'medico'){
    $_SESSION['msg_erro'] = "Não foi possível identificar o médico. Faça login novamente.";
    header('Location: ../views/dashboard.php');
    exit;
}

try {
    // Apenas médicos podem registrar ou excluir
    if($perfil == 'medico'){
        switch($acao){
            // -------- Inserções --------
            case 'prescricao':
                if(empty($paciente_id) || empty($descricao)) throw new Exception("Descrição ou paciente inválidos.");
                Prontuario::registrarPrescricao($paciente_id, $medico_id, $descricao);
                
                // Notificar paciente
                $mensagem = "💊 Nova prescrição médica registrada no seu prontuário";
                Notificacao::criar('prontuario', $mensagem, $paciente_id);
                
                $_SESSION['msg_sucesso'] = "Prescrição registrada com sucesso!";
                break;

            case 'evolucao':
                if(empty($paciente_id) || empty($descricao)) throw new Exception("Descrição ou paciente inválidos.");
                Prontuario::registrarEvolucao($paciente_id, $medico_id, $descricao);
                
                // Notificar paciente
                $mensagem = "📝 Nova evolução registrada no seu prontuário";
                Notificacao::criar('prontuario', $mensagem, $paciente_id);
                
                $_SESSION['msg_sucesso'] = "Evolução registrada com sucesso!";
                break;

            case 'procedimento':
                if(empty($paciente_id) || empty($descricao)) throw new Exception("Descrição ou paciente inválidos.");
                Prontuario::registrarProcedimento($paciente_id, $medico_id, $descricao);
                
                // Notificar paciente
                $mensagem = "🔬 Novo procedimento registrado no seu prontuário";
                Notificacao::criar('prontuario', $mensagem, $paciente_id);
                
                $_SESSION['msg_sucesso'] = "Procedimento registrado com sucesso!";
                break;

            case 'exame':
                $exame_id = $_POST['exame_id'] ?? null;
                if(empty($paciente_id) || empty($exame_id)) throw new Exception("Paciente ou exame inválido.");
                Prontuario::registrarExame($paciente_id, $medico_id, $exame_id);
                
                // Notificar paciente
                $mensagem = "🔍 Novo exame solicitado no seu prontuário";
                Notificacao::criar('prontuario', $mensagem, $paciente_id);
                
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
    } else {
        // enfermeiro não pode registrar ou excluir
        $_SESSION['msg_erro'] = "Você não tem permissão para modificar o prontuário.";
    }

} catch(Exception $e){
    $_SESSION['msg_erro'] = $e->getMessage();
}

// Redireciona para a view do prontuário
header('Location: ../views/prontuario/prontuario.php');
exit;
?>