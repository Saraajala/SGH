<?php
session_start();
include '../models/Consulta.php';
include '../models/Notificacao.php';

if(!isset($_SESSION['id_usuario'], $_SESSION['perfil'])){
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['id_usuario'];
$perfil = $_SESSION['perfil'];
$acao = $_POST['acao'] ?? '';

try {
    switch($acao) {
        case 'agendar':
            if($perfil == 'paciente'){
                $paciente_id = $user_id;
                $medico_id = $_POST['medico_id'];
                $data = $_POST['data'];
                $hora = $_POST['hora'];

                $sucesso = Consulta::cadastrar($paciente_id, $medico_id, $data, $hora);

                if($sucesso){
                    $_SESSION['msg_sucesso'] = "Consulta agendada com sucesso!";
                } else {
                    $alternativas = Consulta::sugestaoHorarios($medico_id, $data);
                    $_SESSION['msg_erro'] = "Horário ocupado! Alternativas: ".implode(', ', $alternativas);
                }
            }
            break;
            
        case 'marcar':
            if($perfil == 'medico'){
                $medico_id = $user_id;
                $paciente_id = $_POST['paciente_id'];
                $data = $_POST['data'];
                $hora = $_POST['hora'];

                $sucesso = Consulta::cadastrar($paciente_id, $medico_id, $data, $hora);

                if($sucesso){
                    $_SESSION['msg_sucesso'] = "Consulta agendada com sucesso!";
                } else {
                    $alternativas = Consulta::sugestaoHorarios($medico_id, $data);
                    $_SESSION['msg_erro'] = "Horário ocupado! Alternativas: ".implode(', ', $alternativas);
                }
            }
            break;
            
        case 'marcar_realizada':
            if($perfil == 'medico'){
                $consulta_id = (int)$_POST['consulta_id'];
                
                // Verificar se é o dia da consulta
                if(!Consulta::ehDiaDaConsulta($consulta_id)) {
                    $_SESSION['msg_erro'] = "Só é possível marcar como realizada no dia da consulta!";
                    header("Location: ../views/consulta/consultas_medico.php");
                    exit;
                }
                
                // PRIMEIRO busca a consulta para pegar dados do paciente
                $consulta = Consulta::buscarPorId($consulta_id);
                if(!$consulta) {
                    $_SESSION['msg_erro'] = "Consulta não encontrada!";
                    header("Location: ../views/consulta/consultas_medico.php");
                    exit;
                }
                
                // DEPOIS atualiza status
                $sucesso = Consulta::atualizarStatus($consulta_id, 'realizada');
                
                // FINALMENTE notifica o paciente
                if($sucesso){
                    $data_formatada = date('d/m/Y', strtotime($consulta['data']));
                    $hora_formatada = substr($consulta['hora'], 0, 5);
                    $mensagem = "✅ Sua consulta de {$data_formatada} às {$hora_formatada} foi marcada como REALIZADA";
                    Notificacao::criar('consulta', $mensagem, $consulta['paciente_id']);
                    
                    $_SESSION['msg_sucesso'] = "Consulta marcada como realizada!";
                } else {
                    $_SESSION['msg_erro'] = "Erro ao atualizar status da consulta.";
                }
                header("Location: ../views/consulta/consultas_medico.php");
                exit;
            }
            break;
            
        case 'cancelar':
            if($perfil == 'medico'){
                $consulta_id = (int)$_POST['consulta_id'];
                
                // Busca dados da consulta antes de cancelar
                $consulta = Consulta::buscarPorId($consulta_id);
                if(!$consulta) {
                    $_SESSION['msg_erro'] = "Consulta não encontrada!";
                    header("Location: ../views/consulta/consultas_medico.php");
                    exit;
                }
                
                $sucesso = Consulta::atualizarStatus($consulta_id, 'cancelada');
                
                if($sucesso){
                    $data_formatada = date('d/m/Y', strtotime($consulta['data']));
                    $hora_formatada = substr($consulta['hora'], 0, 5);
                    $mensagem = "❌ Sua consulta de {$data_formatada} às {$hora_formatada} foi CANCELADA";
                    Notificacao::criar('consulta', $mensagem, $consulta['paciente_id']);
                    
                    $_SESSION['msg_sucesso'] = "Consulta cancelada!";
                } else {
                    $_SESSION['msg_erro'] = "Erro ao cancelar consulta.";
                }
                header("Location: ../views/consulta/consultas_medico.php");
                exit;
            }
            break;
            
        case 'nao_compareceu':
            if($perfil == 'medico'){
                $consulta_id = (int)$_POST['consulta_id'];
                
                // Verificar se é o dia da consulta
                if(!Consulta::ehDiaDaConsulta($consulta_id)) {
                    $_SESSION['msg_erro'] = "Só é possível marcar como não compareceu no dia da consulta!";
                    header("Location: ../views/consulta/consultas_medico.php");
                    exit;
                }
                
                // Busca dados da consulta
                $consulta = Consulta::buscarPorId($consulta_id);
                if(!$consulta) {
                    $_SESSION['msg_erro'] = "Consulta não encontrada!";
                    header("Location: ../views/consulta/consultas_medico.php");
                    exit;
                }
                
                $sucesso = Consulta::atualizarStatus($consulta_id, 'nao_compareceu');
                
                if($sucesso){
                    $data_formatada = date('d/m/Y', strtotime($consulta['data']));
                    $hora_formatada = substr($consulta['hora'], 0, 5);
                    $mensagem = "⚠️ Sua consulta de {$data_formatada} às {$hora_formatada} foi marcada como NÃO COMPARECEU";
                    Notificacao::criar('consulta', $mensagem, $consulta['paciente_id']);
                    
                    $_SESSION['msg_sucesso'] = "Consulta marcada como 'não compareceu'!";
                } else {
                    $_SESSION['msg_erro'] = "Erro ao atualizar status da consulta.";
                }
                header("Location: ../views/consulta/consultas_medico.php");
                exit;
            }
            break;
            
        default:
            throw new Exception("Ação inválida.");
    }
    
} catch(Exception $e) {
    $_SESSION['msg_erro'] = $e->getMessage();
}

// Redireciona para o dashboard se não foi redirecionado ainda
header("Location: ../views/dashboard.php");
exit;
?>