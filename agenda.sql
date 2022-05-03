-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18-Nov-2021 às 02:18
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `agenda`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `conquests`
--

CREATE TABLE `conquests` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL DEFAULT 0,
  `one_day` int(11) DEFAULT 0,
  `two_day` int(11) DEFAULT 0,
  `three_day` int(11) DEFAULT 0,
  `four_day` int(11) DEFAULT 0,
  `five_day` int(11) DEFAULT 0,
  `six_day` int(11) DEFAULT 0,
  `one_week` int(11) DEFAULT 0,
  `two_week` int(11) DEFAULT 0,
  `three_week` int(11) DEFAULT 0,
  `one_month` int(11) DEFAULT 0,
  `two_month` int(11) DEFAULT 0,
  `three_month` int(11) DEFAULT 0,
  `four_month` int(11) DEFAULT 0,
  `five_month` int(11) DEFAULT 0,
  `six_month` int(11) DEFAULT 0,
  `seven_month` int(11) DEFAULT 0,
  `eight_month` int(11) DEFAULT 0,
  `nine_month` int(11) DEFAULT 0,
  `ten_month` int(11) DEFAULT 0,
  `eleven_month` int(11) DEFAULT 0,
  `one_year` int(11) DEFAULT 0,
  `sequence_zero` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `conquests`
--

INSERT INTO `conquests` (`id`, `idUser`, `one_day`, `two_day`, `three_day`, `four_day`, `five_day`, `six_day`, `one_week`, `two_week`, `three_week`, `one_month`, `two_month`, `three_month`, `four_month`, `five_month`, `six_month`, `seven_month`, `eight_month`, `nine_month`, `ten_month`, `eleven_month`, `one_year`, `sequence_zero`) VALUES
(1, 1, -13, 3, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `objectives`
--

CREATE TABLE `objectives` (
  `id` int(11) NOT NULL,
  `title` varchar(450) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1-Importante; 2-Muito Importante;3-Razoavel',
  `level` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1-Semanal;2-Mensal;3-Anual',
  `id_user` int(11) NOT NULL DEFAULT 0,
  `done` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `objectives`
--

INSERT INTO `objectives` (`id`, `title`, `type`, `level`, `id_user`, `done`) VALUES
(1, 'teste', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL DEFAULT '0',
  `start` time NOT NULL DEFAULT '00:00:00',
  `end` time NOT NULL DEFAULT '00:00:00',
  `date` date NOT NULL,
  `importance` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1-Importante;2-Muito Importante;;3-Razoavel; 4-Pouca Importancia\r\n',
  `description` varchar(100) NOT NULL DEFAULT '',
  `icon` varchar(50) DEFAULT '0',
  `idUser` int(11) NOT NULL,
  `selected` tinyint(4) NOT NULL DEFAULT 0,
  `task_path` varchar(450) NOT NULL DEFAULT '0',
  `task_img` varchar(50) NOT NULL DEFAULT '0',
  `days_repeat` varchar(15) DEFAULT NULL,
  `date_repeat` date DEFAULT NULL,
  `idTaskRepeat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `start`, `end`, `date`, `importance`, `description`, `icon`, `idUser`, `selected`, `task_path`, `task_img`, `days_repeat`, `date_repeat`, `idTaskRepeat`) VALUES
(1, 'teste', '22:45:00', '23:45:00', '2021-07-03', 1, 'safdsfsadasd', '0', 1, 1, '', '', NULL, NULL, NULL),
(2, 'teste', '20:05:00', '21:05:00', '2021-07-04', 1, 'dsfsfsfsfsfsfsfsfsfsfsfsfsf', '0', 1, 0, '', '', NULL, NULL, NULL),
(3, 'teste', '20:05:00', '21:05:00', '2021-07-05', 1, 'dsfsfsfsfsfsfsfsfsfsfsfsfsf', '0', 1, 0, '', '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `task_repeats`
--

CREATE TABLE `task_repeats` (
  `id` int(11) NOT NULL,
  `id_task` int(11) DEFAULT NULL,
  `monday` tinyint(4) DEFAULT 0,
  `tuesday` tinyint(4) DEFAULT 0,
  `wednesday` tinyint(4) DEFAULT 0,
  `thursday` tinyint(4) DEFAULT 0,
  `friday` tinyint(4) DEFAULT 0,
  `saturday` tinyint(4) DEFAULT 0,
  `sunday` tinyint(4) DEFAULT 0,
  `everyday` tinyint(4) DEFAULT 0,
  `last_register` date DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profileImg` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imgName` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idFacebook` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idGoogle` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `passRememberToken` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `lastName`, `login`, `email`, `email_verified_at`, `password`, `profileImg`, `imgName`, `idFacebook`, `idGoogle`, `sequence`, `passRememberToken`) VALUES
(1, 'Mauricio', 'Ferreira', 'MauriLP', 'mauricio-ferreira2015@outlook.com', NULL, '$2y$10$6Ca1y/a4ThKHIweDVi84TeBwBQPD36NuLuCZgzgiQ1ly1hUAdg37y', '', '', NULL, NULL, 0, ''),
(2, 'Mauricio Ferreira', NULL, 'MauriFace', 'mauriciolinkinpark13@hotmail.com', NULL, '$2y$10$6Ca1y/a4ThKHIweDVi84TeBwBQPD36NuLuCZgzgiQ1ly1hUAdg37y', NULL, NULL, '3820883184700364', NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `conquests`
--
ALTER TABLE `conquests`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `objectives`
--
ALTER TABLE `objectives`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices para tabela `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `task_repeats`
--
ALTER TABLE `task_repeats`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `conquests`
--
ALTER TABLE `conquests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `objectives`
--
ALTER TABLE `objectives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `task_repeats`
--
ALTER TABLE `task_repeats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
