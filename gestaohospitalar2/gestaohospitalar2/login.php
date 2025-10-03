<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    if (isset($_SESSION['usuarios'][$email]) && $_SESSION['usuarios'][$email]['senha'] === $senha) {
        $_SESSION['usuario_logado'] = $email;
        header("Location: perfil.php"); // Redireciona para perfil
        exit;
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>

<form method="POST" action="login.php">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Senha">
    a<button type="submit">Entrar</button>
</form>

<?php if(isset($erro)) echo "<p>$erro</p>"; ?>
