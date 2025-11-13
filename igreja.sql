-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13/11/2025 às 13:55
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
-- Banco de dados: `igreja`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos`
--

CREATE TABLE `eventos` (
  `id` int(10) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `local` varchar(255) DEFAULT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime DEFAULT NULL,
  `status` enum('pendente','em_andamento','concluido','cancelado') NOT NULL DEFAULT 'pendente',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_contato`
--

CREATE TABLE `mensagens_contato` (
  `id` int(10) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `assunto` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `midia`
--

CREATE TABLE `midia` (
  `id` int(10) NOT NULL,
  `caminho_arquivo` varchar(255) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `tipo_mime` varchar(255) NOT NULL,
  `tipo_arquivo` enum('imagem','video','audio','documento') NOT NULL,
  `tamanho` int(10) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `midia_eventos`
--

CREATE TABLE `midia_eventos` (
  `id` int(10) NOT NULL,
  `midia_id` int(10) NOT NULL,
  `evento_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `midia_publicacoes`
--

CREATE TABLE `midia_publicacoes` (
  `id` int(10) NOT NULL,
  `midia_id` int(10) NOT NULL,
  `publicacao_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `midia_sermoes`
--

CREATE TABLE `midia_sermoes` (
  `id` int(10) NOT NULL,
  `midia_id` int(10) NOT NULL,
  `sermao_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `publicacoes`
--

CREATE TABLE `publicacoes` (
  `id` int(10) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `categoria` enum('noticia','aviso','blog') NOT NULL DEFAULT 'blog',
  `status` enum('rascunho','publicado') NOT NULL DEFAULT 'rascunho',
  `publicado_em` datetime DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sermoes`
--

CREATE TABLE `sermoes` (
  `id` varchar(50) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `conteudo` text DEFAULT NULL,
  `pregador` varchar(255) DEFAULT NULL,
  `data` date NOT NULL,
  `status` enum('rascunho','publicado') NOT NULL DEFAULT 'rascunho',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `papel` enum('admin','editor') NOT NULL DEFAULT 'editor',
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `papel`, `status`, `criado_em`, `atualizado_em`, `reset_token`, `reset_expires`) VALUES
(1, 'Deilton', 'deilton@gmail.com', '$2y$10$ArV6Bg6OfXl3iM05A2sZKuOVr62TLtRWTrchlbAEzxESPa6pJkyo2', 'admin', 'ativo', '2025-11-13 09:08:11', '2025-11-13 14:44:19', NULL, NULL),
(2, 'Matusse', 'matusse@gmail.com', '$2y$10$k3fbOF14ZLOzeG4ANo6KbunJZNexlhkWcxLGDqWy4bfttCBaxHyeK', 'editor', 'ativo', '2025-11-13 09:32:25', '2025-11-13 12:04:44', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `mensagens_contato`
--
ALTER TABLE `mensagens_contato`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `midia`
--
ALTER TABLE `midia`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `midia_eventos`
--
ALTER TABLE `midia_eventos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `midia_id` (`midia_id`,`evento_id`),
  ADD KEY `evento_id` (`evento_id`);

--
-- Índices de tabela `midia_publicacoes`
--
ALTER TABLE `midia_publicacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `midia_id` (`midia_id`,`publicacao_id`),
  ADD KEY `publicacao_id` (`publicacao_id`);

--
-- Índices de tabela `midia_sermoes`
--
ALTER TABLE `midia_sermoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `midia_id` (`midia_id`,`sermao_id`),
  ADD KEY `sermao_id` (`sermao_id`);

--
-- Índices de tabela `publicacoes`
--
ALTER TABLE `publicacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Índices de tabela `sermoes`
--
ALTER TABLE `sermoes`
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
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens_contato`
--
ALTER TABLE `mensagens_contato`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `midia`
--
ALTER TABLE `midia`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `midia_eventos`
--
ALTER TABLE `midia_eventos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `midia_publicacoes`
--
ALTER TABLE `midia_publicacoes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `midia_sermoes`
--
ALTER TABLE `midia_sermoes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `publicacoes`
--
ALTER TABLE `publicacoes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `midia_eventos`
--
ALTER TABLE `midia_eventos`
  ADD CONSTRAINT `midia_eventos_ibfk_1` FOREIGN KEY (`midia_id`) REFERENCES `midia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `midia_eventos_ibfk_2` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `midia_publicacoes`
--
ALTER TABLE `midia_publicacoes`
  ADD CONSTRAINT `midia_publicacoes_ibfk_1` FOREIGN KEY (`midia_id`) REFERENCES `midia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `midia_publicacoes_ibfk_2` FOREIGN KEY (`publicacao_id`) REFERENCES `publicacoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `midia_sermoes`
--
ALTER TABLE `midia_sermoes`
  ADD CONSTRAINT `midia_sermoes_ibfk_1` FOREIGN KEY (`midia_id`) REFERENCES `midia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `midia_sermoes_ibfk_2` FOREIGN KEY (`sermao_id`) REFERENCES `sermoes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
