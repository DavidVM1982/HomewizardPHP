-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Gegenereerd op: 03 jan 2015 om 07:30
-- Serverversie: 5.5.40-36.1-log
-- PHP-versie: 5.6.4-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `homewizard`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `switches`
--

CREATE TABLE IF NOT EXISTS `switches` (
  `id_switch` smallint(6) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `favorite` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `volgorde` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `switches`
--
ALTER TABLE `switches`
 ADD PRIMARY KEY (`id_switch`);

ALTER TABLE `sensors` 
 CHANGE `order` `volgorde` SMALLINT NULL DEFAULT NULL;
 
ALTER TABLE `sensors` 
 ADD `favorite` VARCHAR(3) NOT NULL ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




