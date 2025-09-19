<?php
include __DIR__ . '/../config.php';

class Paciente {

    // Listar todos os pacientes (usuários com perfil 'paciente')
    public static function listar(){
        global $pdo;
        $stmt = $pdo->query("SELECT id, nome FROM usuarios WHERE perfil='paciente' ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar um paciente pelo id
    public static function buscar($id){
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE id = ? AND perfil='paciente'");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cadastrar paciente
    public static function cadastrar($nome, $email, $senha){
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, 'paciente')");
        $stmt->execute([$nome, $email, $senha]);
    }

    // Atualizar paciente
    public static function atualizar($id, $nome, $email){
        global $pdo;
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ? AND perfil='paciente'");
        $stmt->execute([$nome, $email, $id]);
    }

    // Deletar paciente
    public static function deletar($id){
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ? AND perfil='paciente'");
        $stmt->execute([$id]);
    }
}
?>
