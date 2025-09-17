<?php
include '../models/Usuario.php';
session_start();

if (isset($_POST['acao'])) {

    if ($_POST['acao'] == 'cadastrar') {
        $nome  = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = $_POST['senha'];
        $perfil = $_POST['perfil']; 

        // Verificar se e-mail já existe
        if (Usuario::emailExiste($email)) {
            echo "E-mail já cadastrado!";
            exit;
        }

        Usuario::cadastrar($nome, $email, $senha, $perfil);

        header('Location: ../index.php');
        exit;
    }

    if ($_POST['acao'] == 'login') {
        $email = trim($_POST['email']);
        $senha = $_POST['senha'];

        $user = Usuario::login($email, $senha);

        if ($user) {
            $_SESSION['id_usuario'] = $user['id'];
            $_SESSION['perfil']     = $user['perfil'];
            $_SESSION['nome']       = $user['nome'];

            header('Location: ../views/dashboard.php');
            exit;
        } else {
            echo "Login inválido!";
            exit;
        }
    }

} else {
    echo "Ação inválida!";
    exit;
}
