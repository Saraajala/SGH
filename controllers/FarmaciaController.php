<?php
session_start();
include '../models/Farmacia.php';

// Permissões: apenas médico, recepção ou enfermeiro
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','recepcao','enfermeiro'])){
    $_SESSION['msg_erro'] = "Acesso negado.";
    header('Location: ../views/dashboard.php');
    exit;
}

$acao = $_POST['acao'] ?? '';
$usuario_id = $_SESSION['id_usuario'] ?? null;

try {
    switch($acao){
        case 'cadastrar':
            $nome = trim($_POST['nome'] ?? '');
            $descricao = trim($_POST['descricao'] ?? '');
            $quantidade = (int)($_POST['quantidade'] ?? 0);

            if(empty($nome) || $quantidade <= 0){
                throw new Exception("Nome ou quantidade inválidos.");
            }

            Farmacia::cadastrarMedicamento($nome, $descricao, $quantidade, $usuario_id);
            $_SESSION['msg_sucesso'] = "Medicamento cadastrado com sucesso!";
            break;

        case 'dispensar':
            $medicamento_id = (int)($_POST['medicamento_id'] ?? 0);
            $paciente_id = (int)($_POST['paciente_id'] ?? 0);
            $quantidade = (int)($_POST['quantidade'] ?? 0);

            if($medicamento_id <= 0 || $paciente_id <= 0 || $quantidade <= 0){
                throw new Exception("Dados inválidos para dispensação.");
            }

            // CORREÇÃO: Removeu o medico_id e usa apenas os dados necessários
            $sucesso = Farmacia::dispensar($medicamento_id, $paciente_id, $quantidade, $usuario_id);
            if($sucesso){
                $_SESSION['msg_sucesso'] = "Medicamento dispensado com sucesso!";
            } else {
                $_SESSION['msg_erro'] = "Estoque insuficiente!";
            }
            break;

        default:
            throw new Exception("Ação inválida.");
    }
} catch(Exception $e){
    $_SESSION['msg_erro'] = $e->getMessage();
}

// Redireciona para a view correta dentro de views
header('Location: ../views/farmacia/farmacia.php');
exit;
?>