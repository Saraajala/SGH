<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumière - Cadastro</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="icon" href="../favicon_round.png" type="image/png">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
   <div class="login-wrapper">
        <!-- Esquerda - Branding -->
        <div class="login-brand">
            <div class="brand-content">
                <div class="brand-logo">
                    <img src="../sem fundo.png" alt="logo">
                </div>
                <br><br><br><br><br>
                <p>Plataforma integrada de gestão médica com tecnologia de ponta para profissionais da saúde</p>
            </div>
        </div>

        <!-- Lado direito - Formulário -->
        <div class="register-form-section">
            <div class="register-form-container">
                <div class="form-header">
                    <h2>Criar Conta</h2>
                    <p>Preencha seus dados para começar</p>
                </div>

                <form class="register-form" method="POST" action="../../controllers/UsuarioController.php">
                    <input type="hidden" name="acao" value="cadastrar">

                    <div class="form-group">
                        <label class="form-label" for="nome">Nome</label>
                        <div class="input-container">
                            <i class="fa fa-user"></i>
                            <input type="text" id="nome" name="nome" class="form-input" placeholder="Seu nome" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-container">
                            <i class="fa fa-envelope"></i>
                            <input type="email" id="email" name="email" class="form-input" placeholder="seu.email@hospital.com.br" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="senha">Senha</label>
                        <div class="input-container">
                            <i class="fa fa-lock"></i>
                            <input type="password" id="senha" name="senha" class="form-input" placeholder="Senha" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="perfil">Perfil</label>
                        <div class="input-container">
                            <i class="fa fa-id-badge"></i>
                            <select id="perfil" name="perfil" class="form-select" required>
                                <option value="">Selecione seu perfil</option>
                                <option value="paciente">Paciente</option>
                                <option value="medico">Médico</option>
                                <option value="enfermeiro">Enfermeiro</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fa fa-user-plus"></i> Cadastrar
                    </button>
                </form>
            </div>
        </div>

    </div>


</body>
</html>
