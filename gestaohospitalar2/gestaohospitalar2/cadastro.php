<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['password'];
    $tipo = $_POST['tipo']; // 'paciente' ou 'medico'

    if (!isset($_SESSION['usuarios'])) {
        $_SESSION['usuarios'] = [];
    }

    $_SESSION['usuarios'][$email] = [
        'nome' => $nome,
        'senha' => $senha,
        'tipo' => $tipo
    ];

    // Redireciona para login
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MedCare Pro - Cadastro</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f8fafc;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    .register-container {
      width: 100%;
      max-width: 450px;
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .form-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .form-header h2 {
      font-size: 28px;
      font-weight: 700;
      color: #1a202c;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #2d3748;
    }
    .form-input, select {
      width: 100%;
      padding: 14px;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 15px;
      background: #f7fafc;
      transition: 0.3s;
    }
    .form-input:focus, select:focus {
      border-color: #4b9dad;
      background: white;
      outline: none;
    }
    .btn-primary {
      width: 100%;
      background: linear-gradient(135deg, #4b9dad 0%, #8BCAD9 100%);
      color: white;
      border: none;
      padding: 16px;
      border-radius: 14px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      box-shadow: 0 6px 20px rgba(75, 157, 173, 0.4);
      transform: translateY(-2px);
    }
    .login-link {
      margin-top: 20px;
      text-align: center;
      font-size: 14px;
      color: #718096;
    }
    .login-link a {
      color: #4b9dad;
      text-decoration: none;
      font-weight: 600;
    }
    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="register-container">
    <div class="form-header">
      <h2>Criar Conta</h2>
    </div>
    <form method="POST" action="cadastro.php">
      <div class="form-group">
        <label class="form-label" for="nome">Nome Completo</label>
        <input type="text" id="nome" name="nome" class="form-input" required>
      </div>
      
      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" name="email" class="form-input" required>
      </div>
      
      <div class="form-group">
        <label class="form-label" for="password">Senha</label>
        <input type="password" id="password" name="password" class="form-input" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="tipo">Cadastrar como:</label>
        <select id="tipo" name="tipo" required>
          <option value="">Selecione</option>
          <option value="paciente">Paciente</option>
          <option value="medico">Médico</option>
        </select>
      </div>
      
      <a href="index.php"><button type="submit" class="btn-primary">Cadastrar</button></a>
    </form>

    <div class="login-link">
      Já tem uma conta? <a href="login.php">Entrar</a>
    </div>
  </div>
</body>
</html>
