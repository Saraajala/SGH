<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Lumière - Cuidando de Você</title>
    <link rel="stylesheet" href="estilo.css">
        <link rel="icon" href="favicon_round.png" type="image/png">


</head>
<body>

    <header class="header">
        <nav class="nav">
            <div class="nav-container">
                <div class="logo">
                    <div class="hero-visual2">
                        <div class="hero-circle2">
                            <img src="logo.png" alt="Logo Hospital Vida Nova" class="hero-logo">
                        </div>
                    </div>
                    <h1 class="logo-text">Clínica Lumière</h1>
                </div>
                <a href="views/login.php"><button class="btn-primary">Entrar</button></a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="inicio" class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h2 class="hero-title">
                        Cuidando da sua saúde com 
                        <span class="hero-highlight">excelência</span>
                    </h2>
                    <p class="hero-description">
                        Há mais de 30 anos oferecendo atendimento médico de qualidade, 
                        com tecnologia de ponta e profissionais especializados.
                    </p>
                    <div class="hero-buttons">
<a href="#contato"><button class="btn-secondary">Saiba Mais</button></a>
                        <button class="btn-outline">Emergência 24h</button>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="hero-circle">
                        <img src="sem fundo.png" alt="Logo Hospital Vida Nova" class="hero-logo">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Serviços -->
    <section id="servicos" class="services">
        <div class="container">
            <div class="section-header">
                <h3 class="section-title">Nossos Serviços</h3>
                <p class="section-description">
                    Oferecemos uma ampla gama de serviços médicos com tecnologia avançada e atendimento humanizado
                </p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 class="service-title">Emergência 24h</h4>
                    <p class="service-description">Atendimento de urgência e emergência disponível 24 horas por dia, 7 dias por semana.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="service-title">Exames Diagnósticos</h4>
                    <p class="service-description">Laboratório completo e exames de imagem com equipamentos de última geração.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                    </div>
                    <h4 class="service-title">Consultas Especializadas</h4>
                    <p class="service-description">Mais de 20 especialidades médicas com profissionais altamente qualificados.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h8a2 2 0 002-2V3a2 2 0 012 2v6h-3a2 2 0 100 4h3v1a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 class="service-title">Internação</h4>
                    <p class="service-description">Quartos confortáveis e UTI equipada para garantir o melhor cuidado aos pacientes.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 class="service-title">Check-up Preventivo</h4>
                    <p class="service-description">Programas de prevenção e check-up completo para manter sua saúde em dia.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>
                        </svg>
                    </div>
                    <h4 class="service-title">Orientações Farmacêuticas</h4>
                    <p class="service-description">Acompanhamento de medicamento personalizado para cada paciente.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Especialidades -->
<section id="especialidades" class="specialties">
    <div class="container">
        <div class="section-header">
            <h3 class="section-title">Medicamentos Disponíveis</h3>
            <p class="section-description">
                Confira alguns dos principais medicamentos que oferecemos.
            </p>
        </div>

        <div class="specialties-grid">
            <div class="specialty-card">
                <h5 class="specialty-title">Dipirona</h5>
                <p class="specialty-description">Alívio da dor e redução de febre.</p>
            </div>
            <div class="specialty-card">
                <h5 class="specialty-title">Paracetamol</h5>
                <p class="specialty-description">Alívio da dor e redução de febre.</p>
            </div>
            <div class="specialty-card">
                <h5 class="specialty-title">Nimesulida</h5>
                <p class="specialty-description">Anti-inflamatório e analgésico.</p>
            </div>
            <div class="specialty-card">
                <h5 class="specialty-title">Polaramine</h5>
                <p class="specialty-description">Alívio de sintomas alérgicos.</p>
            </div>
            <div class="specialty-card">
                <h5 class="specialty-title">Amoxicilina</h5>
                <p class="specialty-description">Antibiótico utilizado para infecções.</p>
            </div>
            <div class="specialty-card">
                <h5 class="specialty-title">Omeprazol</h5>
                <p class="specialty-description">Protege o estômago e reduz acidez.</p>
            </div>
            <div class="specialty-card">
                <h5 class="specialty-title">Lorazepam</h5>
                <p class="specialty-description">Ansiedade e distúrbios do sono.</p>
            </div>
            <div class="specialty-card">
                <h5 class="specialty-title">Cetirizina</h5>
                <p class="specialty-description">Anti-histamínico para alergias.</p>
            </div>
        </div>
    </div>
</section>

            
            </div>
        </div>
    </section>

    <!-- Contato -->
   <section id="contato" class="contact">
    <div class="container">
        <div class="section-header">
            <h3 class="section-title">Saiba Mais</h3>
            <p class="section-description">Estamos aqui para cuidar de você</p>
        </div>

        <div class="contact-content">
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="contact-details">
                        <h4 class="contact-title">Endereço</h4>
                        <p class="contact-text">Rua da Saúde, 123<br>Centro - São Paulo, SP<br>CEP: 01234-567</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                    </div>
                    <div class="contact-details">
                        <h4 class="contact-title">Telefone</h4>
                        <p class="contact-text">Central de Atendimento:<br>(11) 3456-7890<br>Emergência: (11) 9876-5432</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                    </div>
                    <div class="contact-details">
                        <h4 class="contact-title">E-mail</h4>
                        <p class="contact-text">contato@Lumière.com.br<br>suporte@Lumière.com.br</p>
                    </div>
                </div>
            </div>

            <div class="contact-info-text">
                <h4 class="text-title">Saiba Mais Sobre a Clínica</h4>
                <p class="text-description">
                    A Clínica Lumière oferece atendimento humanizado, tecnologia de ponta e profissionais altamente capacitados. 
                    Nosso objetivo é cuidar da sua saúde e bem-estar em todas as etapas da vida. 
                    <br><br>
                    Oferecemos consultas especializadas, exames de alta precisão, internação com conforto e segurança, emergência 24h e orientações farmacêuticas personalizadas.
                </p>
            </div>
        </div>
    </div>
</section>


    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM10 18a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="footer-logo-text">Lumière</h3>
                    </div>
                    <p class="footer-description">Cuidando de você e sua família há mais de 30 anos com excelência e dedicação.</p>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-title">Serviços</h4>
                    <ul class="footer-list">
                        <li>Emergência 24h</li>
                        <li>Consultas</li>
                        <li>Exames</li>
                        <li>Cirurgias</li>
                        <li>Internação</li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-title">Medicamentos</h4>
                    <ul class="footer-list">
                        <li>Nimesulida</li>
                        <li>Paracetamol</li>
                        <li>Dipirona</li>
                        <li>Amoxilina</li>
                        <li>Omeprazol</li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4 class="footer-title">Contato</h4>
                    <ul class="footer-list">
                        <li>📍 Rua da Saúde, 123</li>
                        <li>📞 (11) 3456-7890</li>
                        <li>🚨 (11) 9876-5432</li>
                        <li>✉️ contato@Lumière.com.br</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Lumière. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Configuração inicial
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();
        });

        // Função principal de inicialização
        function initializeApp() {
            setupSmoothScrolling();
            setupFormHandling();
            setupButtonActions();
            setupAnimations();
            setupFormValidation();
            setupPhoneMask();
            addBackToTopButton();
        }

        // Smooth scrolling para links de navegação
        function setupSmoothScrolling() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        }

        // Manipulação do formulário de contato
        function setupFormHandling() {
            const form = document.getElementById('contactForm');
            if (form) {
                form.addEventListener('submit', handleFormSubmit);
            }
        }

        function handleFormSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const submitButton = e.target.querySelector('.form-submit');
            
            // Simular loading
            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';
            
            // Simular envio (em um projeto real, aqui seria a chamada para API)
            setTimeout(() => {
                showSuccessMessage();
                resetForm(e.target);
                
                // Restaurar botão
                submitButton.disabled = false;
                submitButton.textContent = 'Enviar Solicitação';
            }, 2000);
        }

        function showSuccessMessage() {
            // Criar modal de sucesso
            const modal = document.createElement('div');
            modal.className = 'success-modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <div class="success-icon">✓</div>
                    <h3>Solicitação Enviada!</h3>
                    <p>Obrigado pelo seu interesse! Em breve entraremos em contato para agendar sua consulta.</p>
                    <button onclick="this.parentElement.parentElement.remove()" class="btn-primary">Fechar</button>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function resetForm(form) {
            form.reset();
            // Remover classes de validação
            form.querySelectorAll('.form-input').forEach(input => {
                input.classList.remove('error', 'success');
            });
        }

        // Configurar ações dos botões
        function setupButtonActions() {
            // Botões de agendamento
            document.querySelectorAll('button').forEach(button => {
                if (button.textContent.includes('Agendar')) {
                    button.addEventListener('click', function() {
                        document.querySelector('#contato').scrollIntoView({
                            behavior: 'smooth'
                        });
                    });
                }
                
                if (button.textContent.includes('Emergência')) {
                    button.addEventListener('click', function() {
                        showEmergencyAlert();
                    });
                }
            });
        }

        function showEmergencyAlert() {
            const modal = document.createElement('div');
            modal.className = 'emergency-modal';
            modal.innerHTML = `
                <div class="modal-content emergency">
                    <div class="emergency-icon">🚨</div>
                    <h3>Emergência 24h</h3>
                    <p>Em caso de emergência, ligue imediatamente para:</p>
                    <div class="emergency-number">(11) 9876-5432</div>
                    <p>Ou dirija-se ao nosso pronto-socorro na Rua da Saúde, 123</p>
                    <button onclick="this.parentElement.parentElement.remove()" class="btn-primary">Entendi</button>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Configurar animações
        function setupAnimations() {
            // Intersection Observer para animações ao scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            // Observar cards de serviços e especialidades
            document.querySelectorAll('.service-card, .specialty-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        }

        // Validação de formulário em tempo real
        function setupFormValidation() {
            const inputs = document.querySelectorAll('.form-input');
            
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                });
            });
        }

        function validateField(field) {
            const value = field.value.trim();
            let isValid = true;
            
            // Validação básica
            if (field.hasAttribute('required') && !value) {
                isValid = false;
            }
            
            // Validação específica por tipo
            if (field.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailRegex.test(value);
            }
            
            if (field.type === 'tel' && value) {
                const phoneRegex = /^\(\d{2}\)\s\d{4,5}-\d{4}$/;
                isValid = phoneRegex.test(value);
            }
            
            // Aplicar classes visuais
            field.classList.remove('error', 'success');
            if (value) {
                field.classList.add(isValid ? 'success' : 'error');
            }
            
            return isValid;
        }

        // Máscara para telefone
        function setupPhoneMask() {
            const phoneInput = document.querySelector('input[type="tel"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
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
            }
        }

        // Função para scroll suave ao topo
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Adicionar botão de voltar ao topo
        function addBackToTopButton() {
            const button = document.createElement('button');
            button.innerHTML = '↑';
            button.className = 'back-to-top';
            button.onclick = scrollToTop;
            
            document.body.appendChild(button);
            
            // Mostrar/esconder baseado no scroll
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    button.classList.add('visible');
                } else {
                    button.classList.remove('visible');
                }
            });
        }

        // Efeitos visuais adicionais
        function addVisualEffects() {
            // Efeito parallax suave no hero
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.hero');
                if (hero) {
                    hero.style.transform = `translateY(${scrolled * 0.5}px)`;
                }
            });

            // Contador animado para estatísticas
            const stats = document.querySelectorAll('.stat-number');
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        statsObserver.unobserve(entry.target);
                    }
                });
            });

            stats.forEach(stat => {
                statsObserver.observe(stat);
            });
        }

        function animateCounter(element) {
            const target = parseInt(element.textContent.replace(/\D/g, ''));
            const suffix = element.textContent.replace(/\d/g, '');
            let current = 0;
            const increment = target / 50;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + suffix;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + suffix;
                }
            }, 40);
        }

        // Inicializar efeitos visuais quando o DOM estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(addVisualEffects, 1000);
        });

        // Função para melhorar a acessibilidade
        function improveAccessibility() {
            // Adicionar indicadores de foco visíveis
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });

            document.addEventListener('mousedown', function() {
                document.body.classList.remove('keyboard-navigation');
            });

            // Melhorar navegação por teclado
            const focusableElements = document.querySelectorAll(
                'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );

            focusableElements.forEach(element => {
                element.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && element.tagName === 'BUTTON') {
                        element.click();
                    }
                });
            });
        }

        // Inicializar melhorias de acessibilidade
        document.addEventListener('DOMContentLoaded', function() {
            improveAccessibility();
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9722bbf7332f2912',t:'MTc1NTcwMTc1My4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>





</html>


