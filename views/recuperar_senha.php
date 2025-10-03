<?php
session_start();
include '../config.php';

$etapa = 1; // etapa inicial (informar email)
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Etapa 1 → verifica email
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email=?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $_SESSION['recupera_id'] = $usuario['id'];
            $etapa = 2; // próxima etapa → trocar senha
        } else {
            $erro = "E-mail não encontrado!";
        }
    }

    // Etapa 2 → salvar nova senha
    if (isset($_POST['nova_senha']) && isset($_SESSION['recupera_id'])) {
        $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
        $id = $_SESSION['recupera_id'];

        $stmt = $pdo->prepare("UPDATE usuarios SET senha=? WHERE id=?");
        $stmt->execute([$nova_senha, $id]);

        unset($_SESSION['recupera_id']);
        $sucesso = "Senha alterada com sucesso! <a href='login.php'>Fazer login</a>";
    }
}
?>

<h2>Recuperar Senha</h2>

<?php if ($erro): ?>
<p><?= $erro ?></p>
<?php endif; ?>

<?php if ($sucesso): ?>
<p><?= $sucesso ?></p>
<?php endif; ?>

<?php if ($etapa == 1 && !$sucesso): ?>
<form method="POST">
    <label>Digite seu e-mail cadastrado:</label><br>
    <input type="email" name="email" required>
    <button type="submit">Verificar</button>
</form>
<?php endif; ?>

<?php if ($etapa == 2 && !$sucesso): ?>
<form method="POST">
    <label>Nova senha:</label><br>
    <input type="password" name="nova_senha" required>
    <button type="submit">Salvar nova senha</button>
</form>
<?php endif; ?>

<p><a href="login.php">Voltar ao Login</a></p>
