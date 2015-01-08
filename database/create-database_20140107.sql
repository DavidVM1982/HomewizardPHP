-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Gegenereerd op: 08 jan 2015 om 07:33
-- Serverversie: 5.5.40-36.1-log
-- PHP-versie: 5.6.4-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databank: `homewizard`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `id_sensor` smallint(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `rain`
--

CREATE TABLE IF NOT EXISTS `rain` (
  `date` char(10) NOT NULL,
  `mm` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sensors`
--

CREATE TABLE IF NOT EXISTS `sensors` (
  `id_sensor` smallint(6) NOT NULL,
  `volgorde` smallint(6) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `favorite` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `variable` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(1000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT IGNORE INTO `settings` (`variable`, `value`) VALUES
('acceptedip', '127.0.0.1'),
('acceptedip2', '1.2.3.4'),
('debug', 'no'),
('detailscenes', 'no'),
('jsonurl', 'http://addres:port/password/'),
('secretpassword', '1598'),
('secretusername', '1234');
-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `statusses`
--

CREATE TABLE IF NOT EXISTS `statusses` (
  `status` varchar(200) NOT NULL,
  `omschrijving` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `temperature`
--

CREATE TABLE IF NOT EXISTS `temperature` (
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `te` float NOT NULL,
  `hu` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `temp_day`
--

CREATE TABLE IF NOT EXISTS `temp_day` (
  `date` char(10) NOT NULL,
  `min` float NOT NULL,
  `max` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wind`
--

CREATE TABLE IF NOT EXISTS `wind` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `wi` float NOT NULL,
  `gu` float NOT NULL,
  `dir` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wind_day`
--

CREATE TABLE IF NOT EXISTS `wind_day` (
  `date` char(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `history`
--
ALTER TABLE `history`
 ADD PRIMARY KEY (`id_sensor`,`time`);

--
-- Indexen voor tabel `rain`
--
ALTER TABLE `rain`
 ADD PRIMARY KEY (`date`);

--
-- Indexen voor tabel `sensors`
--
ALTER TABLE `sensors`
 ADD PRIMARY KEY (`id_sensor`);

--
-- Indexen voor tabel `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`variable`);

--
-- Indexen voor tabel `statusses`
--
ALTER TABLE `statusses`
 ADD PRIMARY KEY (`status`);

--
-- Indexen voor tabel `switches`
--
ALTER TABLE `switches`
 ADD PRIMARY KEY (`id_switch`);

--
-- Indexen voor tabel `temperature`
--
ALTER TABLE `temperature`
 ADD PRIMARY KEY (`timestamp`);

--
-- Indexen voor tabel `temp_day`
--
ALTER TABLE `temp_day`
 ADD PRIMARY KEY (`date`);

--
-- Indexen voor tabel `wind`
--
ALTER TABLE `wind`
 ADD PRIMARY KEY (`timestamp`);

--
-- Indexen voor tabel `wind_day`
--
ALTER TABLE `wind_day`
 ADD PRIMARY KEY (`date`);
