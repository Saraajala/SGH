<?php
session_start();
$erro_login = '';
if (isset($_SESSION['erro_login'])) {
    $erro_login = $_SESSION['erro_login'];
    unset($_SESSION['erro_login']); // Limpa a mensagem para não aparecer sempre
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumière - Login</title>
    <link rel="stylesheet" href="../estilo.css">
    <link rel="icon" href="favicon_round.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Mensagem de erro */
        .login-error {
            background-color: #ffe6e6;
            color: #d8000c;
            border: 1px solid #d8000c;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Esquerda - Branding -->
        <div class="login-brand">
            <div class="brand-content">
                <div class="brand-logo">
                    <img src="sem fundo.png" alt="logo">
                </div>
                <br><br><br><br><br>
                <p>Plataforma integrada de gestão médica com tecnologia de ponta para profissionais da saúde</p>
            </div>
        </div>

        <!-- Lado direito (formulário) -->
        <div class="login-form-section">
            <div class="login-form-container">
                <div class="form-header">
                    <h2>Bem-vindo de volta</h2>
                    <p>Faça login para acessar sua conta</p>
                </div>

                <!-- Mensagem de erro -->
                <?php if ($erro_login): ?>
                    <div class="login-error">
                        <i class="fa-solid fa-triangle-exclamation"></i> <?= $erro_login ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário PHP funcional -->
                <form class="login-form" method="POST" action="../controllers/UsuarioController.php">
                    <input type="hidden" name="acao" value="login">

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-container">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" id="email" name="email" class="form-input" placeholder="seu.email@hospital.com.br" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="senha">Senha</label>
                        <div class="input-container">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="senha" name="senha" class="form-input" placeholder="Digite sua senha" required>
                        </div>
                    </div>

                    <div class="form-options">
                        <a href="recuperar_senha.php" class="forgot-password">Esqueceu a senha?</a>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-right-to-bracket"></i> Entrar
                    </button>
                </form>

                <div class="signup-link">
                    Não tem uma conta? <a href="paciente/cadastro.php">Criar conta</a>
                </div>

                <div class="footer-text">
                    <i class="fa-solid fa-shield-halved"></i> Protegido por criptografia
                </div>
            </div>
        </div>
    </div>
</body>
</html>
