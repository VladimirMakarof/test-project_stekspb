-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 23 2023 г., 00:05
-- Версия сервера: 8.0.32
-- Версия PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `gallery_app`
--

-- --------------------------------------------------------

--
-- Структура таблицы `galleries`
--

CREATE TABLE `galleries` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `preview_image_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `galleries`
--

INSERT INTO `galleries` (`id`, `name`, `preview_image_id`) VALUES
(222, 'Новая галерея ', 44),
(243, 'новая галерея', 44),
(244, '7', 44),
(245, '1й', 44),
(246, 'zz', 44),
(247, '55565', 43),
(248, '666', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL,
  `gallery_id` int NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`id`, `gallery_id`, `filename`) VALUES
(43, 245, '653536f95a835_B0536.jpg'),
(44, 245, '6535379a324ed_B0536.jpg'),
(45, 247, '65353b35a6769_8b07c4f76d361e459010d7bb1513000c.jpg'),
(46, 222, '65355ebd009aa_B0536.jpg'),
(47, 222, '65355ec1ea5d5_97327-ekosistema-nebo-derevo-pustynya-les-7680x4320.jpg'),
(48, 222, '65355ec6f0674_1.jpg'),
(49, 222, '65355ecb6300f_1372338870_loran-parsele-21.jpg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_gallery_preview_image` (`preview_image_id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_id` (`gallery_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `FK_gallery_preview_image` FOREIGN KEY (`preview_image_id`) REFERENCES `images` (`id`);

--
-- Ограничения внешнего ключа таблицы `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
