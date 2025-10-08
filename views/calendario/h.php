<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Lumière - Agendamentos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen">
    <!-- Header da Clínica -->
    <div class="clinic-header text-white py-6 mb-8">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Clínica Lumière</h1>
                        <p class="text-white/80">Sistema de Agendamentos</p>
                    </div>
                </div>
<div class="flex items-center space-x-4">
    <div class="text-right">
        <p class="text-sm text-white/80">Bem-vindo,</p>
        <p class="font-semibold">Dr. João Santos</p>
    </div>
    <a href="perfil.html" class="bg-white/20 p-2 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
    </a>
</div>
       </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar Esquerda -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Consultas de Hoje -->
                <div class="medical-card rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" style="color: #1b91a6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Hoje
                    </h3>
                   
                    <div class="space-y-3">
                        <div class="p-3 rounded-lg border-l-4" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); border-color: #66D9D9;">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-800">14:30</p>
                                    <p class="text-sm text-gray-600">Dr. Ana Silva</p>
                                    <p class="text-xs" style="color: #1b91a6;">Cardiologia</p>
                                </div>
                                <div class="w-2 h-2 rounded-full" style="background: #66D9D9;"></div>
                            </div>
                        </div>
                       
                        <div class="p-3 rounded-lg border-l-4 bg-gray-50 border-gray-300">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-800">16:00</p>
                                    <p class="text-sm text-gray-600">Dr. Carlos Lima</p>
                                    <p class="text-xs text-gray-500">Ortopedia</p>
                                </div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estatísticas Rápidas -->
                <div class="medical-card rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estatísticas</h3>
                   
                    <div class="space-y-4">
                        <div class="stats-card p-4 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-lg" style="background: #8BCAD9;">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-700">Este Mês</p>
                                        <p class="text-xs text-gray-500">Consultas</p>
                                    </div>
                                </div>
                                <span class="text-xl font-bold" style="color: #1b91a6;">127</span>
                            </div>
                        </div>
                       
                        <div class="stats-card p-4 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-lg" style="background: #66D9D9;">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-700">Pendentes</p>
                                        <p class="text-xs text-gray-500">Exames</p>
                                    </div>
                                </div>
                                <span class="text-xl font-bold" style="color:  #1b91a6;">8</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendário Principal -->
            <div class="lg:col-span-2">
                <div class="medical-card rounded-2xl p-8">
                    <!-- Header do Calendário -->
                    <div class="flex items-center justify-between mb-8">
                        <button onclick="previousMonth()" class="p-3 hover:bg-gray-100 rounded-xl transition-all duration-300">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                       
                        <div class="text-center">
                            <h2 id="currentMonth" class= "text-3xl font-bold text-gray-800">Janeiro 2024</h2>
                            <p class="text-gray-500 mt-1">Agendamentos Médicos</p>
                        </div>
                       
                        <button onclick="nextMonth()" class="p-3 hover:bg-gray-100 rounded-xl transition-all duration-300">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Dias da Semana -->
                    <div class="grid grid-cols-7 gap-3 mb-6">
                        <div class="text-center text-sm font-semibold py-3 rounded-lg" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); color: #193773;">DOM</div>
                        <div class="text-center text-sm font-semibold py-3 rounded-lg" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); color: #193773;">SEG</div>
                        <div class="text-center text-sm font-semibold py-3 rounded-lg" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); color: #193773;">TER</div>
                        <div class="text-center text-sm font-semibold py-3 rounded-lg" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); color: #193773;">QUA</div>
                        <div class="text-center text-sm font-semibold py-3 rounded-lg" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); color: #193773;">QUI</div>
                        <div class="text-center text-sm font-semibold py-3 rounded-lg" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); color: #193773;">SEX</div>
                        <div class="text-center text-sm font-semibold py-3 rounded-lg" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); color: #193773;">SÁB</div>
                    </div>

                    <!-- Dias do Calendário -->
                    <div id="calendarDays" class="grid grid-cols-7 gap-3">
                        <!-- Os dias serão gerados pelo JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Sidebar Direita -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Próxima Consulta -->
                <div class="medical-card rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" style="color: #66D9D9;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Próxima Consulta
                    </h3>
                   
                    <div class="p-4 rounded-xl border-l-4" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); border-color: #66D9D9;">
                        <div class="flex items-start space-x-3">
                            <div class="p-2 rounded-lg" style="background: #1b91a6;">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">Dr. Ana Silva</h4>
                                <p class="text-sm" style="color:  #1b91a6;">Cardiologista</p>
                                <div class="mt-2 space-y-1">
                                    <div class="flex items-center text-xs text-gray-600">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        15 Jan, 14:30
                                    </div>
                                    <div class="flex items-center text-xs text-gray-600">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        Sala 205
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="mt-4 flex space-x-2">
                            <button onclick="showAppointmentDetails()" class="flex-1 primary-button text-white text-xs font-medium py-2 px-3 rounded-lg">
                                Detalhes
                            </button>
                            <button onclick="rescheduleAppointment()" class="flex-1 secondary-button text-xs font-medium py-2 px-3 rounded-lg">
                                Reagendar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="medical-card rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ações Rápidas</h3>
                   
                    <div class="space-y-3">
                        <button onclick="scheduleNewAppointment()" class="w-full primary-button text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nova Consulta
                        </button>
                       
                        <button onclick="viewAllAppointments()" class="w-full secondary-button font-medium py-3 px-4 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Ver Agenda
                        </button>
                       
                        <button onclick="exportCalendar()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Exportar
                        </button>
                    </div>
                </div>

                <!-- Lembretes -->
                <div class="medical-card rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" style="color: #8BCAD9;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Lembretes
                    </h3>
                   
                    <div class="space-y-2">
                        <div class="p-3 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                            <p class="text-xs font-medium text-blue-800">Reunião de equipe</p>
                            <p class="text-xs text-blue-600">Amanhã às 9:00</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg border-l-4 border-green-400">
                            <p class="text-xs font-medium text-green-800">Atualizar prontuários</p>
                            <p class="text-xs text-green-600">Até sexta-feira</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes da Consulta -->
    <div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Detalhes da Consulta</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
           
            <div class="space-y-4">
                <div class="rounded-lg p-4 border-l-4" style="background: linear-gradient(135deg, rgba(139, 202, 217, 0.1) 0%, rgba(102, 217, 217, 0.1) 100%); border-color: #66D9D9;">
                    <h4 class="font-semibold text-gray-800 mb-2">Dr. Ana Silva - Cardiologista</h4>
                    <p class="text-sm text-gray-600 mb-3">Consulta de rotina para acompanhamento cardiovascular</p>
                   
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Data:</span>
                            <span class="font-medium">15 de Janeiro, 2024</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Horário:</span>
                            <span class="font-medium">14:30 - 15:30</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Local:</span>
                            <span class="font-medium">Clínica Saúde Total</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Endereço:</span>
                            <span class="font-medium">Rua das Flores, 123</span>
                        </div>
                    </div>
                </div>
               
                <div class="rounded-lg p-4 border-l-4 bg-blue-50 border-blue-400">
                    <h5 class="font-medium text-blue-800 mb-2">Preparação:</h5>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Trazer exames anteriores</li>
                        <li>• Jejum de 12 horas</li>
                        <li>• Lista de medicamentos atuais</li>
                    </ul>
                </div>
            </div>
           
            <div class="mt-6 flex space-x-3">
                <button onclick="closeModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                    Fechar
                </button>
                <button onclick="rescheduleAppointment()" class="flex-1 primary-button text-white font-medium py-2 px-4 rounded-lg">
                    Reagendar
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        const months = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        function generateCalendar() {
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
           
            document.getElementById('currentMonth').textContent = `${months[currentMonth]} ${currentYear}`;
           
            const calendarDays = document.getElementById('calendarDays');
            calendarDays.innerHTML = '';
           
            // Dias vazios do mês anterior
            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'h-12';
                calendarDays.appendChild(emptyDay);
            }
           
            // Dias do mês atual
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day h-12 flex items-center justify-center text-sm font-medium cursor-pointer rounded-lg hover:bg-gray-100';
                dayElement.textContent = day;
               
                // Destacar o dia atual
                if (day === currentDate.getDate() && currentMonth === currentDate.getMonth() && currentYear === currentDate.getFullYear()) {
                    dayElement.className += ' today';
                }
               
                // Adicionar consulta no dia 15
                if (day === 15 && currentMonth === 0 && currentYear === 2024) {
                    dayElement.className += ' appointment-day';
                    dayElement.innerHTML = `${day}<div class="w-2 h-2 bg-white rounded-full mt-1"></div>`;
                }
               
                dayElement.onclick = () => selectDay(day);
                calendarDays.appendChild(dayElement);
            }
        }

        function selectDay(day) {
            if (day === 15 && currentMonth === 0 && currentYear === 2024) {
                showAppointmentDetails();
            } else {
                alert(`Você selecionou o dia ${day} de ${months[currentMonth]}`);
            }
        }

        function previousMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar();
        }

        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar();
        }

        function showAppointmentDetails() {
            document.getElementById('appointmentModal').classList.remove('hidden');
            document.getElementById('appointmentModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
            document.getElementById('appointmentModal').classList.remove('flex');
        }

        function rescheduleAppointment() {
            alert('Funcionalidade de reagendamento será implementada em breve!');
            closeModal();
        }

        function scheduleNewAppointment() {
            alert('Redirecionando para o sistema de agendamento...');
        }

        function viewAllAppointments() {
            alert('Mostrando todas as consultas agendadas...');
        }

        function exportCalendar() {
            alert('Exportando calendário para seu dispositivo...');
        }

        // Inicializar o calendário
        generateCalendar();

        // Fechar modal ao clicar fora dele
        document.getElementById('appointmentModal').onclick = function(e) {
            if (e.target === this) {
                closeModal();
            }
        }
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'973208a844bbf1c1',t:'MTc1NTg2MjE4MS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>