<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Login</h2>
<form method="POST" action="../controllers/UsuarioController.php">
    <input type="hidden" name="acao" value="login">
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit">Entrar</button>
</form>

<p>Não tem cadastro? <a href="paciente/cadastro.php">Cadastre-se</a></p>

</body>
</html>