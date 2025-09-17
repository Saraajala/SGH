<?php
include 'config.php';

class Paciente {

    public static function listar(){
        global $pdo;
        return $pdo->query("SELECT * FROM pacientes")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscar($id){
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM pacientes WHERE id=$id");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function cadastrar($nome, $idade, $contato, $endereco, $historico){
        global $pdo;
        $pdo->exec("INSERT INTO pacientes(nome,idade,contato,endereco,historico)
                    VALUES('$nome','$idade','$contato','$endereco','$historico')");
    }

    public static function atualizar($id,$nome,$idade,$contato,$endereco,$historico){
        global $pdo;
        $pdo->exec("UPDATE pacientes SET nome='$nome', idade='$idade', contato='$contato',
                    endereco='$endereco', historico='$historico' WHERE id=$id");
    }

    public static function deletar($id){
        global $pdo;
        $pdo->exec("DELETE FROM pacientes WHERE id=$id");
    }
}
?>
