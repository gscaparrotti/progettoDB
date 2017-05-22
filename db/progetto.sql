-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Mag 22, 2017 alle 19:17
-- Versione del server: 10.1.16-MariaDB
-- Versione PHP: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `progetto`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Acquisto`
--

CREATE TABLE `Acquisto` (
  `Prodotto` int(11) NOT NULL,
  `Cliente` varchar(30) COLLATE utf8_bin NOT NULL,
  `Data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Pagamento` int(11) NOT NULL,
  `Stato` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `Acquisto`
--

INSERT INTO `Acquisto` (`Prodotto`, `Cliente`, `Data`, `Pagamento`, `Stato`) VALUES
(4, 'gscaparrotti@gmail.com', '2017-05-22 15:54:04', 1, 'Nuovo');

-- --------------------------------------------------------

--
-- Struttura della tabella `Cliente`
--

CREATE TABLE `Cliente` (
  `E-Mail` varchar(30) COLLATE utf8_bin NOT NULL,
  `Password` text COLLATE utf8_bin,
  `Nome` text COLLATE utf8_bin NOT NULL,
  `Cognome` text COLLATE utf8_bin NOT NULL,
  `CAP` int(5) NOT NULL,
  `Via` text COLLATE utf8_bin NOT NULL,
  `Citta` text COLLATE utf8_bin NOT NULL,
  `Civico` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `Cliente`
--

INSERT INTO `Cliente` (`E-Mail`, `Password`, `Nome`, `Cognome`, `CAP`, `Via`, `Citta`, `Civico`) VALUES
('gscaparrotti@gmail.com', NULL, 'Giacomo', 'Scaparrotti', 47921, 'Via Francia', 'Rimini', 22);

-- --------------------------------------------------------

--
-- Struttura della tabella `Commenti`
--

CREATE TABLE `Commenti` (
  `nickname` varchar(50) COLLATE utf8_bin NOT NULL,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `commento` varchar(300) COLLATE utf8_bin NOT NULL,
  `id_prodotto` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `Commenti`
--

INSERT INTO `Commenti` (`nickname`, `email`, `commento`, `id_prodotto`, `date`) VALUES
('jack', 'gscaparrotti@gmail.com', 'Prodotto Fantastico!', 1, '2017-05-22 16:58:58');

-- --------------------------------------------------------

--
-- Struttura della tabella `Fornitore`
--

CREATE TABLE `Fornitore` (
  `P_IVA` int(11) NOT NULL,
  `Ragione Sociale` text COLLATE utf8_bin NOT NULL,
  `CAP` int(5) NOT NULL,
  `Città` text COLLATE utf8_bin NOT NULL,
  `Via` text COLLATE utf8_bin NOT NULL,
  `Civico` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `Fornitore`
--

INSERT INTO `Fornitore` (`P_IVA`, `Ragione Sociale`, `CAP`, `Città`, `Via`, `Civico`) VALUES
(1111111111, 'Fornitore 2', 47900, 'Rimini', 'Italia', 15),
(1234567890, 'Fornitore 1', 47921, 'Rimini', 'Via Francia', 22);

-- --------------------------------------------------------

--
-- Struttura della tabella `Fornitura`
--

CREATE TABLE `Fornitura` (
  `Fornitore` int(11) NOT NULL,
  `Data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `Fornitura`
--

INSERT INTO `Fornitura` (`Fornitore`, `Data`) VALUES
(1111111111, '2017-05-19 11:50:56'),
(1234567890, '2017-05-19 11:32:02'),
(1234567890, '2017-05-19 11:33:36');

-- --------------------------------------------------------

--
-- Struttura della tabella `MetodoPagamento`
--

CREATE TABLE `MetodoPagamento` (
  `ID` int(11) NOT NULL,
  `Cliente` varchar(30) COLLATE utf8_bin NOT NULL,
  `Intestatario` text COLLATE utf8_bin NOT NULL,
  `Tipo` int(11) NOT NULL,
  `Codice` int(16) DEFAULT NULL,
  `Scadenza` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `CodSicurezza` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `MetodoPagamento`
--

INSERT INTO `MetodoPagamento` (`ID`, `Cliente`, `Intestatario`, `Tipo`, `Codice`, `Scadenza`, `CodSicurezza`) VALUES
(1, 'gscaparrotti@gmail.com', 'Giacomo Scaparrotti', 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `Ordinare`
--

CREATE TABLE `Ordinare` (
  `Fornitore` int(11) NOT NULL,
  `Data` datetime NOT NULL,
  `Prodotto` int(11) NOT NULL,
  `Quantita` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `Prodotto`
--

CREATE TABLE `Prodotto` (
  `Codice` int(11) NOT NULL,
  `Costo` decimal(10,0) NOT NULL,
  `Descrizione` text COLLATE utf8_bin NOT NULL,
  `Produttore` text COLLATE utf8_bin NOT NULL,
  `Nome` text COLLATE utf8_bin NOT NULL,
  `img` text COLLATE utf8_bin NOT NULL,
  `Tipo` int(11) NOT NULL,
  `Potenza` int(11) DEFAULT NULL,
  `RiF` text COLLATE utf8_bin,
  `N_Ingressi` int(11) DEFAULT NULL,
  `Pot_Max` int(11) DEFAULT NULL,
  `N_Vie` int(11) DEFAULT NULL,
  `Formati` text COLLATE utf8_bin,
  `Dac` text COLLATE utf8_bin,
  `Uscita` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `Prodotto`
--

INSERT INTO `Prodotto` (`Codice`, `Costo`, `Descrizione`, `Produttore`, `Nome`, `img`, `Tipo`, `Potenza`, `RiF`, `N_Ingressi`, `Pot_Max`, `N_Vie`, `Formati`, `Dac`, `Uscita`) VALUES
(1, '100', 'Amplificatore Phonola 5520: grande musicalità e qualità costruttiva al miglior prezzo!', 'Phonola', 'Stereo Amplifier 5520', 'pictures/ampli.jpg', 1, 50, '20-20000 Hz', NULL, NULL, NULL, NULL, NULL, NULL),
(2, '50', 'Ottimo mangianastri economico per dare nuova vita a tutte le vostre musicassette!', 'Aiwa', 'AD-F300', 'pictures/mn.JPG', 3, NULL, NULL, NULL, NULL, NULL, 'Musicassetta', 'N\\A', 'RCA Stereo');

-- --------------------------------------------------------

--
-- Struttura della tabella `ProdottoInNegozio`
--

CREATE TABLE `ProdottoInNegozio` (
  `ID` int(11) NOT NULL,
  `Prodotto` int(11) NOT NULL,
  `Fornitore` int(11) NOT NULL,
  `DataFornitura` datetime NOT NULL,
  `DurataGaranzia` int(11) NOT NULL,
  `Sconto` int(11) NOT NULL,
  `Condizione` text COLLATE utf8_bin NOT NULL,
  `Venduto` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `ProdottoInNegozio`
--

INSERT INTO `ProdottoInNegozio` (`ID`, `Prodotto`, `Fornitore`, `DataFornitura`, `DurataGaranzia`, `Sconto`, `Condizione`, `Venduto`) VALUES
(1, 1, 1234567890, '2017-05-19 11:32:02', 2, 0, 'Nuovo', 0),
(2, 1, 1234567890, '2017-05-19 11:33:36', 2, 20, 'Usato', 0),
(4, 2, 1111111111, '2017-05-19 11:50:56', 2, 0, 'Nuovo', 1),
(5, 1, 1234567890, '2017-05-19 11:32:02', 2, 0, 'Nuovo', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `Ricambio`
--

CREATE TABLE `Ricambio` (
  `Nome` varchar(30) COLLATE utf8_bin NOT NULL,
  `Costo` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `Riparatore`
--

CREATE TABLE `Riparatore` (
  `Matricola` int(11) NOT NULL,
  `Nome` text COLLATE utf8_bin NOT NULL,
  `Cognome` text COLLATE utf8_bin NOT NULL,
  `PagaOraria` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `Riparazione`
--

CREATE TABLE `Riparazione` (
  `Prodotto` int(11) NOT NULL,
  `Cliente` varchar(30) COLLATE utf8_bin NOT NULL,
  `Data Acquisto` datetime NOT NULL,
  `Data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Durata` int(11) NOT NULL,
  `Riparatore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `Sostituzione`
--

CREATE TABLE `Sostituzione` (
  `Prodotto` int(11) NOT NULL,
  `Cliente` varchar(30) COLLATE utf8_bin NOT NULL,
  `Data Acquisto` datetime NOT NULL,
  `Data` datetime NOT NULL,
  `Ricambio` varchar(30) COLLATE utf8_bin NOT NULL,
  `Quantita` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Acquisto`
--
ALTER TABLE `Acquisto`
  ADD PRIMARY KEY (`Prodotto`,`Cliente`,`Data`),
  ADD KEY `Pagamento` (`Pagamento`),
  ADD KEY `Cliente` (`Cliente`);

--
-- Indici per le tabelle `Cliente`
--
ALTER TABLE `Cliente`
  ADD PRIMARY KEY (`E-Mail`);

--
-- Indici per le tabelle `Commenti`
--
ALTER TABLE `Commenti`
  ADD PRIMARY KEY (`nickname`,`date`),
  ADD KEY `id_prodotto` (`id_prodotto`);

--
-- Indici per le tabelle `Fornitore`
--
ALTER TABLE `Fornitore`
  ADD PRIMARY KEY (`P_IVA`);

--
-- Indici per le tabelle `Fornitura`
--
ALTER TABLE `Fornitura`
  ADD PRIMARY KEY (`Fornitore`,`Data`);

--
-- Indici per le tabelle `MetodoPagamento`
--
ALTER TABLE `MetodoPagamento`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Cliente` (`Cliente`);

--
-- Indici per le tabelle `Ordinare`
--
ALTER TABLE `Ordinare`
  ADD PRIMARY KEY (`Fornitore`,`Data`,`Prodotto`),
  ADD KEY `Prodotto` (`Prodotto`);

--
-- Indici per le tabelle `Prodotto`
--
ALTER TABLE `Prodotto`
  ADD PRIMARY KEY (`Codice`);

--
-- Indici per le tabelle `ProdottoInNegozio`
--
ALTER TABLE `ProdottoInNegozio`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Prodotto` (`Prodotto`),
  ADD KEY `Fornitura` (`Fornitore`),
  ADD KEY `DataFornitura` (`DataFornitura`),
  ADD KEY `Fornitore` (`Fornitore`,`DataFornitura`);

--
-- Indici per le tabelle `Ricambio`
--
ALTER TABLE `Ricambio`
  ADD PRIMARY KEY (`Nome`);

--
-- Indici per le tabelle `Riparatore`
--
ALTER TABLE `Riparatore`
  ADD PRIMARY KEY (`Matricola`);

--
-- Indici per le tabelle `Riparazione`
--
ALTER TABLE `Riparazione`
  ADD PRIMARY KEY (`Prodotto`,`Cliente`,`Data Acquisto`,`Data`),
  ADD KEY `Riparatore` (`Riparatore`);

--
-- Indici per le tabelle `Sostituzione`
--
ALTER TABLE `Sostituzione`
  ADD PRIMARY KEY (`Data`,`Prodotto`,`Cliente`,`Data Acquisto`,`Ricambio`),
  ADD KEY `Prodotto` (`Prodotto`,`Cliente`,`Data Acquisto`,`Data`),
  ADD KEY `Ricambio` (`Ricambio`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `MetodoPagamento`
--
ALTER TABLE `MetodoPagamento`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `ProdottoInNegozio`
--
ALTER TABLE `ProdottoInNegozio`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Acquisto`
--
ALTER TABLE `Acquisto`
  ADD CONSTRAINT `Acquisto_ibfk_1` FOREIGN KEY (`Prodotto`) REFERENCES `ProdottoInNegozio` (`ID`),
  ADD CONSTRAINT `Acquisto_ibfk_2` FOREIGN KEY (`Cliente`) REFERENCES `Cliente` (`E-Mail`),
  ADD CONSTRAINT `Acquisto_ibfk_3` FOREIGN KEY (`Pagamento`) REFERENCES `MetodoPagamento` (`ID`);

--
-- Limiti per la tabella `Commenti`
--
ALTER TABLE `Commenti`
  ADD CONSTRAINT `Commenti_ibfk_1` FOREIGN KEY (`id_prodotto`) REFERENCES `Prodotto` (`Codice`);

--
-- Limiti per la tabella `Fornitura`
--
ALTER TABLE `Fornitura`
  ADD CONSTRAINT `Fornitura_ibfk_1` FOREIGN KEY (`Fornitore`) REFERENCES `Fornitore` (`P_IVA`);

--
-- Limiti per la tabella `MetodoPagamento`
--
ALTER TABLE `MetodoPagamento`
  ADD CONSTRAINT `MetodoPagamento_ibfk_1` FOREIGN KEY (`Cliente`) REFERENCES `Cliente` (`E-Mail`);

--
-- Limiti per la tabella `Ordinare`
--
ALTER TABLE `Ordinare`
  ADD CONSTRAINT `Ordinare_ibfk_1` FOREIGN KEY (`Fornitore`,`Data`) REFERENCES `Fornitura` (`Fornitore`, `Data`),
  ADD CONSTRAINT `Ordinare_ibfk_2` FOREIGN KEY (`Prodotto`) REFERENCES `ProdottoInNegozio` (`ID`);

--
-- Limiti per la tabella `ProdottoInNegozio`
--
ALTER TABLE `ProdottoInNegozio`
  ADD CONSTRAINT `ProdottoInNegozio_ibfk_1` FOREIGN KEY (`Fornitore`,`DataFornitura`) REFERENCES `Fornitura` (`Fornitore`, `Data`),
  ADD CONSTRAINT `vincolo_prodotto` FOREIGN KEY (`Prodotto`) REFERENCES `Prodotto` (`Codice`);

--
-- Limiti per la tabella `Riparazione`
--
ALTER TABLE `Riparazione`
  ADD CONSTRAINT `Riparazione_ibfk_1` FOREIGN KEY (`Prodotto`,`Cliente`,`Data Acquisto`) REFERENCES `Acquisto` (`Prodotto`, `Cliente`, `Data`),
  ADD CONSTRAINT `Riparazione_ibfk_2` FOREIGN KEY (`Riparatore`) REFERENCES `Riparatore` (`Matricola`);

--
-- Limiti per la tabella `Sostituzione`
--
ALTER TABLE `Sostituzione`
  ADD CONSTRAINT `Sostituzione_ibfk_1` FOREIGN KEY (`Prodotto`,`Cliente`,`Data Acquisto`,`Data`) REFERENCES `Riparazione` (`Prodotto`, `Cliente`, `Data Acquisto`, `Data`),
  ADD CONSTRAINT `Sostituzione_ibfk_2` FOREIGN KEY (`Ricambio`) REFERENCES `Ricambio` (`Nome`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
