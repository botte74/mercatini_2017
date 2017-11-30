-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Nov 30, 2017 alle 23:52
-- Versione del server: 5.6.35
-- Versione PHP: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parrocchia_mercatini`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `abilitazione`
--

CREATE TABLE `abilitazione` (
  `ab_gruppo` varchar(20) NOT NULL,
  `ab_tipoutente` varchar(10) NOT NULL,
  `ab_attivo` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `abilitazione`
--

INSERT INTO `abilitazione` (`ab_gruppo`, `ab_tipoutente`, `ab_attivo`) VALUES
('Aggiunte', 'admin', 'S'),
('Aggiunte', 'bar', 'S'),
('Bar', 'admin', 'S'),
('Bar', 'bar', 'S'),
('Bibite', 'admin', 'S'),
('Bibite', 'bar', 'S'),
('Bibite', 'bibite', 'S'),
('Bibite', 'cassa', 'S'),
('Birra', 'admin', 'S'),
('Birra', 'bar', 'S'),
('Birra', 'cassa', 'S'),
('Bistecche', 'admin', 'S'),
('Bistecche', 'cassa', 'S'),
('Contorni', 'admin', 'S'),
('Contorni', 'cassa', 'S'),
('Dolci', 'admin', 'S'),
('Dolci', 'bibite', 'S'),
('Dolci', 'cassa', ''),
('Golosita', 'admin', 'S'),
('Golosita', 'bar', 'S'),
('Panini', 'admin', 'S'),
('Panini', 'bar', 'S'),
('Piatti', 'admin', 'S'),
('Piatti', 'cassa', 'S'),
('Secondi', 'admin', 'S'),
('Secondi', 'cassa', 'S'),
('Speciali', 'admin', 'S'),
('Tagliate', 'admin', 'S'),
('Tagliate', 'cassa', 'S'),
('Vino', 'admin', 'S'),
('Vino', 'bar', 'S');

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

CREATE TABLE `articoli` (
  `ar_codice` varchar(20) NOT NULL,
  `ar_descrizione` varchar(80) NOT NULL,
  `ar_descbreve` varchar(80) NOT NULL,
  `ar_attivo` varchar(1) NOT NULL,
  `ar_ordinamento` int(5) NOT NULL,
  `ar_gruppo` varchar(20) NOT NULL,
  `ar_prezzo` decimal(5,2) NOT NULL,
  `ar_stile` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`ar_codice`, `ar_descrizione`, `ar_descbreve`, `ar_attivo`, `ar_ordinamento`, `ar_gruppo`, `ar_prezzo`, `ar_stile`) VALUES
('Bianco frizz 1/2L', 'Vino bianco frizzante 1/2 litro', 'Bianco frizzante 1/2 litro', 'S', 60, 'Vino', '3.00', 'bianco'),
('Bianco frizzante 1L', 'Vino bianco frizzante 1 litro', 'Bianco frizzante 1 litro', 'S', 50, 'Vino', '4.50', 'bianco'),
('Bionda media', 'Birra bionda media 0,4 ', 'Bionda media', 'S', 20, 'Birra', '4.00', 'giallo'),
('Bionda piccola', 'Birra bionda piccola 0,3 ', 'Bionda piccola', 'S', 10, 'Birra', '3.00', 'giallo'),
('Cioccolata calda', 'Cioccolata calda', 'Cioccolata calda', 'S', 110, 'Bar', '1.50', 'verde'),
('Coca lattina', 'Lattina di coca cola', 'Lattina di coca cola', 'S', 90, 'Bibite', '1.50', 'nero'),
('Coca spina', 'Coca cola alla spina 0,3', 'Coca alla spina', 'S', 100, 'Bibite', '2.50', 'nero'),
('Crepes', 'Crepes alla nutella', 'Crepes alla nutella', 'S', 230, 'Golosita', '2.50', 'giallo'),
('Fanta', 'Lattina di fanta', 'Lattina di fanta', 'S', 80, 'Bibite', '1.50', 'arancio'),
('Frizzante', 'Acqua frizzante 1/2 litro', 'Frizzante', 'S', 70, 'Bibite', '1.00', 'bianco'),
('Frizzante bicchiere', 'Frizzante bicchiere', 'Frizzante bicchiere', 'S', 65, 'Vino', '1.00', 'bianco'),
('Funghi', 'Funghi', 'Funghi', 'S', 180, 'Aggiunte', '0.50', 'arancio'),
('Hot dog', 'Hot dog', 'Hot dog', 'S', 170, 'Panini', '2.50', 'arancio'),
('Melanzane', 'Melanzane', 'Melanzane', 'S', 190, 'Aggiunte', '0.50', 'viola'),
('Naturale', 'Acqua naturale 1/2 litro', 'Naturale', 'S', 75, 'Bibite', '1.00', 'azzurro'),
('Panino porchetta', 'Panino porchetta', 'Panino porchetta', 'S', 140, 'Panini', '3.50', 'rosso'),
('Patate fritte', 'Patate fritte', 'Patate fritte', 'S', 130, 'Panini', '2.50', 'giallo'),
('Peperoni', 'Peperoni', 'Peperoni', 'S', 200, 'Aggiunte', '0.50', 'rosso'),
('Piadina crudo form', 'Piadina crudo e formaggio', 'Piadina crudo e formaggio', 'S', 150, 'Panini', '3.50', 'blu'),
('Piadina porch form', 'Piadina porchetta e formaggio', 'Piadina porchetta e formaggio', 'S', 160, 'Panini', '3.50', 'verde'),
('Porchetta', 'Piatto di porchetta con pane', 'Piatto di porchetta con pane', 'S', 220, 'Piatti', '5.00', 'rosso'),
('Rosso bicchiere', 'Rosso bicchiere', 'Rosso bicchiere', 'S', 45, 'Vino', '1.00', 'rosso'),
('Salame al cioccolato', 'Salame al cioccolato', 'Salame al cioccolato', 'S', 260, 'Golosita', '2.00', 'viola'),
('Sconto 0,1', 'Sconto', 'Sconto', 'S', 501, 'Speciali', '-0.10', 'nero'),
('Sconto 0,5', 'Sconto', 'Sconto', 'S', 502, 'Speciali', '-0.50', 'nero'),
('Sconto 1', 'Sconto', 'Sconto', 'S', 503, 'Speciali', '-1.00', 'nero'),
('Sconto 10', 'Sconto', 'Sconto', 'S', 504, 'Speciali', '-10.00', 'nero'),
('Spritz aperol', 'Spritz aperol', 'Spritz aperol', 'S', 66, 'Vino', '2.50', 'rosso'),
('Spritz campari', 'Spritz campari', 'Spritz campari', 'S', 67, 'Vino', '2.50', 'rosso'),
('Vin brule', 'Vin brule', 'Vin brule', 'S', 120, 'Bar', '1.50', 'viola'),
('Vino rosso 1/2 L', 'Vino rosso 1/2 litro', 'Rosso 1/2 litro', 'S', 40, 'Vino', '3.00', 'rosso'),
('Vino rosso 1L', 'Vino rosso 1 litro', 'Rosso 1 litro', 'S', 30, 'Vino', '4.50', 'rosso'),
('Waffle', 'Waffle alla nutella', 'Waffle alla nutella', 'S', 240, 'Golosita', '2.50', 'giallo'),
('Zucchero', 'Zucchero filato', 'Zucchero filato', 'S', 250, 'Golosita', '1.50', 'rosso'),
('Zucchine', 'Zucchine', 'Zucchine', 'S', 210, 'Aggiunte', '0.50', 'verde');

-- --------------------------------------------------------

--
-- Struttura della tabella `distinta`
--

CREATE TABLE `distinta` (
  `di_codicearticolo` varchar(20) NOT NULL,
  `di_codiceprodotto` varchar(20) NOT NULL,
  `di_coefficente` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `distinta`
--

INSERT INTO `distinta` (`di_codicearticolo`, `di_codiceprodotto`, `di_coefficente`) VALUES
('Bianco frizz 1/2L', 'bianco_frizzante_L', '0.50'),
('Bianco frizzante 1L', 'bianco_frizzante_L', '1.00'),
('Bionda media', 'birra_bionda_L', '0.40'),
('Bionda piccola', 'birra_bionda_L', '0.30'),
('Cioccolata calda', 'Cioccolata calda', '1.00'),
('Coca lattina', 'coca_cola_lattina', '1.00'),
('Coca spina', 'coca_cola_L', '0.30'),
('Crepes', 'crepes', '1.00'),
('Crepes', 'nutella', '1.00'),
('Fanta', 'fanta_lattina', '1.00'),
('Frizzante', 'acqua_frizzante', '1.00'),
('Frizzante bicchiere', 'bianco_frizzante_L', '0.16'),
('Funghi', 'funghi', '1.00'),
('Hot dog', 'pane_hotdog', '1.00'),
('Hot dog', 'würstel', '1.00'),
('Melanzane', 'melanzane', '1.00'),
('Naturale', 'acqua_naturale', '1.00'),
('Panino porchetta', 'pane_panino', '1.00'),
('Panino porchetta', 'porchetta', '1.00'),
('Patate fritte', 'patate_fritte', '1.00'),
('Peperoni', 'peperoni_pia', '1.00'),
('Piadina crudo form', 'crudo', '1.00'),
('Piadina crudo form', 'formaggio_panino', '1.00'),
('Piadina crudo form', 'piadina', '1.00'),
('Piadina porch form', 'formaggio_panino', '1.00'),
('Piadina porch form', 'piadina', '1.00'),
('Piadina porch form', 'porchetta', '1.00'),
('Porchetta', 'pane_panino', '1.00'),
('Porchetta', 'porchetta', '1.00'),
('Salame al cioccolato', 'Salame al cioccolato', '1.00'),
('Spritz aperol', 'aperol', '1.00'),
('Spritz campari', 'campari', '1.00'),
('Vin brule', 'Vin brule', '1.00'),
('Vino rosso 1/2 L', 'vino_rosso_L', '0.50'),
('Vino rosso 1L', 'vino_rosso_L', '1.00'),
('Waffle', 'nutella', '1.00'),
('Waffle', 'waffle', '1.00'),
('Zucchero', 'zucchero_filato', '1.00'),
('Zucchine', 'zucchine', '1.00');

-- --------------------------------------------------------

--
-- Struttura della tabella `griglie`
--

CREATE TABLE `griglie` (
  `gri_serata` int(5) NOT NULL,
  `gri_prodotto` varchar(20) NOT NULL,
  `gri_quantita` decimal(7,2) NOT NULL,
  `gri_richiesta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `griglie_legami`
--

CREATE TABLE `griglie_legami` (
  `leg_prodotto1` varchar(20) NOT NULL,
  `leg_prodotto2` varchar(20) NOT NULL,
  `leg_coefficente` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppi`
--

CREATE TABLE `gruppi` (
  `gr_codice` varchar(20) NOT NULL,
  `gr_descrizione` varchar(40) NOT NULL,
  `gr_attivo` varchar(1) NOT NULL,
  `gr_ordinamento` int(5) NOT NULL,
  `gr_stile` varchar(20) NOT NULL,
  `gr_stato` int(1) NOT NULL,
  `gr_tipostampa` varchar(1) NOT NULL COMMENT 'N = NO, F = Foglio, = S = Scontrino',
  `gr_cartella` varchar(50) NOT NULL,
  `gr_cartella2` varchar(50) NOT NULL,
  `gr_barcode` varchar(2) NOT NULL,
  `gr_descrizioneunificata` varchar(40) NOT NULL,
  `gr_ritardo_stampa` int(5) NOT NULL,
  `gr_griglia` varchar(1) NOT NULL,
  `gr_coperti` varchar(1) NOT NULL,
  `gr_buono` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `gruppi`
--

INSERT INTO `gruppi` (`gr_codice`, `gr_descrizione`, `gr_attivo`, `gr_ordinamento`, `gr_stile`, `gr_stato`, `gr_tipostampa`, `gr_cartella`, `gr_cartella2`, `gr_barcode`, `gr_descrizioneunificata`, `gr_ritardo_stampa`, `gr_griglia`, `gr_coperti`, `gr_buono`) VALUES
('Aggiunte', 'Aggiunte', 'S', 23, 'giallo', 5, 'S', '', '', '', '', 0, '', '', ''),
('Bar', 'Bar', 'S', 16, 'nero', 5, 'S', '', '', '', '', 0, '', '', ''),
('Bibite', 'Bibite', 'S', 14, 'giallo', 5, 'S', 'bere', '', '2', 'Bere', 0, '', 'S', ''),
('Birra', 'Birra', 'S', 11, 'arancio', 5, 'S', 'birre', '', '3', 'Birre', 0, '', '', ''),
('Bistecche', 'Bistecche (Stampare Scontrino!!)', 'S', 35, 'arancio', 3, 'T', 'mangiare', 'bistecche', '4', 'Bistecche', 0, '', '', ''),
('Contorni', 'Contorni', 'S', 21, 'blu', 5, 'S', 'mangiare', '', '1', 'Mangiare', 0, '', '', ''),
('Dolci', 'Dolci', 'S', 100, 'rosso', 5, 'N', '', '', '', '', 0, '', '', 'S'),
('Golosita', 'Golosita', 'S', 28, 'verde', 5, 'S', '', '', '', '', 0, '', '', ''),
('Panini', 'Panini', 'S', 22, 'rosso', 5, 'S', '', '', '', '', 0, '', '', ''),
('Piatti', 'Piatti', 'S', 26, 'arancio', 5, 'S', 'mangiare', '', '1', 'Mangiare', 0, '', '', ''),
('Secondi', 'Secondi', 'S', 20, 'arancio', 4, 'F', 'mangiare', '', '1', 'Mangiare', 0, '', '', ''),
('Speciali', 'Speciali', 'S', 200, 'nero', 5, 'N', '', '', '', '', 0, '', '', ''),
('Tagliate', 'Tagliate (Stampare Scontrino!!)', 'S', 40, 'viola', 3, 'T', 'mangiare', 'tagliate', '5', 'Tagliate', 0, '', '', ''),
('Vino', 'Vino', 'S', 12, 'arancio', 5, 'S', '', '', '', '', 0, '', '', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppi_magazzino`
--

CREATE TABLE `gruppi_magazzino` (
  `gr_ma_codice` varchar(10) NOT NULL,
  `gr_ma_descrizione` varchar(30) NOT NULL,
  `gr_ma_ordinamento` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `gruppi_magazzino`
--

INSERT INTO `gruppi_magazzino` (`gr_ma_codice`, `gr_ma_descrizione`, `gr_ma_ordinamento`) VALUES
('bibite', 'Bibite', 20),
('carne', 'Carne', 10),
('dolci', 'Dolci', 90),
('formaggi', 'Formaggi', 40),
('pane', 'Pane', 70),
('pasta', 'Pasta', 50),
('patate', 'Patate', 60),
('pesce', 'Pesce', 80),
('stoviglie', 'Stoviglie', 100),
('verdure', 'Verdure', 30);

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppi_tavoli`
--

CREATE TABLE `gruppi_tavoli` (
  `gr_ta_id` int(2) NOT NULL,
  `gr_ta_descrizione` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `gruppi_tavoli`
--

INSERT INTO `gruppi_tavoli` (`gr_ta_id`, `gr_ta_descrizione`) VALUES
(1, 'GRUPPO A01-A08'),
(2, 'GRUPPO A09-A16'),
(3, 'GRUPPO B01-B08'),
(4, 'GRUPPO B09-B16'),
(5, 'GRUPPO C01-C08'),
(6, 'GRUPPO C09-C16'),
(7, 'SPECIALI');

-- --------------------------------------------------------

--
-- Struttura della tabella `magazzino`
--

CREATE TABLE `magazzino` (
  `ma_codiceprodotto` varchar(20) NOT NULL,
  `ma_descrizione` varchar(40) NOT NULL,
  `ma_giacenza` decimal(10,2) NOT NULL,
  `ma_unita_misura` varchar(10) NOT NULL,
  `ma_ordinamento` int(5) NOT NULL,
  `ma_gruppi` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `magazzino`
--

INSERT INTO `magazzino` (`ma_codiceprodotto`, `ma_descrizione`, `ma_giacenza`, `ma_unita_misura`, `ma_ordinamento`, `ma_gruppi`) VALUES
('acqua_frizzante', 'bottiglietta 1/2 litro acqua frizzante', '10000.00', '', 60, 'bibite'),
('acqua_naturale', 'bottiglietta 1/2 litro acqua naturale', '10000.00', '', 70, 'bibite'),
('aperol', 'porzione di aperol per spritz', '10000.00', '', 120, 'bibite'),
('bianco_frizzante_L', 'vino bianco frizzante', '10000.00', '', 40, 'bibite'),
('birra_bionda_L', 'birra bionda al litro', '10000.00', '', 100, 'bibite'),
('campari', 'porzione di campari per spritz', '10000.00', '', 160, 'bibite'),
('Cioccolata calda', 'Cioccolata calda', '10000.00', '', 10, 'dolci'),
('coca_cola_L', 'coca cola alla spina al litro', '10000.00', '', 130, 'bibite'),
('coca_cola_lattina', 'coca cola in lattina', '10000.00', '', 80, 'bibite'),
('crepes', 'crepes alla nutella', '10000.00', '', 70, 'dolci'),
('crudo', 'prosciutto crudo', '10000.00', '', 110, 'carne'),
('fanta_lattina', 'lattina di fanta', '10000.00', '', 90, 'bibite'),
('formaggio_panino', 'formaggio da panino', '10000.00', '', 20, 'formaggi'),
('funghi', 'funghi sotto olio', '10000.00', '', 60, 'verdure'),
('melanzane', 'melanzane ai ferri', '10000.00', '', 70, 'verdure'),
('nutella', 'nutella per dolci', '10000.00', '', 10, 'dolci'),
('pane_hotdog', 'pane da hot dog', '10000.00', '', 10, 'pane'),
('pane_panino', 'pane da panino', '10000.00', '', 20, 'pane'),
('patate_fritte', 'porzione di patate fritte', '10000.00', '', 10, 'patate'),
('peperoni_for', 'peperoni al forno', '10000.00', '', 80, 'verdure'),
('peperoni_pia', 'peperoni alla piastra', '10000.00', '', 10, 'verdure'),
('piadina', 'piadina', '10000.00', '', 30, 'pane'),
('porchetta', 'porchetta', '10000.00', '', 120, 'carne'),
('Salame al cioccolato', 'Salame al cioccolato', '10000.00', '', 200, 'dolci'),
('spritz_aperol', 'spritz aperol', '10000.00', '', 10, 'bibite'),
('spritz_campari', 'spritz campari', '10000.00', '', 10, 'bibite'),
('Vin brule', 'Vin brule', '10000.00', '', 20, 'dolci'),
('waffle', 'waffle alla nutella', '10000.00', '', 80, 'dolci'),
('würstel', 'würstel', '10000.00', '', 10, 'carne'),
('zucchero_filato', 'zucchero filato', '10000.00', '', 90, 'dolci'),
('zucchine', 'zucchine ai ferri', '10000.00', '', 90, 'verdure');

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE `ordini` (
  `or_numero` int(5) NOT NULL,
  `or_cliente` varchar(40) NOT NULL,
  `or_tipo` varchar(1) NOT NULL COMMENT 'T = Tavolo, A = Asporto, B = Bar',
  `or_tavolo` varchar(6) NOT NULL,
  `or_serata` int(5) NOT NULL,
  `or_cassa` varchar(20) NOT NULL,
  `or_data_inizio` datetime NOT NULL,
  `or_data_abbina` datetime NOT NULL,
  `or_data_fine` datetime NOT NULL,
  `or_totale` decimal(5,2) NOT NULL,
  `or_stato` int(1) NOT NULL COMMENT '0 = Aperto, 1 = Pagato, 2 = Abbinato, 3 = Da Ristampare, 4 = Distribuito, 5 = Chiuso',
  `or_coperti` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordinirighe`
--

CREATE TABLE `ordinirighe` (
  `ri_ordine` int(5) NOT NULL,
  `ri_riga` int(5) NOT NULL,
  `ri_codice` varchar(20) NOT NULL,
  `ri_descrizione` varchar(200) NOT NULL,
  `ri_quantita` int(5) NOT NULL,
  `ri_prezzo` decimal(5,2) NOT NULL,
  `ri_mod` varchar(1) NOT NULL,
  `ri_nota` varchar(200) NOT NULL,
  `ri_stato` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Trigger `ordinirighe`
--
DELIMITER $$
CREATE TRIGGER `aggiungi_riga` AFTER INSERT ON `ordinirighe` FOR EACH ROW UPDATE magazzino SET ma_giacenza = ma_giacenza - NEW.ri_quantita * (SELECT di_coefficente FROM distinta WHERE di_codicearticolo = NEW.ri_codice AND di_codiceprodotto = ma_codiceprodotto) WHERE EXISTS (SELECT * FROM distinta WHERE di_codicearticolo = NEW.ri_codice AND di_codiceprodotto = ma_codiceprodotto)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `cancella_riga` AFTER DELETE ON `ordinirighe` FOR EACH ROW UPDATE magazzino SET ma_giacenza = ma_giacenza + OLD.ri_quantita * (SELECT di_coefficente FROM distinta WHERE di_codicearticolo = OLD.ri_codice AND di_codiceprodotto = ma_codiceprodotto) WHERE EXISTS (SELECT * FROM distinta WHERE di_codicearticolo = OLD.ri_codice AND di_codiceprodotto = ma_codiceprodotto)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `modifica_riga` AFTER UPDATE ON `ordinirighe` FOR EACH ROW UPDATE magazzino SET ma_giacenza = ma_giacenza - (NEW.ri_quantita - OLD.ri_quantita) * (SELECT di_coefficente FROM distinta WHERE di_codicearticolo = NEW.ri_codice AND di_codiceprodotto = ma_codiceprodotto) WHERE EXISTS (SELECT * FROM distinta WHERE di_codicearticolo = NEW.ri_codice AND di_codiceprodotto = ma_codiceprodotto)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti`
--

CREATE TABLE `prodotti` (
  `pr_codice` varchar(20) NOT NULL,
  `pr_descrizione` varchar(40) NOT NULL,
  `pr_griglia` varchar(1) NOT NULL,
  `pr_chiamata` varchar(1) NOT NULL,
  `pr_autogriglia` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `prodotti`
--

INSERT INTO `prodotti` (`pr_codice`, `pr_descrizione`, `pr_griglia`, `pr_chiamata`, `pr_autogriglia`) VALUES
('acqua_frizzante', 'bottiglia 1/2 litro acqua frizzante', '', '', ''),
('acqua_naturale', 'bottiglia 1/2 litro acqua naturale', '', '', ''),
('aperol', 'porzione di aperol', '', '', ''),
('bianco_frizzante_L', 'vino bianco frizzante al litro', '', '', ''),
('birra_bionda_L', 'birra bionda al litro', '', '', ''),
('campari', 'porzione di campari', '', '', ''),
('Cioccolata calda', 'Cioccolata calda', '', '', ''),
('coca_cola_L', 'coca cola al litro', '', '', ''),
('coca_cola_lattina', 'lattina di coca cola', '', '', ''),
('crepes', 'Crepes', '', '', ''),
('crudo', 'porzione di prosciutto crudo', '', '', ''),
('fanta_lattina', 'lattina di fanta', '', '', ''),
('formaggio_panino', 'porzione di formaggio per panino', '', '', ''),
('funghi', 'porzione di funghi sotto olio', '', '', ''),
('melanzane', 'porzione di melanzane ai ferri', '', '', ''),
('nutella', 'porzione nutella per dolce', '', '', ''),
('pane_hotdog', 'pane da hot dog', '', '', ''),
('pane_panino', 'pane per panino', '', '', ''),
('patate_fritte', 'Patate fritte', '', '', ''),
('peperoni_for', 'porzione di peperoni al forno', '', '', ''),
('peperoni_pia', 'peperoni alla piastra', '', '', ''),
('piadina', 'piadina', '', '', ''),
('porchetta', 'porzione di porchetta', '', '', ''),
('Salame al cioccolato', 'fetta di Salame al cioccolato\n', '', '', ''),
('spritz_aperol', 'spritz aperol', '', '', ''),
('spritz_campari', 'spirtz campari', '', '', ''),
('Vin brule', 'Vin brule', '', '', ''),
('vino_rosso_L', 'vino rosso al litro', '', '', ''),
('waffle', 'Waffle', '', '', ''),
('würstel', 'würstel', '', '', ''),
('zucchero_filato', 'Zucchero filato', '', '', ''),
('zucchine', 'porzione di zucchine ai ferri', '', '', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `serate`
--

CREATE TABLE `serate` (
  `se_numero` int(5) NOT NULL,
  `se_descrizione` varchar(40) NOT NULL,
  `se_attiva` varchar(1) NOT NULL,
  `se_data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `serate`
--

INSERT INTO `serate` (`se_numero`, `se_descrizione`, `se_attiva`, `se_data`) VALUES
(1, 'Venerdi 8 dicembre', 'S', '2017-12-08'),
(2, 'Sabato 9 dicembre', ' ', '2017-12-09'),
(3, 'Domenica 10 dicembre', ' ', '2017-12-10');

-- --------------------------------------------------------

--
-- Struttura della tabella `stile`
--

CREATE TABLE `stile` (
  `st_stile` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `stile`
--

INSERT INTO `stile` (`st_stile`) VALUES
('arancio'),
('azzurro'),
('bianco'),
('blu'),
('giallo'),
('nero'),
('rosso'),
('verde'),
('viola');

-- --------------------------------------------------------

--
-- Struttura della tabella `tavoli`
--

CREATE TABLE `tavoli` (
  `ta_id` int(3) NOT NULL,
  `ta_id_gruppo` int(2) NOT NULL,
  `ta_descrizione` varchar(20) NOT NULL,
  `ta_valore` varchar(20) NOT NULL,
  `ta_or_stato` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `tavoli`
--

INSERT INTO `tavoli` (`ta_id`, `ta_id_gruppo`, `ta_descrizione`, `ta_valore`, `ta_or_stato`) VALUES
(1, 1, 'TAVOLO A01', 'A01', 1),
(2, 1, 'TAVOLO A02', 'A02', 1),
(3, 1, 'TAVOLO A03', 'A03', 1),
(4, 1, 'TAVOLO A04', 'A04', 1),
(5, 1, 'TAVOLO A05', 'A05', 1),
(6, 1, 'TAVOLO A06', 'A06', 1),
(7, 1, 'TAVOLO A07', 'A07', 1),
(8, 1, 'TAVOLO A08', 'A08', 1),
(11, 2, 'TAVOLO A09', 'A09', 1),
(12, 2, 'TAVOLO A10', 'A10', 1),
(13, 2, 'TAVOLO A11', 'A11', 1),
(14, 2, 'TAVOLO A12', 'A12', 1),
(15, 2, 'TAVOLO A13', 'A13', 1),
(16, 2, 'TAVOLO A14', 'A14', 1),
(17, 2, 'TAVOLO A15', 'A15', 1),
(18, 2, 'TAVOLO A16', 'A16', 1),
(21, 3, 'TAVOLO B01', 'B01', 1),
(22, 3, 'TAVOLO B02', 'B02', 1),
(23, 3, 'TAVOLO B03', 'B03', 1),
(24, 3, 'TAVOLO B04', 'B04', 1),
(25, 3, 'TAVOLO B05', 'B05', 1),
(26, 3, 'TAVOLO B06', 'B06', 1),
(27, 3, 'TAVOLO B07', 'B07', 1),
(28, 3, 'TAVOLO B08', 'B08', 1),
(31, 4, 'TAVOLO B09', 'B09', 1),
(32, 4, 'TAVOLO B10', 'B10', 1),
(33, 4, 'TAVOLO B11', 'B11', 1),
(34, 4, 'TAVOLO B12', 'B12', 1),
(35, 4, 'TAVOLO B13', 'B13', 1),
(36, 4, 'TAVOLO B14', 'B14', 1),
(37, 4, 'TAVOLO B15', 'B15', 1),
(38, 4, 'TAVOLO B16', 'B16', 1),
(41, 5, 'TAVOLO C01', 'C01', 1),
(42, 5, 'TAVOLO C02', 'C02', 1),
(43, 5, 'TAVOLO C03', 'C03', 1),
(44, 5, 'TAVOLO C04', 'C04', 1),
(45, 5, 'TAVOLO C05', 'C05', 1),
(46, 5, 'TAVOLO C06', 'C06', 1),
(47, 5, 'TAVOLO C07', 'C07', 1),
(48, 5, 'TAVOLO C08', 'C08', 1),
(51, 6, 'TAVOLO C09', 'C09', 1),
(52, 6, 'TAVOLO C10', 'C10', 1),
(53, 6, 'TAVOLO C11', 'C11', 1),
(54, 6, 'TAVOLO C12', 'C12', 1),
(55, 6, 'TAVOLO C13', 'C13', 1),
(56, 6, 'TAVOLO C14', 'C14', 1),
(57, 6, 'TAVOLO C15', 'C15', 1),
(58, 6, 'TAVOLO C16', 'C16', 1),
(61, 7, 'BAR', 'BAR', 1),
(62, 7, 'BANCARELLE', 'BANCAR', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `tempi`
--

CREATE TABLE `tempi` (
  `codice` int(2) NOT NULL,
  `numero` int(5) NOT NULL,
  `tipo` varchar(20) CHARACTER SET utf8 NOT NULL,
  `descrizione` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tempi`
--

INSERT INTO `tempi` (`codice`, `numero`, `tipo`, `descrizione`) VALUES
(1, 90, 'distribuzione', 'tempo ricarica pagina distribuzione'),
(2, 60, 'elenco_ordini', 'tempo ricarica pagina elenco ordini'),
(3, 2, 'abbina', 'tempo ricarica pagina abbina'),
(4, 30, 'griglie', 'tempo ricarica pagina griglie');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `ut_nome` varchar(20) NOT NULL,
  `ut_password` varchar(32) NOT NULL,
  `ut_tipo` varchar(20) NOT NULL,
  `ut_homepage` varchar(50) NOT NULL,
  `ut_cartella` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`ut_nome`, `ut_password`, `ut_tipo`, `ut_homepage`, `ut_cartella`) VALUES
('cassa1', '8a24b05d5bfc454b6582eea94733fb2c', 'admin', 'index.php', 'cassa1'),
('davide', '4e2df6946c768f7879aac432b1d98b15', 'admin', 'index.php', 'cassa1');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `abilitazione`
--
ALTER TABLE `abilitazione`
  ADD PRIMARY KEY (`ab_gruppo`,`ab_tipoutente`),
  ADD KEY `ab_gruppo` (`ab_gruppo`);

--
-- Indici per le tabelle `articoli`
--
ALTER TABLE `articoli`
  ADD PRIMARY KEY (`ar_codice`),
  ADD KEY `ar_stile` (`ar_stile`),
  ADD KEY `ar_gruppo` (`ar_gruppo`);

--
-- Indici per le tabelle `distinta`
--
ALTER TABLE `distinta`
  ADD PRIMARY KEY (`di_codicearticolo`,`di_codiceprodotto`),
  ADD KEY `di_codiceprodotto` (`di_codiceprodotto`);

--
-- Indici per le tabelle `griglie`
--
ALTER TABLE `griglie`
  ADD PRIMARY KEY (`gri_serata`,`gri_prodotto`),
  ADD KEY `gl_serata` (`gri_serata`),
  ADD KEY `gl_prodotto` (`gri_prodotto`);

--
-- Indici per le tabelle `griglie_legami`
--
ALTER TABLE `griglie_legami`
  ADD KEY `leg_prodotto1` (`leg_prodotto1`),
  ADD KEY `leg_prodotto2` (`leg_prodotto2`);

--
-- Indici per le tabelle `gruppi`
--
ALTER TABLE `gruppi`
  ADD PRIMARY KEY (`gr_codice`),
  ADD KEY `gr_stile` (`gr_stile`);

--
-- Indici per le tabelle `gruppi_magazzino`
--
ALTER TABLE `gruppi_magazzino`
  ADD PRIMARY KEY (`gr_ma_codice`);

--
-- Indici per le tabelle `gruppi_tavoli`
--
ALTER TABLE `gruppi_tavoli`
  ADD PRIMARY KEY (`gr_ta_id`);

--
-- Indici per le tabelle `magazzino`
--
ALTER TABLE `magazzino`
  ADD PRIMARY KEY (`ma_codiceprodotto`),
  ADD KEY `ma_unita_misura` (`ma_unita_misura`),
  ADD KEY `ma_gruppi` (`ma_gruppi`);

--
-- Indici per le tabelle `ordini`
--
ALTER TABLE `ordini`
  ADD PRIMARY KEY (`or_numero`),
  ADD KEY `or_serata` (`or_serata`),
  ADD KEY `or_cassa` (`or_cassa`);

--
-- Indici per le tabelle `ordinirighe`
--
ALTER TABLE `ordinirighe`
  ADD PRIMARY KEY (`ri_ordine`,`ri_riga`),
  ADD KEY `ri_codice` (`ri_codice`);

--
-- Indici per le tabelle `prodotti`
--
ALTER TABLE `prodotti`
  ADD PRIMARY KEY (`pr_codice`);

--
-- Indici per le tabelle `serate`
--
ALTER TABLE `serate`
  ADD PRIMARY KEY (`se_numero`);

--
-- Indici per le tabelle `stile`
--
ALTER TABLE `stile`
  ADD PRIMARY KEY (`st_stile`);

--
-- Indici per le tabelle `tavoli`
--
ALTER TABLE `tavoli`
  ADD PRIMARY KEY (`ta_id`),
  ADD UNIQUE KEY `ta_valore` (`ta_valore`),
  ADD KEY `ta_id_gruppo` (`ta_id_gruppo`);

--
-- Indici per le tabelle `tempi`
--
ALTER TABLE `tempi`
  ADD PRIMARY KEY (`codice`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`ut_nome`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `abilitazione`
--
ALTER TABLE `abilitazione`
  ADD CONSTRAINT `abilitazione_ibfk_1` FOREIGN KEY (`ab_gruppo`) REFERENCES `gruppi` (`gr_codice`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `articoli`
--
ALTER TABLE `articoli`
  ADD CONSTRAINT `articoli_ibfk_1` FOREIGN KEY (`ar_stile`) REFERENCES `stile` (`st_stile`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articoli_ibfk_2` FOREIGN KEY (`ar_gruppo`) REFERENCES `gruppi` (`gr_codice`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `distinta`
--
ALTER TABLE `distinta`
  ADD CONSTRAINT `distinta_ibfk_1` FOREIGN KEY (`di_codicearticolo`) REFERENCES `articoli` (`ar_codice`) ON UPDATE CASCADE,
  ADD CONSTRAINT `distinta_ibfk_2` FOREIGN KEY (`di_codiceprodotto`) REFERENCES `prodotti` (`pr_codice`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `griglie`
--
ALTER TABLE `griglie`
  ADD CONSTRAINT `griglie_ibfk_1` FOREIGN KEY (`gri_serata`) REFERENCES `serate` (`se_numero`),
  ADD CONSTRAINT `griglie_ibfk_2` FOREIGN KEY (`gri_prodotto`) REFERENCES `prodotti` (`pr_codice`);

--
-- Limiti per la tabella `griglie_legami`
--
ALTER TABLE `griglie_legami`
  ADD CONSTRAINT `griglie_legami_ibfk_1` FOREIGN KEY (`leg_prodotto1`) REFERENCES `prodotti` (`pr_codice`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `griglie_legami_ibfk_2` FOREIGN KEY (`leg_prodotto2`) REFERENCES `prodotti` (`pr_codice`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `gruppi`
--
ALTER TABLE `gruppi`
  ADD CONSTRAINT `gruppi_ibfk_1` FOREIGN KEY (`gr_stile`) REFERENCES `stile` (`st_stile`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `magazzino`
--
ALTER TABLE `magazzino`
  ADD CONSTRAINT `magazzino_ibfk_1` FOREIGN KEY (`ma_codiceprodotto`) REFERENCES `prodotti` (`pr_codice`) ON UPDATE CASCADE,
  ADD CONSTRAINT `magazzino_ibfk_2` FOREIGN KEY (`ma_gruppi`) REFERENCES `gruppi_magazzino` (`gr_ma_codice`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `ordini_ibfk_1` FOREIGN KEY (`or_serata`) REFERENCES `serate` (`se_numero`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ordini_ibfk_2` FOREIGN KEY (`or_cassa`) REFERENCES `utenti` (`ut_nome`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordinirighe`
--
ALTER TABLE `ordinirighe`
  ADD CONSTRAINT `ordinirighe_ibfk_1` FOREIGN KEY (`ri_ordine`) REFERENCES `ordini` (`or_numero`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ordinirighe_ibfk_2` FOREIGN KEY (`ri_codice`) REFERENCES `articoli` (`ar_codice`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tavoli`
--
ALTER TABLE `tavoli`
  ADD CONSTRAINT `tavoli_ibfk_1` FOREIGN KEY (`ta_id_gruppo`) REFERENCES `gruppi_tavoli` (`gr_ta_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
