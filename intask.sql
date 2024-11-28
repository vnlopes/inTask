-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Nov-2024 às 14:21
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `intask`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `datan` varchar(12) NOT NULL DEFAULT date_format(curdate(),'%d %b, %Y'),
  `priority` enum('Urgente','Deboa','Atenção') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `title`, `content`, `datan`, `priority`) VALUES
(97, 1, 'Reunião de Trabalho', 'Revisar o relatório do projeto\nDiscutir prazos com a equipe\nAjustar orçamento', '28 Nov, 2024', 'Deboa'),
(98, 1, 'Estudo de Programação', 'Revisar conceitos de SQL\nPraticar Python\nFazer exercícios sobre APIs', '28 Nov, 2024', 'Atenção'),
(99, 1, 'Limpeza da Casa', 'Varrer a sala\nLavar a louça\nPassar pano no banheiro', '28 Nov, 2024', 'Urgente'),
(100, 1, 'Compra de Presentes', 'Escolher presente para o amigo secreto\nComprar embrulho\nVerificar envio', '28 Nov, 2024', 'Deboa'),
(101, 1, 'Consulta Médica', 'Marcar horário com o dentista\nLevar exames para revisão', '28 Nov, 2024', 'Urgente'),
(102, 1, 'Compra de Roupas', 'Comprar camisa nova\nVerificar descontos em sapatos', '28 Nov, 2024', 'Atenção'),
(103, 1, 'Organizar Escritório', 'Arrumar mesa\nArquivar documentos antigos\nLimpar computador', '28 Nov, 2024', 'Deboa'),
(104, 1, 'Treino na Academia', 'Fazer cardio\nTreinar pernas\nAlongar após o treino', '28 Nov, 2024', 'Atenção'),
(105, 1, 'Revisar Orçamento Familiar', 'Verificar despesas do mês\nPlanejar compras para o mês seguinte', '28 Nov, 2024', 'Urgente'),
(106, 1, 'Planejamento de Viagem', 'Pesquisar passagens aéreas\nReservar hotel\nFazer checklist para viagem', '28 Nov, 2024', 'Deboa');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `is_admin`, `role`) VALUES
(1, 'vnlopes', '$2y$10$s5v7DChohMGYXBTS12oh8eaJ9WoImPe46TJ17C5qawdIQwNAV8SE6', '2024-11-07 14:16:48', 0, 'user');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_ibfk_1` (`user_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;