<?php
session_start();
require_once '../models/Internacao.php';
require_once '../models/Notificacao.php';
include __DIR__ . '/../config.php';

// Apenas médico ou enfermeiro podem acessar
if(!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], ['medico','enfermeiro'])){
    header('Location: ../index.php');
    exit;
}

$model = new Internacao();
$acao = $_POST['acao'] ?? '';

if($acao == 'internar'){
    $res = $model->internar($_POST['paciente_id'], $_POST['quarto_id'], $_SESSION['id_usuario']);
    
    if(strpos($res, 'sucesso:') !== false){
        // Notificar paciente sobre internação
        $mensagem = "🏥 Você foi internado no hospital. Desejamos uma rápida recuperação!";
        Notificacao::criar('internacao', $mensagem, $_POST['paciente_id']);
    }
    
    $_SESSION['mensagem'] = $res;
} elseif($acao == 'dar_alta') {
    $res = $model->darAlta($_POST['internacao_id']);
    
    if(strpos($res, 'sucesso:') !== false){
        // Buscar dados da internação para notificar
        $stmt = $pdo->prepare("SELECT paciente_id FROM internacoes WHERE id = ?");
        $stmt->execute([$_POST['internacao_id']]);
        $internacao = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($internacao){
            $mensagem = "🎉 Você recebeu alta médica! Cuide-se bem!";
            Notificacao::criar('internacao', $mensagem, $internacao['paciente_id']);
        }
    }
    
    $_SESSION['mensagem'] = $res;
} elseif($acao == 'transferir') {
    $res = $model->transferir($_POST['internacao_id'], $_POST['novo_quarto_id']);
    
    if(strpos($res, 'sucesso:') !== false){
        // Buscar dados da internação para notificar
        $stmt = $pdo->prepare("SELECT i.paciente_id, q.numero, q.ala 
                              FROM internacoes i 
                              JOIN quartos q ON i.quarto_id = q.id 
                              WHERE i.id = ?");
        $stmt->execute([$_POST['internacao_id']]);
        $internacao = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($internacao){
            $mensagem = "🔄 Você foi transferido para o quarto {$internacao['numero']} da ala {$internacao['ala']}";
            Notificacao::criar('internacao', $mensagem, $internacao['paciente_id']);
        }
    }
    
    $_SESSION['mensagem'] = $res;
} elseif($acao == 'liberar_quarto') {
    $stmt = $pdo->prepare("UPDATE quartos SET status = 'disponivel' WHERE numero = ?");
    $stmt->execute([$_POST['numero_quarto']]);
    exit;
}

header('Location: ../views/internacao/internacao.php');
exit;
?>