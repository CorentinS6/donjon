-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 19 Avril 2013 à 14:43
-- Version du serveur: 5.5.29-0ubuntu0.12.10.1
-- Version de PHP: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `donjon1`
--

-- --------------------------------------------------------

--
-- Structure de la table `AIDE`
--

CREATE TABLE IF NOT EXISTS `AIDE` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TITRE` varchar(45) NOT NULL,
  `TEXTE` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `AVENTURIER`
--

CREATE TABLE IF NOT EXISTS `AVENTURIER` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idMEMBRE` int(11) NOT NULL,
  `idCREATURE` int(11) NOT NULL,
  `NOM` varchar(45) NOT NULL,
  `ACROBATIE` smallint(5) unsigned NOT NULL,
  `BAGARRE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CHARME` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ACUITE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `AGE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `EXPERIENCE` int(10) unsigned NOT NULL DEFAULT '0',
  `RENOMMEE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `RENOMMEE_MAX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `PVIE` smallint(5) unsigned NOT NULL DEFAULT '50',
  `PVIE_MAX` smallint(5) unsigned NOT NULL DEFAULT '50',
  `ARGENT` float(12,2) unsigned NOT NULL DEFAULT '20.00',
  `MANA` smallint(5) unsigned NOT NULL DEFAULT '0',
  `MANA_MAX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `PACT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `PDEP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `POSITION` varchar(45) DEFAULT NULL,
  `idPIECE` int(11) unsigned DEFAULT NULL,
  `POSX` int(15) DEFAULT '0',
  `POSY` int(15) DEFAULT '0',
  `POUVOIRS` varchar(45) DEFAULT NULL,
  `TALENTS` varchar(255) DEFAULT NULL,
  `ENVOUTEMENT` varchar(255) DEFAULT NULL,
  `POINTS` int(5) unsigned NOT NULL DEFAULT '0',
  `ETAT` int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_AVENTURIER_CREATURE1` (`idCREATURE`),
  KEY `fk_AVENTURIER_PIECE1` (`idPIECE`),
  KEY `fk_AVENTURIER_MEMBRE1` (`idMEMBRE`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `AVENTURIER`
--

INSERT INTO `AVENTURIER` (`id`, `idMEMBRE`, `idCREATURE`, `NOM`, `ACROBATIE`, `BAGARRE`, `CHARME`, `ACUITE`, `AGE`, `EXPERIENCE`, `RENOMMEE`, `RENOMMEE_MAX`, `PVIE`, `PVIE_MAX`, `ARGENT`, `MANA`, `MANA_MAX`, `PACT`, `PDEP`, `POSITION`, `idPIECE`, `POSX`, `POSY`, `POUVOIRS`, `TALENTS`, `ENVOUTEMENT`, `POINTS`, `ETAT`) VALUES
(1, 3, 4, 'Falagern', 59, 211, 60, 55, 28, 841, 0, 0, 25, 25, 12150.49, 0, 0, 29, 24, '{9,14}', 1, NULL, NULL, NULL, NULL, NULL, 0, 1),
(2, 3, 1, 'glascon', 0, 51, 10, 14, 28, 219, 0, 0, 999, 21, 99145.00, 0, 0, 30, 30, '{36,7}', 2, 36, 7, NULL, NULL, NULL, 0, 1),
(3, 3, 3, 'test', 0, 0, 10, 10, 0, 0, 0, 0, 9, 9, 30.00, 0, 0, 30, 1, '{5,5}', 1, 0, 0, NULL, NULL, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `BANQUE`
--

CREATE TABLE IF NOT EXISTS `BANQUE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAVENTURIER` int(11) unsigned NOT NULL,
  `TAILLE_COFFRE` int(11) NOT NULL,
  `ARGENT` float(12,2) NOT NULL,
  `ARGENT_MAX` float(12,2) NOT NULL,
  `COUT` float(12,2) NOT NULL,
  `ETAT` enum('Fermé','Ouvert','Suspendu') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_BANQUE_AVENTURIER1` (`idAVENTURIER`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `BESTIAIRE`
--

CREATE TABLE IF NOT EXISTS `BESTIAIRE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCREATURE` int(11) NOT NULL,
  `idDONJON` int(11) unsigned DEFAULT NULL,
  `PRENOM` varchar(45) NOT NULL,
  `ACROBATIE` smallint(6) NOT NULL,
  `BAGARRE` smallint(6) NOT NULL,
  `CHARME` smallint(6) NOT NULL,
  `ACUITE` smallint(6) NOT NULL,
  `AGE` smallint(6) NOT NULL,
  `EXPERIENCE` int(11) NOT NULL,
  `RENOMMEE` smallint(6) NOT NULL,
  `PVIE` smallint(6) NOT NULL,
  `PVIE_MAX` smallint(6) NOT NULL,
  `REPOS` tinyint(4) NOT NULL DEFAULT '1',
  `A_VENDRE` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `COUT` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `idPIECE` int(11) unsigned DEFAULT NULL,
  `POSITION` varchar(45) DEFAULT NULL,
  `POUVOIRS` varchar(45) DEFAULT NULL,
  `TALENTS` varchar(255) DEFAULT NULL,
  `ENVOUTEMENT` varchar(255) DEFAULT NULL,
  `ORDRE` varchar(255) DEFAULT NULL,
  `POINTS` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_DONJON_has_MONSTRE_DONJON1` (`idDONJON`),
  KEY `fk_BESTIAIRE_CREATURE1` (`idCREATURE`),
  KEY `fk_BESTIAIRE_PIECE1` (`idPIECE`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=104 ;

--
-- Contenu de la table `BESTIAIRE`
--

INSERT INTO `BESTIAIRE` (`id`, `idCREATURE`, `idDONJON`, `PRENOM`, `ACROBATIE`, `BAGARRE`, `CHARME`, `ACUITE`, `AGE`, `EXPERIENCE`, `RENOMMEE`, `PVIE`, `PVIE_MAX`, `REPOS`, `A_VENDRE`, `COUT`, `idPIECE`, `POSITION`, `POUVOIRS`, `TALENTS`, `ENVOUTEMENT`, `ORDRE`, `POINTS`) VALUES
(1, 11, NULL, 'Pouâcre', 0, 50, 10, 10, 36, 50, 0, 27, 27, 1, 0, '0.01', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 11, 1, 'Pouâcre', 0, 50, 10, 10, 36, 10, 0, 27, 27, 1, 0, '0.01', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(4, 11, 1, 'Pouâcre', 0, 50, 10, 10, 36, 76, 0, 49, 27, 1, 0, '0.01', NULL, NULL, NULL, NULL, NULL, NULL, 10),
(5, 11, 1, 'Pouâcre', 0, 50, 10, 10, 36, 473, 0, 1398, 27, 1, 0, '0.01', 1, '{12,10}', NULL, NULL, NULL, NULL, 35),
(6, 3, 1, 'Gobelin', 0, 0, 10, 10, 36, 0, 0, 10, 10, 1, 0, '0.01', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(9, 8, NULL, 'Saurien', 10, 50, 10, 10, 28, 0, 0, 19, 19, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(12, 4, 1, 'Kochaque', 50, 10, 10, 10, 20, 0, 0, 17, 17, 1, 0, '0.01', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(15, 8, NULL, 'Saurien', 10, 50, 10, 10, 28, 0, 0, 11, 11, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(16, 22, NULL, 'Troll cavernicole', 0, 10, 0, 10, 22, 0, 0, 62, 62, 0, 1, '5.00', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(17, 22, 1, 'Troll cavernicole', 0, 10, 0, 10, 23, 0, 0, 55, 55, 1, 0, '5.00', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(18, 22, NULL, 'Troll cavernicole', 0, 10, 0, 10, 25, 0, 0, 65, 65, 0, 1, '5.00', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(20, 20, 1, 'Dynamiteur', 10, 10, 0, 10, 27, 0, 0, 10, 10, 1, 0, '0.01', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(21, 2, 1, 'Amphibie', 10, 10, 0, 10, 24, 0, 0, 18, 18, 1, 0, '0.01', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(23, 23, 1, 'Troll sylvestre', 10, 50, 0, 10, 21, 0, 0, 21, 21, 1, 0, '3.00', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(24, 14, 1, 'Petite claqueuse', 10, 10, 10, 10, 19, 0, 0, 10, 10, 1, 0, '0.01', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(26, 21, NULL, 'Dragon', 50, 100, 10, 100, 27, 0, 0, 42, 42, 0, 1, '1.00', NULL, '{V}', 'vol', NULL, NULL, NULL, 0),
(27, 14, NULL, 'Petite claqueuse', 10, 10, 10, 10, 28, 0, 0, 12, 12, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(29, 12, NULL, 'Faucheuse verte', 100, 50, 10, 10, 22, 0, 0, 2, 2, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(30, 18, 1, 'Petit Gobelin', 0, 0, 10, 10, 24, 0, 0, 13, 13, 0, 0, '0.01', 1, '{0,0}', NULL, NULL, NULL, NULL, 0),
(31, 22, NULL, 'Troll cavernicole', 0, 10, 0, 10, 28, 0, 0, 64, 64, 0, 1, '5.00', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(32, 25, NULL, 'Petit Moussu', 10, 10, 10, 10, 25, 0, 0, 18, 18, 0, 1, '0.20', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(35, 17, NULL, 'Chasseur', 10, 10, 0, 0, 24, 0, 0, 12, 12, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(37, 6, NULL, 'Oiseau', 10, 0, 50, 10, 22, 0, 0, 12, 12, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(38, 2, NULL, 'Amphibie', 10, 10, 0, 10, 23, 0, 0, 17, 17, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(39, 20, NULL, 'Dynamiteur', 10, 10, 0, 10, 22, 0, 0, 9, 9, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(40, 19, NULL, 'Grand Gobelin', 0, 0, 10, 10, 22, 0, 0, 16, 16, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(42, 16, NULL, 'Capuchon', 10, 10, 0, 10, 20, 0, 0, 17, 17, 0, 1, '0.10', NULL, '{V}', 'vol', NULL, NULL, NULL, 0),
(43, 12, NULL, 'Faucheuse verte', 100, 50, 10, 10, 24, 0, 0, 2, 2, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(44, 5, NULL, 'Lapin', 0, 0, 10, 10, 27, 0, 0, 15, 15, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(45, 14, NULL, 'Petite claqueuse', 10, 10, 10, 10, 27, 0, 0, 9, 9, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(46, 4, NULL, 'Kochaque', 50, 10, 10, 10, 26, 0, 0, 11, 11, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(47, 21, NULL, 'Dragon', 50, 100, 10, 100, 23, 0, 0, 49, 49, 0, 1, '1.00', NULL, '{V}', 'vol', NULL, NULL, NULL, 0),
(48, 17, NULL, 'Chasseur', 10, 10, 0, 0, 28, 0, 0, 11, 11, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(49, 23, NULL, 'Troll sylvestre', 10, 50, 0, 10, 26, 0, 0, 29, 29, 0, 1, '3.00', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(50, 10, NULL, 'Macasseu', 0, 50, 10, 10, 27, 0, 0, 25, 25, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(51, 13, NULL, 'Grande claqueuse', 0, 50, 10, 10, 23, 0, 0, 18, 18, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(52, 22, NULL, 'Troll cavernicole', 0, 10, 0, 10, 19, 0, 0, 53, 53, 0, 1, '5.00', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(53, 13, NULL, 'Grande claqueuse', 0, 50, 10, 10, 20, 0, 0, 19, 19, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(54, 16, NULL, 'Capuchon', 10, 10, 0, 10, 27, 0, 0, 19, 19, 0, 1, '0.10', NULL, '{V}', 'vol', NULL, NULL, NULL, 0),
(55, 3, NULL, 'Gobelin', 0, 0, 10, 10, 28, 0, 0, 6, 6, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(56, 22, NULL, 'Troll cavernicole', 0, 10, 0, 10, 20, 0, 0, 63, 63, 0, 1, '5.00', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(57, 15, NULL, 'Pseudopode', 0, 25, 0, 50, 18, 0, 0, 15, 15, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(59, 10, NULL, 'Macasseu', 0, 50, 10, 10, 24, 0, 0, 17, 17, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(60, 6, NULL, 'Oiseau', 10, 0, 50, 10, 23, 0, 0, 20, 20, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(62, 4, NULL, 'Kochaque', 50, 10, 10, 10, 24, 0, 0, 14, 14, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(63, 3, NULL, 'Gobelin', 0, 0, 10, 10, 21, 0, 0, 7, 7, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(64, 11, NULL, 'PouÃ¢cre', 0, 50, 10, 10, 19, 0, 0, 22, 22, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(65, 23, NULL, 'Troll sylvestre', 10, 50, 0, 10, 23, 0, 0, 28, 28, 0, 1, '3.00', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(66, 24, NULL, 'Grand Moussu', 0, 10, 0, 0, 27, 0, 0, 28, 28, 0, 1, '0.50', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(67, 18, NULL, 'Petit Gobelin', 0, 0, 10, 10, 22, 0, 0, 5, 5, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(68, 17, NULL, 'Chasseur', 10, 10, 0, 0, 24, 0, 0, 17, 17, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(70, 25, NULL, 'Petit Moussu', 10, 10, 10, 10, 21, 0, 0, 16, 16, 0, 1, '0.20', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(71, 25, NULL, 'Petit Moussu', 10, 10, 10, 10, 24, 0, 0, 13, 13, 0, 1, '0.20', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(72, 5, NULL, 'Lapin', 0, 0, 10, 10, 19, 0, 0, 12, 12, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(73, 15, NULL, 'Pseudopode', 0, 25, 0, 50, 21, 0, 0, 12, 12, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(75, 6, NULL, 'Oiseau', 10, 0, 50, 10, 19, 0, 0, 17, 17, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(76, 1, NULL, 'Babare', 0, 10, 10, 10, 27, 0, 0, 24, 24, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(77, 10, NULL, 'Macasseu', 0, 50, 10, 10, 23, 0, 0, 25, 25, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(78, 22, NULL, 'Troll cavernicole', 0, 10, 0, 10, 23, 0, 0, 61, 61, 0, 1, '5.00', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(79, 18, NULL, 'Petit Gobelin', 0, 0, 10, 10, 26, 0, 0, 13, 13, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(80, 2, NULL, 'Amphibie', 10, 10, 0, 10, 19, 0, 0, 14, 14, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(81, 11, NULL, 'PouÃ¢cre', 0, 50, 10, 10, 21, 0, 0, 28, 28, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(82, 7, NULL, 'Olf', 10, 10, 0, 10, 27, 0, 0, 20, 20, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(83, 4, NULL, 'Kochaque', 50, 10, 10, 10, 22, 0, 0, 17, 17, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(84, 14, NULL, 'Petite claqueuse', 10, 10, 10, 10, 22, 0, 0, 13, 13, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(85, 19, NULL, 'Grand Gobelin', 0, 0, 10, 10, 24, 0, 0, 8, 8, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(86, 23, NULL, 'Troll sylvestre', 10, 50, 0, 10, 25, 0, 0, 21, 21, 0, 1, '3.00', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(87, 10, NULL, 'Macasseu', 0, 50, 10, 10, 21, 0, 0, 18, 18, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(88, 8, NULL, 'Saurien', 10, 50, 10, 10, 21, 0, 0, 17, 17, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(89, 16, NULL, 'Capuchon', 10, 10, 0, 10, 25, 0, 0, 18, 18, 0, 1, '0.10', NULL, '{V}', 'vol', NULL, NULL, NULL, 0),
(90, 15, NULL, 'Pseudopode', 0, 25, 0, 50, 20, 0, 0, 15, 15, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(91, 20, NULL, 'Dynamiteur', 10, 10, 0, 10, 27, 0, 0, 15, 15, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(92, 24, NULL, 'Grand Moussu', 0, 10, 0, 0, 27, 0, 0, 22, 22, 0, 1, '0.50', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(93, 14, NULL, 'Petite claqueuse', 10, 10, 10, 10, 27, 0, 0, 11, 11, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(94, 25, NULL, 'Petit Moussu', 10, 10, 10, 10, 25, 0, 0, 18, 18, 0, 1, '0.20', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(95, 25, NULL, 'Petit Moussu', 10, 10, 10, 10, 23, 0, 0, 18, 18, 0, 1, '0.20', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(96, 20, NULL, 'Dynamiteur', 10, 10, 0, 10, 25, 0, 0, 13, 13, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(97, 6, NULL, 'Oiseau', 10, 0, 50, 10, 24, 0, 0, 14, 14, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(98, 1, NULL, 'Babare', 0, 10, 10, 10, 24, 0, 0, 30, 30, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(99, 16, NULL, 'Capuchon', 10, 10, 0, 10, 23, 0, 0, 20, 20, 0, 1, '0.10', NULL, '{V}', 'vol', NULL, NULL, NULL, 0),
(100, 16, NULL, 'Capuchon', 10, 10, 0, 10, 19, 0, 0, 14, 14, 0, 1, '0.10', NULL, '{V}', 'vol', NULL, NULL, NULL, 0),
(101, 9, NULL, 'Serpentaire', 10, 10, 10, 10, 23, 0, 0, 6, 6, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(102, 9, NULL, 'Serpentaire', 10, 10, 10, 10, 24, 0, 0, 12, 12, 0, 1, '0.01', NULL, '{V}', NULL, NULL, NULL, NULL, 0),
(103, 21, NULL, 'Dragon', 50, 100, 10, 100, 21, 0, 0, 40, 40, 0, 1, '1.00', NULL, '{V}', 'vol', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `CHATMSG`
--

CREATE TABLE IF NOT EXISTS `CHATMSG` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MTIME` int(20) NOT NULL,
  `AUTEUR` varchar(10) DEFAULT '',
  `DESTINATAIRE` varchar(10) DEFAULT '',
  `MESSAGE` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `CHATMSG`
--

INSERT INTO `CHATMSG` (`id`, `MTIME`, `AUTEUR`, `DESTINATAIRE`, `MESSAGE`) VALUES
(1, 1364491230, '1:d', '', 'test'),
(2, 1364569559, '1:donjon', NULL, 'test2'),
(3, 1364569620, '1:donjon', NULL, 'test2'),
(4, 1364569826, '1:donjon', NULL, 'test2'),
(5, 1364569877, '1:donjon', NULL, 'test2'),
(6, 1364569921, '1:donjon', NULL, 'test2'),
(7, 1364569933, '1:donjon', NULL, 'test2'),
(8, 1364569976, '1:donjon', NULL, 'coucou'),
(9, 1364570221, '1:donjon', NULL, 'coucou');

-- --------------------------------------------------------

--
-- Structure de la table `CONNECTE`
--

CREATE TABLE IF NOT EXISTS `CONNECTE` (
  `id` varchar(10) NOT NULL,
  `LASTTIME` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `CREATURE`
--

CREATE TABLE IF NOT EXISTS `CREATURE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPARENT` int(11) DEFAULT NULL,
  `NOM` varchar(255) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL DEFAULT '',
  `INTELLIGENTE` tinyint(1) NOT NULL DEFAULT '1',
  `ACROBATIE` smallint(6) NOT NULL,
  `BAGARRE` smallint(6) NOT NULL,
  `CHARME` smallint(6) NOT NULL,
  `ACUITE` smallint(6) NOT NULL,
  `VIE` varchar(45) NOT NULL,
  `DEGAT` varchar(45) NOT NULL,
  `UNIQUE` tinyint(1) NOT NULL,
  `POUVOIRS` varchar(45) DEFAULT NULL,
  `PRIX_ACHAT` float(12,2) NOT NULL DEFAULT '1.00',
  `PRIX_ENTRETIEN` float(12,2) NOT NULL DEFAULT '0.01',
  PRIMARY KEY (`id`),
  KEY `fk_CREATURE_CREATURE1` (`idPARENT`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Contenu de la table `CREATURE`
--

INSERT INTO `CREATURE` (`id`, `idPARENT`, `NOM`, `DESCRIPTION`, `INTELLIGENTE`, `ACROBATIE`, `BAGARRE`, `CHARME`, `ACUITE`, `VIE`, `DEGAT`, `UNIQUE`, `POUVOIRS`, `PRIX_ACHAT`, `PRIX_ENTRETIEN`) VALUES
(1, NULL, 'Babare', '', 1, 0, 10, 10, 10, '20+1D', '1D', 0, NULL, 1.00, 0.01),
(2, NULL, 'Amphibie', '', 1, 10, 10, 0, 10, '10+1D', '2D', 0, NULL, 1.00, 0.01),
(3, NULL, 'Gobelin', '', 1, 0, 0, 10, 10, '5+1D', '1D', 0, NULL, 1.00, 0.01),
(4, NULL, 'Kochaque', '', 1, 50, 10, 10, 10, '10+1D', '1D', 0, NULL, 1.00, 0.01),
(5, NULL, 'Lapin', '', 1, 0, 0, 10, 10, '10+1D', '1D', 0, NULL, 1.00, 0.01),
(6, NULL, 'Oiseau', '', 1, 10, 0, 50, 10, '10+1D', '1D', 0, NULL, 1.00, 0.01),
(7, NULL, 'Olf', '', 1, 10, 10, 0, 10, '10+1D', '1D', 0, NULL, 1.00, 0.01),
(8, NULL, 'Saurien', '', 1, 10, 50, 10, 10, '10+1D', '1D', 0, NULL, 1.00, 0.01),
(9, NULL, 'Serpentaire', 'Charognard ailé des marais', 0, 10, 10, 10, 10, '5+1D', '1D', 0, NULL, 1.00, 0.01),
(10, NULL, 'Macasseu', 'Prédateur des marais', 0, 0, 50, 10, 10, '10+1D15', '2D', 0, NULL, 1.00, 0.01),
(11, NULL, 'Pouâcre', 'Grand amphibi des marais. Omnivore tenace.', 0, 0, 50, 10, 10, '20+1D15', '2D', 0, NULL, 10.00, 0.01),
(12, NULL, 'Faucheuse verte', 'Gros insecte carnivore.', 0, 100, 50, 10, 10, '2', '1', 0, NULL, 1.00, 0.01),
(13, NULL, 'Grande claqueuse', 'Immense araignée des profondeurs.', 0, 0, 50, 10, 10, '10+1D', '1D', 0, NULL, 1.00, 0.01),
(14, NULL, 'Petite claqueuse', 'Petite araignée des profondeurs', 0, 10, 10, 10, 10, '3+1D', '2', 0, NULL, 1.00, 0.01),
(15, NULL, 'Pseudopode', 'Créature des profondeurs. Surprennant leurs ennemis en leurs tombant dessus.', 0, 0, 25, 0, 50, '10+1D', '4D', 0, NULL, 1.00, 0.01),
(16, NULL, 'Capuchon', '', 1, 10, 10, 0, 10, '10+1D', '2D', 0, 'vol', 50.00, 0.10),
(17, NULL, 'Chasseur', '', 1, 10, 10, 0, 0, '10+1D', '1D', 0, NULL, 30.00, 0.01),
(18, 3, 'Petit Gobelin', '', 1, 0, 0, 10, 10, '1D+3', '1D', 0, NULL, 1.00, 0.01),
(19, 3, 'Grand Gobelin', '', 1, 0, 0, 10, 10, '7+1D', '1D', 0, NULL, 1.00, 0.01),
(20, NULL, 'Dynamiteur', '', 1, 10, 10, 0, 10, '5+1D', '1D', 0, NULL, 1.00, 0.01),
(21, 8, 'Dragon', 'Garder le trésor !', 1, 50, 100, 10, 100, '30+2D', '3D', 0, 'vol', 100.00, 1.00),
(22, NULL, 'Troll cavernicole', '', 1, 0, 10, 0, 10, '40+4D', '5D+10', 0, NULL, 200.00, 5.00),
(23, NULL, 'Troll sylvestre', 'Troll des bois', 1, 10, 50, 0, 10, '20+1D', '2D+4', 0, NULL, 100.00, 3.00),
(24, NULL, 'Grand Moussu', 'Grand mouss !', 1, 0, 10, 0, 0, '20+1D', '3D+10', 0, NULL, 50.00, 0.50),
(25, NULL, 'Petit Moussu', 'Petit moussu', 1, 10, 10, 10, 10, '10+1D', '2D', 0, NULL, 10.00, 0.20);

-- --------------------------------------------------------

--
-- Structure de la table `DONJON`
--

CREATE TABLE IF NOT EXISTS `DONJON` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idMEMBRE` int(11) NOT NULL,
  `DATE_CREATION` date NOT NULL,
  `DATE_OUVERTURE` date DEFAULT NULL,
  `NOM` varchar(45) NOT NULL,
  `DESCRIPTION` mediumtext,
  `ARGENT` float(12,2) unsigned NOT NULL,
  `ETAT` smallint(5) NOT NULL,
  `PACT` smallint(5) unsigned NOT NULL,
  `RENOMMEE` smallint(5) unsigned NOT NULL,
  `RENOMMEE_MAX` smallint(5) unsigned NOT NULL,
  `EXPERIENCE` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_DONJON_MEMBRE1` (`idMEMBRE`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `DONJON`
--

INSERT INTO `DONJON` (`id`, `idMEMBRE`, `DATE_CREATION`, `DATE_OUVERTURE`, `NOM`, `DESCRIPTION`, `ARGENT`, `ETAT`, `PACT`, `RENOMMEE`, `RENOMMEE_MAX`, `EXPERIENCE`) VALUES
(1, 3, '2013-01-01', '2013-03-01', 'Abysse pourpre de la dévastation', 'tagada', 31409.78, 2, 17, 0, 0, 83),
(2, 3, '2012-12-09', NULL, 'Terres glacées', '<p>Niveau minimum conseill&eacute; : 1</p>\r\n<p>Niveau maximum : 10 (sinon, &ccedil;a va &ecirc;tre ennuyeux).</p>\r\n<p>&nbsp;</p>\r\n<p>Terres glac&eacute;es !</p>', 0.00, 2, 30, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `ETAGE`
--

CREATE TABLE IF NOT EXISTS `ETAGE` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idDONJON` int(11) unsigned NOT NULL,
  `NIVEAU` smallint(5) NOT NULL DEFAULT '0',
  `NOM` varchar(255) NOT NULL DEFAULT 'Etage',
  `TAILLE` int(3) unsigned NOT NULL DEFAULT '60',
  PRIMARY KEY (`id`),
  KEY `idDONJON` (`idDONJON`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `ETAGE`
--

INSERT INTO `ETAGE` (`id`, `idDONJON`, `NIVEAU`, `NOM`, `TAILLE`) VALUES
(1, 1, -1, '1er sous-sol', 80),
(2, 1, 0, 'Rez de chaussé', 80),
(3, 1, 1, '1er étage', 80),
(4, 1, 2, '2ème étage', 80),
(5, 1, -2, '2ème sous-sol', 80),
(6, 1, 3, '3ème étage', 80),
(7, 1, 4, '4ème étage', 80),
(8, 2, -1, '1er sous-sol', 60),
(9, 2, 0, 'Rez de chaussé', 60),
(10, 2, 1, '1er étage', 60),
(11, 2, 2, '2ème étage', 60),
(12, 2, 3, '3ème étage', 60),
(15, 1, -3, '3ème sous-sol', 60),
(16, 1, -4, '4ème sous-sol', 60),
(17, 1, -5, '5ème sous-sol', 60),
(18, 1, 5, '5ème étage', 60),
(19, 1, 6, 'La presence mortelle', 60);

-- --------------------------------------------------------

--
-- Structure de la table `EVENEMENT`
--

CREATE TABLE IF NOT EXISTS `EVENEMENT` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DEST` varchar(10) NOT NULL,
  `CONTENT` longtext NOT NULL,
  `DT` datetime NOT NULL,
  `LU` int(1) unsigned NOT NULL DEFAULT '0',
  `CAT` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `EVENEMENT`
--

INSERT INTO `EVENEMENT` (`id`, `DEST`, `CONTENT`, `DT`, `LU`, `CAT`) VALUES
(1, '1:d', 'tagaf', '2013-03-28 04:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `fos_group`
--

CREATE TABLE IF NOT EXISTS `fos_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_4B019DDB5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `fos_user_user_group`
--

CREATE TABLE IF NOT EXISTS `fos_user_user_group` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `IDX_B3C77447A76ED395` (`user_id`),
  KEY `IDX_B3C77447FE54D947` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `INVENTAIRE`
--

CREATE TABLE IF NOT EXISTS `INVENTAIRE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idOBJETS` int(11) unsigned NOT NULL,
  `idDONJON` int(11) unsigned DEFAULT NULL,
  `idAVENTURIER` int(11) unsigned DEFAULT NULL,
  `idBESTIAIRE` int(11) DEFAULT NULL,
  `idCOMPTE` int(11) DEFAULT NULL,
  `NOM` varchar(255) NOT NULL,
  `AGE` smallint(6) NOT NULL DEFAULT '0',
  `BONUS` varchar(255) DEFAULT NULL,
  `USURE` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `QUALITE` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `idPIECE` int(11) unsigned DEFAULT NULL,
  `POSITION` varchar(45) DEFAULT NULL,
  `ENVOUTEMENT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_OBJETS_has_PIECE_OBJETS1` (`idOBJETS`),
  KEY `fk_INVENTAIRE_DONJON1` (`idDONJON`),
  KEY `fk_INVENTAIRE_AVENTURIER1` (`idAVENTURIER`),
  KEY `fk_INVENTAIRE_BESTIAIRE1` (`idBESTIAIRE`),
  KEY `fk_INVENTAIRE_BANQUE1` (`idCOMPTE`),
  KEY `fk_INVENTAIRE_PIECE1` (`idPIECE`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=297 ;

--
-- Contenu de la table `INVENTAIRE`
--

INSERT INTO `INVENTAIRE` (`id`, `idOBJETS`, `idDONJON`, `idAVENTURIER`, `idBESTIAIRE`, `idCOMPTE`, `NOM`, `AGE`, `BONUS`, `USURE`, `QUALITE`, `idPIECE`, `POSITION`, `ENVOUTEMENT`) VALUES
(1, 11, 1, NULL, NULL, NULL, 'Epée', 36, NULL, 0, 1, NULL, '{I}', NULL),
(2, 11, NULL, 1, NULL, NULL, 'Epée', 36, NULL, 0, 2, NULL, '{EAD}', NULL),
(3, 3, NULL, 2, NULL, NULL, 'Nunchaku', 36, NULL, 0, 1, NULL, '{I}', NULL),
(4, 17, NULL, 1, NULL, NULL, 'Main gauche', 36, NULL, 0, 1, NULL, '{I}', NULL),
(7, 20, 1, NULL, NULL, NULL, 'Wakizashi', 36, NULL, 0, 1, 7, '{V}', NULL),
(8, 6, NULL, 1, NULL, NULL, 'Massue', 36, NULL, 0, 1, NULL, '{V}', NULL),
(11, 14, NULL, 2, NULL, NULL, 'Hache', 36, NULL, 0, 1, NULL, '{I}', NULL),
(15, 19, 1, NULL, NULL, NULL, 'Sabre', 36, NULL, 0, 1, 1, '{6,9}', NULL),
(22, 12, 1, NULL, NULL, NULL, 'Epée courte', 36, NULL, 0, 1, 1, '{4,8}', NULL),
(25, 43, 1, NULL, NULL, NULL, 'Armure lourde', 36, NULL, 0, 1, NULL, '{I}', NULL),
(28, 42, NULL, 1, NULL, NULL, 'Armure moyenne', 36, NULL, 0, 1, NULL, '{ECA}', NULL),
(29, 43, NULL, 2, NULL, NULL, 'Armure lourde', 36, NULL, 0, 1, NULL, '{I}', NULL),
(31, 43, 1, NULL, NULL, NULL, 'Armure lourde', 36, NULL, 0, 1, NULL, '{I}', NULL),
(34, 27, NULL, 1, NULL, NULL, 'Petit bouclier', 36, NULL, 0, 1, NULL, '{EAG}', NULL),
(36, 53, 1, NULL, NULL, NULL, 'Bougies (par 6)', 36, NULL, 0, 1, 1, '{8,10}', NULL),
(38, 52, 1, NULL, NULL, NULL, 'Torche', 36, NULL, 0, 1, 1, '{7,10}', NULL),
(39, 53, NULL, 1, NULL, NULL, 'Bougies (par 6)', 36, NULL, 0, 1, NULL, '{I}', NULL),
(42, 29, 1, NULL, NULL, NULL, 'Pavois', 36, NULL, 0, 1, NULL, '{I}', NULL),
(80, 19, NULL, NULL, NULL, NULL, 'Sabre', 20, NULL, 1, 1, NULL, '0', NULL),
(110, 29, NULL, NULL, NULL, NULL, 'Pavois', 21, NULL, 2, 1, NULL, '0', NULL),
(142, 4, 1, NULL, NULL, NULL, 'Marteau de guerre', 26, NULL, 2, 1, NULL, '{I}', NULL),
(155, 2, NULL, NULL, NULL, NULL, 'Fléau d''arme', 19, NULL, 3, 1, NULL, '0', NULL),
(166, 45, NULL, NULL, NULL, NULL, 'Bière', 18, NULL, 4, 1, NULL, '0', NULL),
(171, 10, NULL, NULL, NULL, NULL, 'Dague', 23, NULL, 3, 1, NULL, '0', NULL),
(172, 46, NULL, NULL, NULL, NULL, 'Vin', 22, NULL, 1, 1, NULL, '0', NULL),
(176, 11, NULL, NULL, NULL, NULL, 'Epée', 20, NULL, 3, 1, NULL, '0', NULL),
(180, 47, NULL, NULL, NULL, NULL, 'Lait', 27, NULL, 4, 1, NULL, '0', NULL),
(184, 9, NULL, NULL, NULL, NULL, 'Couteau', 18, NULL, 2, 1, NULL, '0', NULL),
(186, 6, NULL, NULL, NULL, NULL, 'Massue', 23, NULL, 4, 1, NULL, '0', NULL),
(189, 7, 1, NULL, NULL, NULL, 'Gourdin', 19, NULL, 5, 2, NULL, '{I}', NULL),
(190, 46, NULL, NULL, NULL, NULL, 'Vin', 25, NULL, 2, 2, NULL, '0', NULL),
(191, 51, NULL, NULL, NULL, NULL, 'Boeuf', 27, NULL, 5, 2, NULL, '0', NULL),
(193, 10, NULL, NULL, NULL, NULL, 'Dague', 21, NULL, 2, 1, NULL, '0', NULL),
(194, 41, NULL, NULL, NULL, NULL, 'Armure légère', 23, NULL, 3, 1, NULL, '0', NULL),
(195, 27, NULL, NULL, NULL, NULL, 'Petit bouclier', 20, NULL, 2, 1, NULL, '0', NULL),
(198, 15, NULL, NULL, NULL, NULL, 'Hallebarde', 21, NULL, 4, 2, NULL, '0', NULL),
(201, 2, NULL, 1, NULL, NULL, 'Fléau d''arme', 24, NULL, 2, 1, NULL, '{V}', NULL),
(204, 11, NULL, NULL, NULL, NULL, 'Epée', 18, NULL, 5, 1, NULL, '0', NULL),
(205, 25, NULL, NULL, NULL, NULL, 'Pioche', 25, NULL, 4, 1, NULL, '0', NULL),
(207, 50, NULL, NULL, NULL, NULL, 'Mouton', 25, NULL, 4, 2, NULL, '0', NULL),
(208, 48, NULL, NULL, NULL, NULL, 'Légumes à grignoter', 17, NULL, 5, 1, NULL, '0', NULL),
(217, 17, NULL, NULL, NULL, NULL, 'Main-gauche', 27, NULL, 4, 1, NULL, '0', NULL),
(219, 4, NULL, NULL, NULL, NULL, 'Marteau de guerre', 23, NULL, 1, 1, NULL, '0', NULL),
(220, 11, NULL, NULL, NULL, NULL, 'Epée', 26, NULL, 1, 1, NULL, '0', NULL),
(222, 45, NULL, NULL, NULL, NULL, 'Bière', 18, NULL, 5, 1, NULL, '0', NULL),
(225, 44, NULL, NULL, NULL, NULL, 'Rations séchées', 27, NULL, 5, 2, NULL, '0', NULL),
(227, 7, NULL, NULL, NULL, NULL, 'Gourdin', 23, NULL, 5, 1, NULL, '0', NULL),
(236, 41, NULL, NULL, NULL, NULL, 'Armure légère', 18, NULL, 5, 1, NULL, '0', NULL),
(237, 28, NULL, NULL, NULL, NULL, 'Bouclier moyen', 17, NULL, 1, 1, NULL, '0', NULL),
(240, 21, NULL, NULL, NULL, NULL, 'Epieu', 27, NULL, 3, 1, NULL, '0', NULL),
(241, 2, NULL, 1, NULL, NULL, 'Fléau d''arme', 19, NULL, 1, 1, NULL, '{I}', NULL),
(243, 28, NULL, NULL, NULL, NULL, 'Bouclier moyen', 24, NULL, 1, 2, NULL, '0', NULL),
(244, 43, NULL, NULL, NULL, NULL, 'Armure lourde', 23, '{ACROBATIE,static,-1r}', 5, 1, NULL, '0', NULL),
(245, 10, NULL, NULL, NULL, NULL, 'Dague', 25, NULL, 1, 1, NULL, '0', NULL),
(247, 10, NULL, NULL, NULL, NULL, 'Dague', 24, NULL, 4, 1, NULL, '0', NULL),
(248, 44, NULL, NULL, NULL, NULL, 'Rations séchées', 20, NULL, 4, 1, NULL, '0', NULL),
(250, 44, NULL, NULL, NULL, NULL, 'Rations séchées', 20, NULL, 4, 1, NULL, '0', NULL),
(251, 46, NULL, NULL, NULL, NULL, 'Vin', 26, NULL, 4, 1, NULL, '0', NULL),
(252, 47, NULL, NULL, NULL, NULL, 'Lait', 23, NULL, 2, 1, NULL, '0', NULL),
(253, 42, NULL, NULL, NULL, NULL, 'Armure moyenne', 27, NULL, 1, 1, NULL, '0', NULL),
(255, 42, NULL, NULL, NULL, NULL, 'Armure moyenne', 21, NULL, 3, 2, NULL, '0', NULL),
(256, 11, NULL, NULL, NULL, NULL, 'Epée', 22, NULL, 5, 1, NULL, '0', NULL),
(257, 28, NULL, NULL, NULL, NULL, 'Bouclier moyen', 27, NULL, 4, 1, NULL, '0', NULL),
(258, 12, NULL, NULL, NULL, NULL, 'Epée courte', 19, NULL, 3, 1, NULL, '0', NULL),
(259, 49, NULL, NULL, NULL, NULL, 'Poulet', 18, NULL, 4, 2, NULL, '0', NULL),
(260, 23, NULL, NULL, NULL, NULL, 'Lance de fantassin', 17, NULL, 5, 1, NULL, '0', NULL),
(261, 7, 1, NULL, NULL, NULL, 'Gourdin', 19, NULL, 2, 1, NULL, '{I}', NULL),
(263, 48, NULL, NULL, NULL, NULL, 'Légumes à grignoter', 27, NULL, 3, 2, NULL, '0', NULL),
(264, 48, NULL, NULL, NULL, NULL, 'Légumes à grignoter', 18, NULL, 4, 1, NULL, '0', NULL),
(265, 47, NULL, NULL, NULL, NULL, 'Lait', 21, NULL, 3, 1, NULL, '0', NULL),
(266, 45, NULL, NULL, NULL, NULL, 'Bière', 19, NULL, 3, 1, NULL, '0', NULL),
(267, 19, NULL, NULL, NULL, NULL, 'Sabre', 23, NULL, 2, 2, NULL, '0', NULL),
(268, 45, NULL, NULL, NULL, NULL, 'Bière', 17, NULL, 3, 1, NULL, '0', NULL),
(269, 25, NULL, NULL, NULL, NULL, 'Pioche', 22, NULL, 5, 2, NULL, '0', NULL),
(270, 17, NULL, NULL, NULL, NULL, 'Main-gauche', 18, NULL, 5, 1, NULL, '0', NULL),
(271, 44, NULL, 1, NULL, NULL, 'Rations séchées', 21, NULL, 4, 2, NULL, '{I}', NULL),
(272, 44, 1, NULL, NULL, NULL, 'Rations séchées', 24, NULL, 1, 1, NULL, '{I}', NULL),
(274, 7, NULL, NULL, NULL, NULL, 'Gourdin', 20, NULL, 4, 1, NULL, '0', NULL),
(275, 50, NULL, NULL, NULL, NULL, 'Mouton', 26, NULL, 4, 1, NULL, '0', NULL),
(276, 41, NULL, NULL, NULL, NULL, 'Armure légère', 23, NULL, 5, 1, NULL, '0', NULL),
(277, 48, NULL, NULL, NULL, NULL, 'Légumes à grignoter', 24, NULL, 3, 1, NULL, '0', NULL),
(278, 46, NULL, NULL, NULL, NULL, 'Vin', 27, NULL, 4, 1, NULL, '0', NULL),
(279, 27, NULL, NULL, NULL, NULL, 'Petit bouclier', 24, NULL, 5, 1, NULL, '0', NULL),
(281, 9, 1, NULL, NULL, NULL, 'Couteau', 26, NULL, 5, 1, NULL, '{I}', NULL),
(282, 7, 1, NULL, NULL, NULL, 'Gourdin', 22, NULL, 3, 2, NULL, '{I}', NULL),
(283, 11, NULL, NULL, NULL, NULL, 'Epée', 22, NULL, 1, 1, NULL, '0', NULL),
(284, 41, NULL, NULL, NULL, NULL, 'Armure légère', 17, NULL, 1, 1, NULL, '0', NULL),
(285, 8, 1, NULL, NULL, NULL, 'Cimeterre', 26, NULL, 3, 1, NULL, '{I}', NULL),
(286, 49, NULL, NULL, NULL, NULL, 'Poulet', 21, NULL, 5, 1, NULL, '0', NULL),
(287, 47, NULL, NULL, NULL, NULL, 'Lait', 26, NULL, 1, 1, NULL, '0', NULL),
(288, 52, NULL, NULL, NULL, NULL, 'Torche', 26, NULL, 1, 1, NULL, '0', NULL),
(289, 1, NULL, NULL, NULL, NULL, 'Bâton', 27, NULL, 2, 1, NULL, '0', NULL),
(290, 49, NULL, NULL, NULL, NULL, 'Poulet', 19, NULL, 4, 1, NULL, '0', NULL),
(291, 44, NULL, NULL, NULL, NULL, 'Rations séchées', 17, NULL, 5, 2, NULL, '0', NULL),
(292, 48, NULL, NULL, NULL, NULL, 'Légumes à grignoter', 18, NULL, 3, 1, NULL, '0', NULL),
(293, 18, NULL, NULL, NULL, NULL, 'Rapière', 20, NULL, 3, 1, NULL, '0', NULL),
(294, 41, NULL, NULL, NULL, NULL, 'Armure légère', 23, NULL, 1, 1, NULL, '0', NULL),
(295, 29, NULL, NULL, NULL, NULL, 'Pavois', 17, NULL, 2, 1, NULL, '0', NULL),
(296, 17, NULL, NULL, NULL, NULL, 'Main-gauche', 21, NULL, 4, 1, NULL, '0', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `MEMBRE`
--

CREATE TABLE IF NOT EXISTS `MEMBRE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `username_canonical` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_canonical` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_64C3CAF92FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_64C3CAFA0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `MEMBRE`
--

INSERT INTO `MEMBRE` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(3, 'coco', 'coco', 'corentin.sirou@orange.fr', 'corentin.sirou@orange.fr', 1, 'ib5ijlvgzmgcgkcc8ck84g0kg8s4s0k', 'KaVvvkQ97PvgY30DRlq0Q6Ae7Njc1p1/J43jgodqlbo53VjnBiNyAgIOHxpuVdKZYAHDvzXuCPSKmcr4rtg/GQ==', '2013-04-18 13:27:14', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `OBJETS`
--

CREATE TABLE IF NOT EXISTS `OBJETS` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `NOM` varchar(255) NOT NULL DEFAULT 'Objet sans nom',
  `CAT` enum('ARME','ARME DE JET','BOUCLIER','MACHINE','ARME EXOTIQUE','ARMURE','VETEMENT','DENREE','PETIT MATERIEL','MONTURE','LIVRE','MATERIEL CHASSE','OBJET LEGENDAIRE','OBJET DU DESTIN','OBJET MAGIQUE','POTION') NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL DEFAULT 'rien',
  `BONUS` varchar(255) NOT NULL DEFAULT '',
  `PRIX` float(12,2) unsigned NOT NULL DEFAULT '0.00',
  `FREQUENCE` int(2) NOT NULL DEFAULT '0',
  `DEGAT` varchar(10) NOT NULL,
  `BOUCLIER` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=54 ;

--
-- Contenu de la table `OBJETS`
--

INSERT INTO `OBJETS` (`id`, `NOM`, `CAT`, `DESCRIPTION`, `BONUS`, `PRIX`, `FREQUENCE`, `DEGAT`, `BOUCLIER`) VALUES
(1, 'Bâton', 'ARME', 'rien', '', 1.00, 0, '1D6', '0'),
(2, 'Fléau d''arme', 'ARME', 'rien', '', 20.00, 0, '2D', '0'),
(3, 'Nunchaku', 'ARME', 'rien', '', 30.00, 0, '2D8', '0'),
(4, 'Marteau de guerre', 'ARME', 'rien', '', 20.00, 0, '2D', '0'),
(5, 'Masse d''arme', 'ARME', 'rien', '', 20.00, 0, '2D', '0'),
(6, 'Massue', 'ARME', 'rien', '', 3.00, 0, '2D', '0'),
(7, 'Gourdin', 'ARME', 'rien', '', 3.00, 0, '1D6', '0'),
(8, 'Cimeterre', 'ARME', 'rien', '', 25.00, 0, '2D', '0'),
(9, 'Couteau', 'ARME', 'rien', '', 2.00, 0, '1D', '0'),
(10, 'Dague', 'ARME', 'rien', '', 5.00, 0, '1D', '0'),
(11, 'Epée', 'ARME', 'rien', '', 25.00, 0, '2D', '0'),
(12, 'Epée courte', 'ARME', 'rien', '', 20.00, 0, '2D8', '0'),
(13, 'Epée à deux mains', 'ARME', 'rien', '', 30.00, 0, '10+1D', '0'),
(14, 'Hache', 'ARME', 'rien', '', 30.00, 0, '2D8', '0'),
(15, 'Hallebarde', 'ARME', 'rien', '', 30.00, 0, '2D', '0'),
(16, 'Katana', 'ARME', 'rien', '', 40.00, 0, '2D', '0'),
(17, 'Main-gauche', 'ARME', 'rien', '', 5.00, 0, '1D', '0'),
(18, 'Rapière', 'ARME', 'rien', '', 30.00, 0, '2D', '0'),
(19, 'Sabre', 'ARME', 'rien', '', 25.00, 0, '2D', '0'),
(20, 'Wakizashi', 'ARME', 'rien', '', 35.00, 0, '2D', '0'),
(21, 'Epieu', 'ARME', 'rien', '', 3.00, 0, '1D6', '0'),
(22, 'Fourche', 'ARME', 'rien', '', 5.00, 0, '1D', '0'),
(23, 'Lance de fantassin', 'ARME', 'rien', '', 20.00, 0, '2D8', '0'),
(24, 'Lance de cavalier', 'ARME', 'rien', '', 25.00, 0, '2D', '0'),
(25, 'Pioche', 'ARME', 'rien', '', 15.00, 0, '1D', '0'),
(26, 'Trident', 'ARME', 'rien', '', 25.00, 0, '2D', '0'),
(27, 'Petit bouclier', 'BOUCLIER', 'rien', '', 10.00, 0, '0', '2'),
(28, 'Bouclier moyen', 'BOUCLIER', 'rien', '', 20.00, 0, '0', '3'),
(29, 'Pavois', 'BOUCLIER', 'rien', '', 40.00, 0, '0', '5'),
(30, 'Baliste', 'MACHINE', 'rien', '', 200.00, 0, '0', '0'),
(31, 'Baliste à répétition', 'MACHINE', 'rien', '', 500.00, 0, '0', '0'),
(32, 'Catapulte', 'MACHINE', 'rien', '', 400.00, 0, '0', '0'),
(33, 'Trébuchet', 'MACHINE', 'rien', '', 800.00, 0, '0', '0'),
(34, 'Canon de navire', 'MACHINE', 'rien', '', 500.00, 0, '0', '0'),
(35, 'Canne-à-pyramide', 'ARME EXOTIQUE', 'rien', '', 45.00, 0, '2D', '0'),
(36, 'Casse-tête', 'ARME EXOTIQUE', 'rien', '', 30.00, 0, '2D', '0'),
(37, 'Dynamite', 'ARME EXOTIQUE', 'rien', '', 60.00, 0, '4D', '0'),
(38, 'Epée-fouet à deux lames', 'ARME EXOTIQUE', 'rien', '', 120.00, 0, '3D', '0'),
(39, 'Fouet', 'ARME EXOTIQUE', 'rien', '', 20.00, 0, '1D8', '0'),
(40, 'Marteau babare', 'ARME EXOTIQUE', 'rien', '', 25.00, 0, '5+2D', '0'),
(41, 'Armure légère', 'ARMURE', 'rien', '', 500.00, 0, '0', '4'),
(42, 'Armure moyenne', 'ARMURE', 'rien', '', 400.00, 0, '0', '8'),
(43, 'Armure lourde', 'ARMURE', 'rien', '{ACROBATIE,static,-1r}', 800.00, 0, '0', '10'),
(44, 'Rations séchées', 'DENREE', 'rien', '', 0.20, 0, '0', '0'),
(45, 'Bière', 'DENREE', 'rien', '', 0.10, 0, '0', '0'),
(46, 'Vin', 'DENREE', 'rien', '', 0.20, 0, '0', '0'),
(47, 'Lait', 'DENREE', 'rien', '', 0.05, 0, '0', '0'),
(48, 'Légumes à grignoter', 'DENREE', 'rien', '', 0.05, 0, '0', '0'),
(49, 'Poulet', 'DENREE', 'rien', '', 1.00, 0, '0', '0'),
(50, 'Mouton', 'DENREE', 'rien', '', 10.00, 0, '0', '0'),
(51, 'Boeuf', 'DENREE', 'rien', '', 20.00, 0, '0', '0'),
(52, 'Torche', 'PETIT MATERIEL', 'rien', '', 0.05, 0, '0', '0'),
(53, 'Bougies (par 6)', 'PETIT MATERIEL', 'rien', '', 0.01, 0, '0', '0');

-- --------------------------------------------------------

--
-- Structure de la table `ORGANISATION`
--

CREATE TABLE IF NOT EXISTS `ORGANISATION` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CAT` enum('group','guilde') NOT NULL DEFAULT 'group',
  `PUBLIC` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `NOM` varchar(45) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `CHARTE` text,
  `BLASON` varchar(45) DEFAULT NULL,
  `GESTION` enum('auto','joueur') NOT NULL DEFAULT 'auto',
  `NOMBRE_MB_MAX` int(10) unsigned NOT NULL DEFAULT '0',
  `PRIX_IN` int(10) unsigned DEFAULT '0',
  `PRIX_COT` int(10) unsigned DEFAULT '0',
  `ACTIONS_DIFF` varchar(45) DEFAULT NULL,
  `DATE_CREA` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `ORGANISATION_APPARTENANCE`
--

CREATE TABLE IF NOT EXISTS `ORGANISATION_APPARTENANCE` (
  `idAVENTURIER` int(11) unsigned NOT NULL,
  `idORGANISATION` int(10) unsigned NOT NULL,
  `CACHER` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `TITRE` varchar(45) DEFAULT NULL,
  `DROITS` set('chef','administrateur','gdroits','gargent','ginventaire','gliste','argent','inventaire','liste') DEFAULT NULL,
  `ETAT` set('actif','ancien','exclu','demande','refus') NOT NULL DEFAULT 'demande',
  PRIMARY KEY (`idAVENTURIER`,`idORGANISATION`),
  KEY `fk_AVENTURIER_has_ORGANISATION_ORGANISATION1` (`idORGANISATION`),
  KEY `fk_AVENTURIER_has_ORGANISATION_AVENTURIER1` (`idAVENTURIER`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `PIECE`
--

CREATE TABLE IF NOT EXISTS `PIECE` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idETAGE` int(11) unsigned NOT NULL,
  `NOM` varchar(255) NOT NULL,
  `POSX` smallint(6) NOT NULL,
  `POSY` smallint(6) NOT NULL,
  `TAILLEX` smallint(5) unsigned NOT NULL DEFAULT '5',
  `TAILLEY` smallint(5) unsigned NOT NULL DEFAULT '5',
  `COUCHE_SOL` longtext NOT NULL,
  `COUCHE_SOL2` longtext NOT NULL,
  `COUCHE_MOBILIER` longtext NOT NULL,
  `ACTIONS` longtext NOT NULL,
  `ETAT` smallint(5) NOT NULL DEFAULT '0',
  `LUMIERE` smallint(5) unsigned NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`),
  KEY `fk_PIECE_ETAGE1` (`idETAGE`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `PIECE`
--

INSERT INTO `PIECE` (`id`, `idETAGE`, `NOM`, `POSX`, `POSY`, `TAILLEX`, `TAILLEY`, `COUCHE_SOL`, `COUCHE_SOL2`, `COUCHE_MOBILIER`, `ACTIONS`, `ETAT`, `LUMIERE`) VALUES
(1, 2, 'Entrée', 0, 0, 20, 20, '[002079,002079,002079,002079,002079,002105,002168,002168,002168,002168,002168,002168,002168,002168,002168,002079,002079,002079,002079,002079][002079,002079,002079,002079,002079,002079,002168,002168,002168,002002,002002,002002,002002,002168,002168,002079,002079,002079,002079,002079][002079,002079,002105,002079,002079,002079,002168,002168,002168,002002,002002,002002,002002,002168,002168,002079,002079,002079,002079,002079][002079,002079,002079,002079,002079,002079,002168,002168,002168,002002,002002,002002,002002,002168,002168,002079,002079,002105,002079,002079][002079,002079,002079,002079,002079,002079,002168,002168,002168,002002,002002,002002,002002,002168,002168,002079,002079,002079,002079,002079][002079,002079,002079,002079,002079,002079,002168,002168,002168,001060,002002,002002,001060,002168,002168,002079,002079,002079,002079,002079][002079,002079,002079,002079,002079,002079,002168,002168,002168,002168,002002,002002,002168,002168,002168,002079,002079,002079,002079,002079][002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079,002079][000217,000217,000217,000217,000217,000217,000217,000211,000234,000234,000234,000234,000242,000217,000217,000217,000217,000217,000217,000217][002209,002209,002209,002209,002209,002048,002209,002209,002209,002209,002209,002048,002209,002209,002209,002209,002209,002209,002209,004000][002209,002209,002209,002209,002209,002209,002209,002209,002209,002209,002209,002209,002209,002209,002209,002209,002048,002209,002209,002209][002209,002209,002048,002209,001163,001163,001163,001163,002209,002209,002209,001163,001163,001163,002209,002209,002209,002209,002209,002209][002209,004006,002209,002209,002168,002168,002168,002168,002209,002209,002048,002168,002168,002168,002209,002048,004008,004001,002209,002048][002209,002209,002048,002209,002168,002168,002168,002168,002209,002209,002209,002168,002168,002168,002209,002209,004009,004002,002209,002209][001163,001163,001163,001163,002168,002168,002168,002168,002048,002209,002209,002168,002168,002168,001163,001163,001163,001163,001163,001163][001171,001171,001171,001171,002168,002168,002168,002168,002209,002209,002209,002168,002168,002168,002168,002168,002168,002168,002168,002168][002168,002168,002168,002168,002168,002209,002209,002209,002209,002209,002209,002209,002209,002209,002168,002168,002168,002168,002168,002168][002168,002168,002168,002168,002168,002209,002209,002209,002209,002209,002048,002209,002209,002209,002168,002168,002168,002168,002168,002168][002168,002168,002168,002168,002168,002209,002048,002209,002209,002209,002209,002209,002209,002209,002168,002168,002168,002168,002168,002168][002168,002168,002168,002168,002168,001163,001163,001163,001163,001163,001163,001163,001163,001163,002168,002168,002168,002168,002168,002168]', '[0,1,011088][10,2,011036]', '[17,5,008091][1,7,008084][9,14,008019][6,16,008049][12,17,008092][12,18,008025]', '{4,0,PO,3}{5,16,Z}{6,16,Z}{7,16,Z}{8,16,Z}{9,16,Z}{10,16,Z}{11,16,Z}{12,16,Z}{13,16,Z}{5,17,Z}{6,17,Z}{7,17,Z}{8,17,Z}{9,17,Z}{10,17,Z}{11,17,Z}{12,17,Z}{13,17,Z}{5,18,Z}{6,18,Z}{7,18,Z}{8,18,Z}{9,18,Z}{10,18,Z}{11,18,Z}{12,18,Z}{13,18,Z}{14,14,V}{15,14,V}{16,14,V}{17,14,V}{18,14,V}{19,14,V}{14,15,V}{15,15,V}{16,15,V}{17,15,V}{18,15,V}{19,15,V}{14,16,V}{15,16,V}{16,16,V}{17,16,V}{18,16,V}{19,16,V}{14,17,V}{15,17,V}{16,17,V}{17,17,V}{18,17,V}{19,17,V}{14,18,V}{15,18,V}{16,18,V}{17,18,V}{18,18,V}{19,18,V}{14,19,V}{15,19,V}{16,19,V}{17,19,V}{18,19,V}{19,19,V}{0,14,V}{1,14,V}{2,14,V}{3,14,V}{4,14,V}{0,15,V}{1,15,V}{2,15,V}{3,15,V}{4,15,V}{0,16,V}{1,16,V}{2,16,V}{3,16,V}{4,16,V}{0,17,V}{1,17,V}{2,17,V}{3,17,V}{4,17,V}{0,18,V}{1,18,V}{2,18,V}{3,18,V}{4,18,V}{0,19,V}{1,19,V}{2,19,V}{3,19,V}{4,19,V}{5,19,V}{6,19,V}{7,19,V}{8,19,V}{9,19,V}{10,19,V}{11,19,V}{12,19,V}{13,19,V}{11,11,V}{12,11,V}{13,11,V}{11,12,V}{12,12,V}{13,12,V}{11,13,V}{12,13,V}{13,13,V}{11,14,V}{12,14,V}{13,14,V}{11,15,V}{12,15,V}{13,15,V}{4,11,V}{5,11,V}{6,11,V}{7,11,V}{4,12,V}{5,12,V}{6,12,V}{7,12,V}{4,13,V}{5,13,V}{6,13,V}{7,13,V}{5,14,V}{6,14,V}{7,14,V}{5,15,V}{6,15,V}{7,15,V}{6,0,V}{7,0,V}{8,0,V}{6,1,V}{7,1,V}{8,1,V}{6,2,V}{7,2,V}{8,2,V}{6,3,V}{7,3,V}{8,3,V}{6,4,V}{7,4,V}{8,4,V}{6,5,V}{7,5,V}{8,5,V}{6,6,V}{7,6,V}{8,6,V}{13,0,V}{14,0,V}{13,1,V}{14,1,V}{13,2,V}{14,2,V}{13,3,V}{14,3,V}{13,4,V}{14,4,V}{13,5,V}{14,5,V}{13,6,V}{14,6,V}{9,0,V}{10,0,V}{11,0,V}{12,0,V}{9,5,V}{9,6,V}{12,5,V}{12,6,V}{19,9,P,2,1,4}{14,12,S}{15,12,S}{14,13,S}{15,13,S}{0,13,F}{1,13,F}{2,13,F}{3,13,F}{16,12,I}{17,12,I}{16,13,I}{17,13,I}{1,12,I}{3,8,EM}', 2, 10),
(2, 2, 'Le grand couloir', 20, 1, 40, 8, '[001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075,001075][002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104][002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,000008,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104][002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104][004004,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104][002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104][002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,000008,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104][002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,002104,004011,002104,002104,002104]', '[2,0,010572][7,0,009024][13,0,009024][19,0,009024][25,0,009024][32,0,009024][37,0,009024][9,1,013021][22,1,013097][35,1,013104][9,2,013024][22,2,013068][35,2,013049]', ' ', '{0,4,P,1,18,9}{0,0,I}{1,0,I}{2,0,I}{3,0,I}{4,0,I}{5,0,I}{6,0,I}{7,0,I}{8,0,I}{9,0,I}{10,0,I}{11,0,I}{12,0,I}{13,0,I}{14,0,I}{15,0,I}{16,0,I}{17,0,I}{18,0,I}{19,0,I}{20,0,I}{21,0,I}{22,0,I}{23,0,I}{24,0,I}{25,0,I}{26,0,I}{27,0,I}{28,0,I}{29,0,I}{30,0,I}{31,0,I}{32,0,I}{33,0,I}{34,0,I}{35,0,I}{36,0,I}{37,0,I}{38,0,I}{39,0,I}{9,2,I}{22,2,I}{35,2,I}', 2, 10),
(3, 2, 'Le passage', 38, 9, 22, 23, '[001018,001018,001018,001223,001018,001018,001018,001018,001223,001018,001018,001018,001018,001018,001195,001018,001018,001018,001071,001018,001018,001018][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002055,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002055,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002214,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002055,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][004004,002054,002054,002054,002055,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002055,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][001142,001142,001142,001142,001142,001142,001142,002078,002148,001142,001142,001142,001142,001142,001142,002053,001142,001142,001142,001142,001142,001142][001171,001171,001171,001171,001171,001171,001171,002078,002148,001171,001171,001171,001171,001171,001171,001171,001171,001171,001171,001171,001171,001171][001171,001171,001171,001171,001171,001171,001171,002078,002148,001171,001171,001171,001171,001171,001171,001171,001171,001171,001171,001171,001171,001171][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002055,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002055,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054][002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,004011,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054,002054]', '[2,0,008092][6,0,008513][9,0,008052][12,0,008526][19,0,009024][21,0,008015][2,1,008049][18,1,008019][15,4,009520][2,6,010005][15,7,008091][12,9,008086][19,12,011126][19,13,011033][3,17,008520][17,19,011103]', ' ', '{17,6,PO,10}{10,5,PO,5}{3,8,PO,5}{5,2,PO,5}{18,21,PO,5}{4,20,PO,5}{13,16,PO,5}{9,10,PO,5}{0,0,PO,10}', -3, 10),
(4, 2, 'La halte', 20, 9, 18, 11, '[002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094]', ' ', ' ', '{0,0,PO,51}', 1, 9),
(5, 9, 'Nouvelle salle', 0, 0, 13, 5, '[002052,002052,002052,002052,002052,002052,002052,002052,002052,002052,002052,002052,002052][002052,002052,002036,002052,002052,002052,002052,002052,002052,002052,002052,002052,002052][002052,002052,002052,002052,002052,002052,002052,002052,002052,002052,002052,002016,002052][002052,002052,002052,002052,002052,002052,002052,002052,002052,002052,002052,002052,002052][002052,002052,002016,002052,002052,002052,002052,002052,002052,002052,002052,002052,002052]', ' ', ' ', ' ', 1, 10),
(7, 3, 'Fen', 0, 0, 20, 20, '[002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094]', ' ', ' ', '{3,8,ED}{5,7,ED}', 1, 10),
(8, 2, 'Buandrie', 27, 20, 11, 13, '[002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002003,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094]', '[7,5,013002]', '[8,6,013005]', ' ', 1, 10),
(9, 2, 'Cagibi', 14, 20, 13, 16, '[002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094][002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094,002094]', ' ', ' ', '', 2, 10);

-- --------------------------------------------------------

--
-- Structure de la table `POUVOIR`
--

CREATE TABLE IF NOT EXISTS `POUVOIR` (
  `id` varchar(6) NOT NULL,
  `NOM` varchar(30) NOT NULL,
  `DESCRIPTION` longtext NOT NULL,
  `ACTION` varchar(45) NOT NULL,
  `CAT` enum('Spécifique','Générique','Extraordinaire','Social') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `RELATION`
--

CREATE TABLE IF NOT EXISTS `RELATION` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAVENTURIER1` int(11) unsigned NOT NULL,
  `idAVENTURIER2` int(11) unsigned NOT NULL,
  `CAT` enum('Proche','Ami','Ennemi','Amour','Enseignement','Mecene') NOT NULL,
  `COUT` float(12,5) unsigned NOT NULL DEFAULT '0.00000',
  `DATE_RELATION` date NOT NULL,
  `FIN` smallint(6) NOT NULL DEFAULT '-1',
  `ETAT1` tinyint(1) NOT NULL,
  `ETAT2` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_AVENTURIER_has_AVENTURIER_AVENTURIER2` (`idAVENTURIER2`),
  KEY `fk_AVENTURIER_has_AVENTURIER_AVENTURIER1` (`idAVENTURIER1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `STATS`
--

CREATE TABLE IF NOT EXISTS `STATS` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `USR` varchar(10) NOT NULL,
  `CLE` varchar(45) NOT NULL,
  `VAL` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `TALENT`
--

CREATE TABLE IF NOT EXISTS `TALENT` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(45) NOT NULL,
  `DESCRIPTION` longtext NOT NULL,
  `CAT` enum('Professionnel','Personnel') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `AVENTURIER`
--
ALTER TABLE `AVENTURIER`
  ADD CONSTRAINT `fk_AVENTURIER_CREATURE1` FOREIGN KEY (`idCREATURE`) REFERENCES `CREATURE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_AVENTURIER_MEMBRE1` FOREIGN KEY (`idMEMBRE`) REFERENCES `MEMBRE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_AVENTURIER_PIECE1` FOREIGN KEY (`idPIECE`) REFERENCES `PIECE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `BANQUE`
--
ALTER TABLE `BANQUE`
  ADD CONSTRAINT `fk_BANQUE_AVENTURIER1` FOREIGN KEY (`idAVENTURIER`) REFERENCES `AVENTURIER` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `BESTIAIRE`
--
ALTER TABLE `BESTIAIRE`
  ADD CONSTRAINT `fk_BESTIAIRE_CREATURE1` FOREIGN KEY (`idCREATURE`) REFERENCES `CREATURE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_BESTIAIRE_PIECE1` FOREIGN KEY (`idPIECE`) REFERENCES `PIECE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DONJON_has_MONSTRE_DONJON1` FOREIGN KEY (`idDONJON`) REFERENCES `DONJON` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `CREATURE`
--
ALTER TABLE `CREATURE`
  ADD CONSTRAINT `fk_CREATURE_CREATURE1` FOREIGN KEY (`idPARENT`) REFERENCES `CREATURE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `DONJON`
--
ALTER TABLE `DONJON`
  ADD CONSTRAINT `fk_DONJON_MEMBRE1` FOREIGN KEY (`idMEMBRE`) REFERENCES `MEMBRE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ETAGE`
--
ALTER TABLE `ETAGE`
  ADD CONSTRAINT `ETAGE_ibfk_1` FOREIGN KEY (`idDONJON`) REFERENCES `DONJON` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `INVENTAIRE`
--
ALTER TABLE `INVENTAIRE`
  ADD CONSTRAINT `fk_INVENTAIRE_AVENTURIER1` FOREIGN KEY (`idAVENTURIER`) REFERENCES `AVENTURIER` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_INVENTAIRE_BANQUE1` FOREIGN KEY (`idCOMPTE`) REFERENCES `BANQUE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_INVENTAIRE_BESTIAIRE1` FOREIGN KEY (`idBESTIAIRE`) REFERENCES `BESTIAIRE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_INVENTAIRE_DONJON1` FOREIGN KEY (`idDONJON`) REFERENCES `DONJON` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_INVENTAIRE_PIECE1` FOREIGN KEY (`idPIECE`) REFERENCES `PIECE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_OBJETS_has_PIECE_OBJETS1` FOREIGN KEY (`idOBJETS`) REFERENCES `OBJETS` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ORGANISATION_APPARTENANCE`
--
ALTER TABLE `ORGANISATION_APPARTENANCE`
  ADD CONSTRAINT `fk_AVENTURIER_has_ORGANISATION_AVENTURIER1` FOREIGN KEY (`idAVENTURIER`) REFERENCES `AVENTURIER` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_AVENTURIER_has_ORGANISATION_ORGANISATION1` FOREIGN KEY (`idORGANISATION`) REFERENCES `ORGANISATION` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `PIECE`
--
ALTER TABLE `PIECE`
  ADD CONSTRAINT `fk_PIECE_ETAGE1` FOREIGN KEY (`idETAGE`) REFERENCES `ETAGE` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `RELATION`
--
ALTER TABLE `RELATION`
  ADD CONSTRAINT `fk_AVENTURIER_has_AVENTURIER_AVENTURIER1` FOREIGN KEY (`idAVENTURIER1`) REFERENCES `AVENTURIER` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_AVENTURIER_has_AVENTURIER_AVENTURIER2` FOREIGN KEY (`idAVENTURIER2`) REFERENCES `AVENTURIER` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
