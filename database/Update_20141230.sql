CREATE TABLE IF NOT EXISTS `settings` (
  `variable` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(1000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `settings` (`variable`, `value`) VALUES
('acceptedip', '127.0.0.1'),
('acceptedip2', '1.2.3.4'),
('jsonurl', 'http://adres:port/password/'),
('secretpassword', '5678'),
('secretusername', '1234');

ALTER TABLE `settings`
 ADD PRIMARY KEY (`variable`);
