-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Giu 15, 2016 alle 14:55
-- Versione del server: 10.1.10-MariaDB
-- Versione PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Test3DprintersBook`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `books`
--

CREATE TABLE `books` (
  `bcode` int(10) UNSIGNED NOT NULL,
  `client` varchar(64) NOT NULL,
  `machine` varchar(64) NOT NULL,
  `insertTime` time NOT NULL,
  `startingtime` time NOT NULL,
  `duration` int(10) UNSIGNED NOT NULL,
  `endingTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `books`
--

INSERT INTO `books` (`bcode`, `client`, `machine`, `insertTime`, `startingtime`, `duration`, `endingTime`) VALUES
(2, 'u3@p.it', 'DeltaWasp2040_2', '00:01:00', '17:15:00', 30, '17:45:00'),
(3, 'u2@p.it', 'DeltaWasp2040_3', '00:01:00', '18:00:00', 45, '18:45:00'),
(4, 'u2@p.it', 'DeltaWasp2040_4', '00:01:00', '19:00:00', 60, '20:00:00'),
(5, 'u1@p.it', 'DeltaWasp2040_1', '00:01:00', '15:30:00', 60, '16:30:00'),
(7, 'u1@p.it', 'DeltaWasp2040_1', '00:01:00', '20:10:00', 30, '20:40:00'),
(8, 'u2@p.it', 'DeltaWasp2040_2', '00:01:00', '20:05:00', 55, '21:00:00'),
(9, 'u3@p.it', 'DeltaWasp2040_1', '00:01:00', '16:40:00', 30, '17:10:00'),
(17, 'u1@p.it', 'DeltaWasp2040_2', '00:01:00', '15:00:00', 20, '15:20:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `members`
--

CREATE TABLE `members` (
  `name` varchar(24) NOT NULL,
  `lastname` varchar(24) NOT NULL,
  `user` varchar(64) NOT NULL,
  `pass` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `members`
--

INSERT INTO `members` (`name`, `lastname`, `user`, `pass`) VALUES
('Riccardo', 'Sisto', 'u1@p.it', 'a067b62d622a28ef15736e8613bfeab1'),
('Enrico', 'Masala', 'u2@p.it', 'efb37d6ad2baa97289d70ecafa4917e2'),
('Luca', 'Mannella', 'u3@p.it', '2b50cf5affd234f1baade55c11618174');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bcode`);

--
-- Indici per le tabelle `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`user`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `books`
--
ALTER TABLE `books`
  MODIFY `bcode` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
