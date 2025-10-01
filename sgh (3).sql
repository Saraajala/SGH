-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/10/2025 às 18:44
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sgh`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `consultas`
--

CREATE TABLE `consultas` (
  `id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `medico_id` int(10) UNSIGNED NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('agendada','realizada','cancelada','nao_compareceu') DEFAULT 'agendada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `consultas`
--

INSERT INTO `consultas` (`id`, `paciente_id`, `medico_id`, `data`, `hora`, `criado_em`, `status`) VALUES
(1, 3, 1, '2025-09-20', '15:00:00', '2025-09-19 15:41:45', 'realizada'),
(2, 3, 4, '2025-09-25', '09:30:00', '2025-09-24 11:33:07', 'agendada'),
(3, 5, 4, '2025-09-25', '11:00:00', '2025-09-24 11:51:58', 'agendada'),
(4, 5, 1, '2025-09-26', '14:40:00', '2025-09-24 16:37:27', 'realizada'),
(5, 6, 1, '2025-10-02', '09:00:00', '2025-10-01 11:53:13', 'realizada'),
(6, 3, 1, '2025-10-02', '13:30:00', '2025-10-01 14:48:41', 'realizada'),
(7, 5, 1, '2025-10-01', '16:00:00', '2025-10-01 16:03:45', 'nao_compareceu'),
(8, 3, 1, '2025-10-01', '13:30:00', '2025-10-01 16:34:44', 'realizada'),
(9, 5, 1, '2025-10-01', '13:40:00', '2025-10-01 16:37:08', 'agendada');

-- --------------------------------------------------------

--
-- Estrutura para tabela `dispensacoes`
--

CREATE TABLE `dispensacoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `cadastrado_por` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `medicamento_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `dispensacoes`
--

INSERT INTO `dispensacoes` (`id`, `nome`, `descricao`, `quantidade`, `cadastrado_por`, `data_cadastro`, `medicamento_id`, `paciente_id`) VALUES
(1, 'PARACETAMOL', 'para febre e dor de cabeça', 1, 7, '2025-10-01 11:22:30', 1, 3),
(2, 'PARACETAMOL', 'para febre e dor de cabeça', 1, 7, '2025-10-01 11:26:37', 1, 5),
(3, 'nimesulida', 'anti-inflamatorio', 1, 7, '2025-10-01 11:27:37', 2, 6),
(4, 'dipirona', 'febre e dor de cabeça', 4, 7, '2025-10-01 13:40:34', 3, 5),
(5, 'polaramine', 'alergia', 5, 2, '2025-10-01 13:43:03', 4, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `evolucoes`
--

CREATE TABLE `evolucoes` (
  `id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `medico_id` int(10) UNSIGNED NOT NULL,
  `descricao` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `evolucoes`
--

INSERT INTO `evolucoes` (`id`, `paciente_id`, `medico_id`, `descricao`, `data`) VALUES
(6, 5, 4, 'ertryu', '2025-09-24 12:48:53'),
(7, 3, 1, '4r5ty', '2025-09-24 16:37:56'),
(8, 3, 1, 'wsdfghjk', '2025-10-01 11:24:27'),
(10, 6, 1, '4wetryuiu', '2025-10-01 16:35:34');

-- --------------------------------------------------------

--
-- Estrutura para tabela `exames`
--

CREATE TABLE `exames` (
  `id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `medico_id` int(10) UNSIGNED NOT NULL,
  `exame_id` int(10) UNSIGNED NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `exames`
--

INSERT INTO `exames` (`id`, `paciente_id`, `medico_id`, `exame_id`, `data`) VALUES
(1, 5, 4, 11, '2025-09-24 12:53:53'),
(2, 3, 1, 6, '2025-09-24 16:38:26'),
(4, 3, 1, 15, '2025-10-01 16:02:50'),
(5, 6, 1, 15, '2025-10-01 16:35:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `exames_cadastrados`
--

CREATE TABLE `exames_cadastrados` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `exames_cadastrados`
--

INSERT INTO `exames_cadastrados` (`id`, `nome`) VALUES
(1, 'Hemograma Completo'),
(2, 'Glicemia em Jejum'),
(3, 'Creatinina'),
(4, 'Ureia'),
(5, 'Colesterol Total'),
(6, 'Triglicerídeos'),
(7, 'TGO / AST'),
(8, 'TGP / ALT'),
(9, 'Eletrocardiograma (ECG)'),
(10, 'Radiografia de Tórax'),
(11, 'Ultrassonografia Abdominal'),
(12, 'Tomografia Computadorizada (TC)'),
(13, 'Ressonância Magnética (RM)'),
(14, 'PSA (Antígeno Prostático)'),
(15, 'TSH (Hormônio Estimulante da Tireoide)'),
(16, 'T4 Livre'),
(17, 'Hemoglobina Glicada (HbA1c)'),
(18, 'Urina Tipo I (EAS)'),
(19, 'Cultura de Urina'),
(20, 'Sorologia Hepatite B');

-- --------------------------------------------------------

--
-- Estrutura para tabela `internacoes`
--

CREATE TABLE `internacoes` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `quarto_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `data_entrada` datetime NOT NULL,
  `data_alta` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `internacoes`
--

INSERT INTO `internacoes` (`id`, `paciente_id`, `quarto_id`, `medico_id`, `data_entrada`, `data_alta`, `status`) VALUES
(1, 3, 2, 1, '2025-10-01 08:20:56', '2025-10-01 08:21:14', 'alta'),
(2, 5, 3, 1, '2025-10-01 08:22:19', '2025-10-01 08:22:24', 'alta'),
(3, 3, 2, 1, '2025-10-01 08:59:40', '2025-10-01 08:59:47', 'alta'),
(4, 3, 1, 2, '2025-10-01 09:04:36', '2025-10-01 09:04:40', 'alta'),
(5, 3, 1, 7, '2025-10-01 11:29:00', '2025-10-01 11:29:03', 'alta'),
(6, 6, 1, 1, '2025-10-01 13:03:01', '2025-10-01 13:35:55', 'alta'),
(7, 3, 2, 1, '2025-10-01 13:36:01', '2025-10-01 13:39:58', 'alta');

-- --------------------------------------------------------

--
-- Estrutura para tabela `medicamentos`
--

CREATE TABLE `medicamentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `cadastrado_por` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `medicamentos`
--

INSERT INTO `medicamentos` (`id`, `nome`, `descricao`, `quantidade`, `cadastrado_por`, `data_cadastro`) VALUES
(1, 'PARACETAMOL', 'para febre e dor de cabeça', 3, 7, '2025-10-01 13:46:48'),
(2, 'nimesulida', 'anti-inflamatorio', 9, 7, '2025-10-01 14:27:22'),
(3, 'dipirona', 'febre e dor de cabeça', 16, 7, '2025-10-01 16:40:24'),
(4, 'polaramine', 'alergia', 5, 2, '2025-10-01 16:42:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `mensagem` text NOT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id`, `usuario_id`, `tipo`, `mensagem`, `lida`, `data`) VALUES
(1, 3, 'consulta', 'Consulta agendada para 2025-09-20 às 15:00 com médico sara', 1, '2025-09-19 15:41:45'),
(2, 1, 'consulta', 'Consulta agendada para 2025-09-20 às 15:00 com paciente karen', 1, '2025-09-19 15:41:45'),
(3, 3, 'consulta', 'Consulta agendada para 2025-09-25 às 09:30 com médico maria', 1, '2025-09-24 11:33:07'),
(4, 4, 'consulta', 'Consulta agendada para 2025-09-25 às 09:30 com paciente karen', 0, '2025-09-24 11:33:07'),
(5, 5, 'consulta', 'Consulta agendada para 2025-09-25 às 11:00 com médico maria', 1, '2025-09-24 11:51:58'),
(6, 4, 'consulta', 'Consulta agendada para 2025-09-25 às 11:00 com paciente luis', 0, '2025-09-24 11:51:58'),
(7, 3, 'internacao', 'Você foi internado no quarto 1', 1, '2025-09-24 13:04:07'),
(8, 4, 'internacao', 'Paciente ID 3 internado no quarto 1', 0, '2025-09-24 13:04:07'),
(9, 3, 'internacao', 'Você foi internado no quarto 2', 1, '2025-09-24 13:11:04'),
(10, 4, 'internacao', 'Paciente ID 3 internado no quarto 2', 0, '2025-09-24 13:11:04'),
(11, 5, 'internacao', 'Você foi internado no quarto 3', 1, '2025-09-24 13:17:15'),
(12, 4, 'internacao', 'Paciente ID 5 internado no quarto 3', 0, '2025-09-24 13:17:15'),
(13, 3, 'internacao', 'Você foi internado no quarto 4', 1, '2025-09-24 13:18:14'),
(14, 4, 'internacao', 'Paciente ID 3 internado no quarto 4', 0, '2025-09-24 13:18:14'),
(15, 5, 'internacao', 'Você foi internado no quarto 1', 1, '2025-09-24 13:32:46'),
(16, 4, 'internacao', 'Paciente ID 5 internado no quarto 1', 0, '2025-09-24 13:32:46'),
(17, 5, 'internacao', 'Você foi internado no quarto 1', 1, '2025-09-24 13:33:42'),
(18, 4, 'internacao', 'Paciente ID 5 internado no quarto 1', 0, '2025-09-24 13:33:42'),
(19, 4, 'alta', 'Paciente ID 3 teve alta ou transferência', 0, '2025-09-24 14:02:32'),
(20, 4, 'alta', 'Paciente ID 3 teve alta ou transferência', 0, '2025-09-24 14:03:26'),
(21, 5, 'consulta', 'Consulta agendada para 2025-09-26 às 14:40 com médico sara', 1, '2025-09-24 16:37:27'),
(22, 1, 'consulta', 'Consulta agendada para 2025-09-26 às 14:40 com paciente luis', 1, '2025-09-24 16:37:27'),
(23, 6, 'consulta', 'Consulta agendada para 2025-10-02 às 09:00 com médico sara', 1, '2025-10-01 11:53:13'),
(24, 1, 'consulta', 'Consulta agendada para 2025-10-02 às 09:00 com paciente Rita', 1, '2025-10-01 11:53:13'),
(25, 3, 'farmacia', 'Medicamento dispensado: PARACETAMOL, quantidade: 1', 1, '2025-10-01 14:22:30'),
(26, 5, 'farmacia', 'Medicamento dispensado: PARACETAMOL, quantidade: 1', 1, '2025-10-01 14:26:37'),
(27, 6, 'farmacia', 'Medicamento dispensado: nimesulida, quantidade: 1', 1, '2025-10-01 14:27:37'),
(28, 3, 'consulta', 'Consulta agendada para 2025-10-02 às 13:30 com médico sara', 1, '2025-10-01 14:48:41'),
(29, 1, 'consulta', 'Consulta agendada para 2025-10-02 às 13:30 com paciente karen', 1, '2025-10-01 14:48:41'),
(30, 5, 'consulta', 'Consulta agendada para 2025-10-01 às 16:00 com médico sara', 1, '2025-10-01 16:03:46'),
(31, 1, 'consulta', 'Consulta agendada para 2025-10-01 às 16:00 com paciente luis', 1, '2025-10-01 16:03:46'),
(32, 3, 'consulta', 'Consulta agendada para 2025-10-01 às 13:30 com médico sara', 1, '2025-10-01 16:34:44'),
(33, 1, 'consulta', 'Consulta agendada para 2025-10-01 às 13:30 com paciente karen', 1, '2025-10-01 16:34:44'),
(34, 3, 'consulta', '✅ Sua consulta de 01/10/2025 às 13:30 foi marcada como REALIZADA', 1, '2025-10-01 16:35:14'),
(35, 6, 'prontuario', '???? Nova evolução registrada no seu prontuário', 1, '2025-10-01 16:35:34'),
(36, 6, 'prontuario', '???? Nova prescrição médica registrada no seu prontuário', 1, '2025-10-01 16:35:37'),
(37, 6, 'prontuario', '???? Novo procedimento registrado no seu prontuário', 1, '2025-10-01 16:35:40'),
(38, 6, 'prontuario', '???? Novo exame solicitado no seu prontuário', 1, '2025-10-01 16:35:45'),
(39, 6, 'internacao', '???? Você recebeu alta médica! Cuide-se bem!', 1, '2025-10-01 16:35:55'),
(40, 3, 'internacao', '???? Você foi internado no hospital. Desejamos uma rápida recuperação!', 1, '2025-10-01 16:36:01'),
(41, 5, 'consulta', 'Consulta agendada para 2025-10-01 às 13:40 com médico sara', 1, '2025-10-01 16:37:08'),
(42, 1, 'consulta', 'Consulta agendada para 2025-10-01 às 13:40 com paciente luis', 0, '2025-10-01 16:37:08'),
(43, 3, 'internacao', '???? Você recebeu alta médica! Cuide-se bem!', 1, '2025-10-01 16:39:58'),
(44, 5, 'farmacia', 'Medicamento dispensado: dipirona, quantidade: 4', 1, '2025-10-01 16:40:34'),
(45, 6, 'farmacia', 'Medicamento dispensado: polaramine, quantidade: 5', 1, '2025-10-01 16:43:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `prescricoes`
--

CREATE TABLE `prescricoes` (
  `id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `medico_id` int(10) UNSIGNED NOT NULL,
  `descricao` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `prescricoes`
--

INSERT INTO `prescricoes` (`id`, `paciente_id`, `medico_id`, `descricao`, `data`) VALUES
(3, 5, 4, 'wef', '2025-09-24 12:49:10'),
(4, 3, 1, '345rty', '2025-09-24 16:38:01'),
(7, 6, 1, '3456', '2025-10-01 16:35:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `procedimentos`
--

CREATE TABLE `procedimentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `medico_id` int(11) DEFAULT NULL,
  `descricao` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `procedimentos`
--

INSERT INTO `procedimentos` (`id`, `paciente_id`, `medico_id`, `descricao`, `data`) VALUES
(1, 5, 4, 'ewrdf', '2025-09-24 12:49:14'),
(2, 3, 1, '23456', '2025-09-24 16:38:05'),
(4, 6, 1, 'e5rtughk', '2025-10-01 16:35:40');

-- --------------------------------------------------------

--
-- Estrutura para tabela `quartos`
--

CREATE TABLE `quartos` (
  `id` int(10) UNSIGNED NOT NULL,
  `numero` varchar(10) NOT NULL,
  `ala` varchar(50) NOT NULL,
  `status` enum('disponivel','ocupado','em_limpeza') DEFAULT 'disponivel'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `quartos`
--

INSERT INTO `quartos` (`id`, `numero`, `ala`, `status`) VALUES
(1, '101', 'A', ''),
(2, '102', 'A', ''),
(3, '103', 'A', ''),
(4, '201', 'B', 'disponivel'),
(5, '202', 'B', 'disponivel'),
(6, '203', 'B', 'disponivel'),
(7, '301', 'C', 'disponivel'),
(8, '302', 'C', 'disponivel');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('administrador','medico','enfermeiro','paciente') NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `perfil`, `criado_em`) VALUES
(1, 'sara', 'sara.ajalasiva24@gmail.com', '$2y$10$QtL3XAs8f.q3Rr7Aj46YWeglIZhb83NX.m5/iW6Dt0f8fxvk252Qm', 'medico', '2025-09-19 15:40:55'),
(2, 'anna', 'anna.goncalves@gmail.com', '$2y$10$wejIYUviHND6/e6ddAvLW.Xv026xUp8qIEpdyTLIuxKWqTCe6cK8y', 'enfermeiro', '2025-09-19 15:41:11'),
(3, 'karen', 'karen.santana@gmail.com', '$2y$10$u1cIyTM05anMKbHPmjNqX.rIWv7OUQO3FpSKsTkIp8DTWVeAQOYHu', 'paciente', '2025-09-19 15:41:21'),
(4, 'maria', 'maria.maria@gmail.com', '$2y$10$rR4Ezu7PHOuQZ46bhFs.COFcXQY8CSqvA3UIlwWww1qGw4kG4akFm', 'medico', '2025-09-24 11:29:26'),
(5, 'luis', 'luis.felipe@gmail.com', '$2y$10$AdVb8nmNGsgc1bHvgelDW.ycWVH/z2MAMOP7N3/jtCk.Mz9FzH3vW', 'paciente', '2025-09-24 11:36:26'),
(6, 'Rita', 'rita.rita@gmail.com', '$2y$10$uqumI0RPAeM0IGO57otCZeYpLZueg0IYsCaVpACCMQVAnfAb2vGu2', 'paciente', '2025-10-01 11:52:21'),
(7, 'beto', 'beto.beto@gmail.com', '$2y$10$dQUqaXYaSOay.QzpPtussOgB09AYaCammMUTByKbg3N/i3Q2QqO.6', 'enfermeiro', '2025-10-01 13:25:15');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Índices de tabela `dispensacoes`
--
ALTER TABLE `dispensacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dispensacao_medicamento` (`medicamento_id`);

--
-- Índices de tabela `evolucoes`
--
ALTER TABLE `evolucoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Índices de tabela `exames`
--
ALTER TABLE `exames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `medico_id` (`medico_id`),
  ADD KEY `exame_id` (`exame_id`);

--
-- Índices de tabela `exames_cadastrados`
--
ALTER TABLE `exames_cadastrados`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `internacoes`
--
ALTER TABLE `internacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `prescricoes`
--
ALTER TABLE `prescricoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Índices de tabela `procedimentos`
--
ALTER TABLE `procedimentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`);

--
-- Índices de tabela `quartos`
--
ALTER TABLE `quartos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `dispensacoes`
--
ALTER TABLE `dispensacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `evolucoes`
--
ALTER TABLE `evolucoes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `exames`
--
ALTER TABLE `exames`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `exames_cadastrados`
--
ALTER TABLE `exames_cadastrados`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `internacoes`
--
ALTER TABLE `internacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `medicamentos`
--
ALTER TABLE `medicamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `prescricoes`
--
ALTER TABLE `prescricoes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `procedimentos`
--
ALTER TABLE `procedimentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `quartos`
--
ALTER TABLE `quartos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`medico_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `dispensacoes`
--
ALTER TABLE `dispensacoes`
  ADD CONSTRAINT `fk_dispensacao_medicamento` FOREIGN KEY (`medicamento_id`) REFERENCES `medicamentos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `evolucoes`
--
ALTER TABLE `evolucoes`
  ADD CONSTRAINT `evolucoes_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evolucoes_ibfk_2` FOREIGN KEY (`medico_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `exames`
--
ALTER TABLE `exames`
  ADD CONSTRAINT `exames_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exames_ibfk_2` FOREIGN KEY (`medico_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exames_ibfk_3` FOREIGN KEY (`exame_id`) REFERENCES `exames_cadastrados` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `prescricoes`
--
ALTER TABLE `prescricoes`
  ADD CONSTRAINT `prescricoes_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescricoes_ibfk_2` FOREIGN KEY (`medico_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `procedimentos`
--
ALTER TABLE `procedimentos`
  ADD CONSTRAINT `procedimentos_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
