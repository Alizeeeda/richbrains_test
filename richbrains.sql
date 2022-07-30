-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 30 2022 г., 22:08
-- Версия сервера: 8.0.24
-- Версия PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `richbrains`
--

-- --------------------------------------------------------

--
-- Структура таблицы `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220730125138', '2022-07-30 15:53:04', 184);

-- --------------------------------------------------------

--
-- Структура таблицы `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`, `phone`) VALUES
(10, 'daryazezina@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$OiQbXf9vCv36B9EaIyo4SOyD2AOGW5Z3srFSMpCW/pGxzTutXBmPC', 'Daria', 'Zezina', '+375256721616'),
(11, 'test_user3@gmail.com', '[\"ROLE_USER\"]', '1233456Dd', 'Test', 'User', '+375298888888'),
(27, 'daryazezsina@gmail.com', '[\"ROLE_ADMIN\"]', '123', 'Daria', 'Zezina', '+375256721616'),
(31, 'daryazezsinssa@gmail.com', '[]', '$2y$13$Zq5fi6lWZdQmwJaxD6mj6uK2Vj7qGcKKTQhbI15k4fD/ah0mFFb2q', 'Daria', 'Test', '+375256721616'),
(32, 'daryazsezsinssa@gmail.com', '[\"ROLE_USER\"]', '$2y$13$b6f3OnmTtQHqHNas9vdPTeRalJb8.FU/jtumJrKKqeHhRP6fFRmGy', 'Daria', 'Test', '+375256721616'),
(33, '5daryazsezsinssa@gmail.com', '[\"ROLE_USER\"]', '$2y$13$n/1Qg5JeAC.wKCvdb4Y65.BxHRkwBiaPIrvpTOqhv/c.o6TRgYUAi', 'Daria', 'Test', '+375256721616'),
(34, 'test@bk.ru', '[\"ROLE_USER\"]', '$2y$13$p150J9HhvGbO6bE1puOf4OigYis6yFubvvzfLZopyvbeo9pCZ9UpS', 'df', 'fd', '+375266666666'),
(35, 'test_usver03@gmail.com', '[\"ROLE_USER\"]', '$2y$13$dDFETewCd0FV1qi5vLzgDuiCmNvtKzZTynFPGF/l/y4fOmeKT9HWC', 'dff', 'dfjk', '+375256725555');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
