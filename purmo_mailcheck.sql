-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июл 27 2019 г., 20:34
-- Версия сервера: 5.6.39-83.1
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `purmo_mailcheck`
--

-- --------------------------------------------------------

--
-- Структура таблицы `check`
--

CREATE TABLE IF NOT EXISTS `check` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_subscriber` int(10) UNSIGNED NOT NULL,
  `id_magazine` int(10) UNSIGNED NOT NULL,
  `number` int(10) UNSIGNED NOT NULL,
  `sent_date` date NOT NULL,
  `track` int(10) UNSIGNED NOT NULL,
  `status` int(3) UNSIGNED NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `track` (`track`),
  KEY `status` (`status`),
  KEY `id_subscriber` (`id_subscriber`),
  KEY `id_magazine` (`id_magazine`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `check`
--

INSERT INTO `check` (`id`, `id_subscriber`, `id_magazine`, `number`, `sent_date`, `track`, `status`, `created`) VALUES
(3, 1, 3, 213, '2019-07-27', 321, 0, '2019-07-27'),
(4, 4, 8, 213, '2019-07-26', 32, 0, '2019-07-27'),
(5, 3, 7, 33, '2019-07-27', 44, 0, '2019-07-27');

-- --------------------------------------------------------

--
-- Структура таблицы `magazine`
--

CREATE TABLE IF NOT EXISTS `magazine` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `number` int(11) NOT NULL,
  `release_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `number` (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `magazine`
--

INSERT INTO `magazine` (`id`, `name`, `number`, `release_date`) VALUES
(1, 'Журнал №1', 10, '2019-07-01'),
(2, 'Журнал №2', 20, '2019-07-01'),
(3, 'Журнал №3', 30, '2019-07-01'),
(4, 'Журнал №4', 40, '2019-07-01'),
(5, 'Журнал №5', 50, '2019-07-01'),
(6, 'Журнал №1', 11, '2019-08-01'),
(7, 'Журнал №2', 21, '2019-08-01'),
(8, 'Журнал №3', 31, '2019-08-01'),
(9, 'Журнал №4', 41, '2019-08-01'),
(10, 'Журнал №5', 51, '2019-08-01');

-- --------------------------------------------------------

--
-- Структура таблицы `mailing_list`
--

CREATE TABLE IF NOT EXISTS `mailing_list` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_subscriber` int(10) UNSIGNED NOT NULL,
  `act_date` date NOT NULL,
  `period` int(2) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `mailing_list`
--

INSERT INTO `mailing_list` (`id`, `id_subscriber`, `act_date`, `period`) VALUES
(1, 1, '2019-06-01', 12),
(2, 2, '2019-06-01', 12),
(3, 3, '2019-06-01', 12),
(4, 4, '2019-06-01', 12),
(5, 5, '2019-06-01', 12);

-- --------------------------------------------------------

--
-- Структура таблицы `subscriber`
--

CREATE TABLE IF NOT EXISTS `subscriber` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fio` text NOT NULL,
  `adr` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `subscriber`
--

INSERT INTO `subscriber` (`id`, `fio`, `adr`) VALUES
(1, 'Сидоров С.С.', 'Адрес Сидорова'),
(2, 'Иванов И.И.', 'Адрес Иванова'),
(3, 'Петров П.П.', 'Адрес Петрова'),
(4, 'Жуков Е.Е.', 'Адрес Жукова'),
(5, 'Спиридонов Е.Е.', 'Адрес Спиридонова');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
