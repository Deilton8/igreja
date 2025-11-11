CREATE TABLE `mensagens_contato` (
  `id` int(10) PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `assunto` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `eventos` (
  `id` int(10) PRIMARY KEY AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `local` varchar(255) DEFAULT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime DEFAULT NULL,
  `status` enum(
    'pendente',
    'em_andamento',
    'concluido',
    'cancelado'
  ) NOT NULL DEFAULT 'pendente',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `midia` (
  `id` int(10) PRIMARY KEY AUTO_INCREMENT,
  `caminho_arquivo` varchar(255) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `tipo_mime` varchar(255) NOT NULL,
  `tipo_arquivo` enum('imagem', 'video', 'audio', 'documento') NOT NULL,
  `tamanho` int(10) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `publicacoes` (
  `id` int(10) PRIMARY KEY AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) UNIQUE NOT NULL,
  `conteudo` text NOT NULL,
  `categoria` enum('noticia', 'aviso', 'blog') NOT NULL DEFAULT 'blog',
  `status` enum('rascunho', 'publicado') NOT NULL DEFAULT 'rascunho',
  `publicado_em` datetime DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `sermoes` (
  `id` varchar(50) PRIMARY KEY NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `conteudo` text DEFAULT NULL,
  `pregador` varchar(255) DEFAULT NULL,
  `data` date NOT NULL,
  `status` enum('rascunho', 'publicado') NOT NULL DEFAULT 'rascunho',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `usuarios` (
  `id` int(10) PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `senha` varchar(255) NOT NULL,
  `papel` enum('admin', 'editor') NOT NULL DEFAULT 'editor',
  `status` enum ('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- ========================================================
-- Relação muitos-para-muitos entre mídias e eventos
-- ========================================================
CREATE TABLE `midia_eventos` (
  `id` INT(10) PRIMARY KEY AUTO_INCREMENT,
  `midia_id` INT(10) NOT NULL,
  `evento_id` INT(10) NOT NULL,
  FOREIGN KEY (`midia_id`) REFERENCES `midia`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`evento_id`) REFERENCES `eventos`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY (`midia_id`, `evento_id`) -- evita duplicados
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- ========================================================
-- Relação muitos-para-muitos entre mídias e publicações
-- ========================================================
CREATE TABLE `midia_publicacoes` (
  `id` INT(10) PRIMARY KEY AUTO_INCREMENT,
  `midia_id` INT(10) NOT NULL,
  `publicacao_id` INT(10) NOT NULL,
  FOREIGN KEY (`midia_id`) REFERENCES `midia`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`publicacao_id`) REFERENCES `publicacoes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY (`midia_id`, `publicacao_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- ========================================================
-- Relação muitos-para-muitos entre mídias e sermões
-- ========================================================
CREATE TABLE `midia_sermoes` (
  `id` INT(10) PRIMARY KEY AUTO_INCREMENT,
  `midia_id` INT(10) NOT NULL,
  `sermao_id` VARCHAR(50) NOT NULL,
  FOREIGN KEY (`midia_id`) REFERENCES `midia`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`sermao_id`) REFERENCES `sermoes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY (`midia_id`, `sermao_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;