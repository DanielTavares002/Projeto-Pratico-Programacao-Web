-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/11/2025 às 18:07
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
-- Banco de dados: `schema`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefas`
--

CREATE TABLE `tarefas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `status` enum('pendente','concluida','excluida') DEFAULT 'pendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NULL DEFAULT NULL,
  `prioridade` enum('baixa','media','alta') DEFAULT 'media'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefas`
--

INSERT INTO `tarefas` (`id`, `usuario_id`, `titulo`, `descricao`, `vencimento`, `status`, `criado_em`, `atualizado_em`, `prioridade`) VALUES
(1, 1, 'Fazer compras', 'comprar labubu,\r\nbobbie goods,\r\nfigurinha da copa,\r\nmamadeira de leite coreano,\r\nleite do saco', '1212-12-12', 'excluida', '2025-11-24 01:21:16', '2025-11-24 01:21:30', 'media'),
(2, 1, 'Compras compras compras.. compras', 'fazer compras', '0000-00-00', 'excluida', '2025-11-24 01:48:39', '2025-11-24 01:48:55', 'alta'),
(3, 1, 'Fazer compras', 'Carne\r\nAzeitona', '2025-11-24', 'concluida', '2025-11-24 16:46:25', '2025-11-24 16:46:53', 'alta');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `criado_em`) VALUES
(1, 'Daniel Tavares dos Santos', 'danieltavares@gmail.com', '$2y$10$n9EcKrZ67VfRSdBB2gl13ecynKThaHqg9oqALbZ0SHCSFz5ELcINW', '2025-11-24 01:20:03'),
(2, 'Gustavo Pereira', 'gustavopereira@gmail.com', '$2y$10$P3IygBU0XuwB3yDds/5YJuBJX7UxBo6WX8tYmnF/KqGd9Kydn8CN6', '2025-11-24 16:55:12');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tarefas`
--
ALTER TABLE `tarefas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

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
-- AUTO_INCREMENT de tabela `tarefas`
--
ALTER TABLE `tarefas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tarefas`
--
ALTER TABLE `tarefas`
  ADD CONSTRAINT `tarefas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
