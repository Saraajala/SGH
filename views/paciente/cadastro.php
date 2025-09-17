<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
<form method="POST" action="../../controllers/UsuarioController.php">
    <input type="hidden" name="acao" value="cadastrar">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    Perfil:
    <select name="perfil" required>
        <option value="medico">Médico</option>
        <option value="enfermeiro">Enfermeiro</option>
        <option value="paciente">Paciente</option>
    </select><br>
    <button type="submit">Cadastrar</button>
</form>

</body>
</html>