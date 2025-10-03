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
    <!-- Lado esquerdo -->
    <div class="login-brand">
      <div class="brand-content">
        <div class="brand-logo">
          <img src="../sem fundo.png" alt="Logo Lumière">
        </div><br><br><br><br><br>
        <p>Junte-se à nossa plataforma e revolucione o cuidado médico com tecnologia de ponta</p>
      </div>
    </div>

    <!-- Lado direito - Cadastro -->
    <div class="register-form-section">
      <div class="register-form-container">
        <div class="form-header">
          <h2>Criar Conta</h2>
          <p>Preencha seus dados para começar</p>
        </div>

        <form class="register-form" method="POST">
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

  <script>
    // Redireciona para login.php ao clicar em cadastrar
    document.querySelector('.register-form').addEventListener('submit', function(e) {
      e.preventDefault(); // impede envio real do formulário
      window.location.href = '../login.php';
    });
  </script>
</body>
</html>


    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
           
            // Get form data
            const formData = new FormData(this);
            const password = formData.get('password');
            const confirmPassword = formData.get('confirmPassword');
           
            // Validate passwords match
            if (password !== confirmPassword) {
                alert('As senhas não coincidem. Por favor, verifique e tente novamente.');
                return;
            }
           
            // Simulate registration process
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
           
            // Show loading state
            submitButton.textContent = 'Criando conta...';
            submitButton.disabled = true;
           
            // Simulate API call
            setTimeout(() => {
                // Show success message
                document.getElementById('successMessage').style.display = 'block';
               
                // Reset form
                this.reset();
               
                // Reset button
                submitButton.textContent = originalText;
                submitButton.disabled = false;
               
                // Scroll to top to show success message
                document.querySelector('.register-form-section').scrollTop = 0;
               
                // Auto redirect to login after 3 seconds
                setTimeout(() => {
                    window.location.href = '../index.html';
                }, 3000);
               
            }, 2000);
        });

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 7) {
                value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else if (value.length >= 3) {
                value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            }
            e.target.value = value;
        });

        // CRM formatting
        document.getElementById('crm').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            e.target.value = value;
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strength = getPasswordStrength(password);
           
            // Remove existing strength indicator
            const existingIndicator = e.target.parentNode.parentNode.querySelector('.password-strength');
            if (existingIndicator) {
                existingIndicator.remove();
            }
           
            if (password.length > 0) {
                const indicator = document.createElement('div');
                indicator.className = 'password-strength';
                indicator.style.cssText = `
                    margin-top: 8px;
                    font-size: 12px;
                    font-weight: 500;
                `;
               
                if (strength < 3) {
                    indicator.style.color = '#e53e3e';
                    indicator.textContent = 'Senha fraca - adicione números e símbolos';
                } else if (strength < 4) {
                    indicator.style.color = '#dd6b20';
                    indicator.textContent = 'Senha média - adicione mais caracteres';
                } else {
                    indicator.style.color = '#38a169';
                    indicator.textContent = 'Senha forte ✓';
                }
               
                e.target.parentNode.parentNode.appendChild(indicator);
            }
        });

        function getPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'976cbd376642f179',t:'MTc1NjQ3Nzc1MS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>


