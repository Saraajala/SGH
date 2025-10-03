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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Lumière</title>
    <link rel="stylesheet" href="../estilo.css">
    <link rel="icon" href="favicon_round.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="login-wrapper">
        <!-- Esquerda - Branding -->
        <div class="login-brand">
            <div class="brand-content">
                <div class="brand-logo">
                    <img src="sem fundo.png" alt="logo">
                </div><br><br><br><br><br>
                <p>Plataforma integrada de gestão médica com tecnologia de ponta para profissionais da saúde</p>
            </div>
        </div>

        <!-- Lado direito (formulário) -->
        <div class="login-form-section">
            <div class="login-form-container">
                <div class="form-header">
                    <h2>Recuperar Senha</h2>
                    <p>Informe seu e-mail ou crie uma nova senha</p>
                </div>

                <?php if ($erro): ?>
                <p style="color: #e53e3e; text-align:center; margin-bottom:20px;"><i class="fa-solid fa-triangle-exclamation"></i> <?= $erro ?></p>
                <?php endif; ?>

                <?php if ($sucesso): ?>
                <p style="color: #38a169; text-align:center; margin-bottom:20px;"><i class="fa-solid fa-circle-check"></i> <?= $sucesso ?></p>
                <?php endif; ?>

                <?php if ($etapa == 1 && !$sucesso): ?>
                <form method="POST" class="login-form">
                    <div class="form-group">
                        <label class="form-label" for="email">Digite seu e-mail cadastrado:</label>
                        <div class="input-container">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" id="email" name="email" class="form-input" placeholder="seu.email@hospital.com.br" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-envelope-circle-check"></i> Verificar
                    </button>
                </form>
                <?php endif; ?>

                <?php if ($etapa == 2 && !$sucesso): ?>
                <form method="POST" class="login-form">
                    <div class="form-group">
                        <label class="form-label" for="nova_senha">Nova senha:</label>
                        <div class="input-container">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="nova_senha" name="nova_senha" class="form-input" placeholder="Digite sua nova senha" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-key"></i> Salvar nova senha
                    </button>
                </form>
                <?php endif; ?>

                <div class="signup-link" style="text-align:center; margin-top:20px;">
                    <a href="login.php"><i class="fa-solid fa-arrow-left"></i> Voltar ao Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
