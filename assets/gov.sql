-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 15 jan. 2025 à 12:14
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gov`
--

-- --------------------------------------------------------

--
-- Structure de la table `administration`
--

DROP TABLE IF EXISTS `administration`;
CREATE TABLE IF NOT EXISTS `administration` (
  `IdAdmin` int NOT NULL AUTO_INCREMENT,
  `NomComplet` varchar(255) NOT NULL,
  `Poste` varchar(255) NOT NULL,
  `Email` varchar(191) NOT NULL,
  `MotPasse` varchar(255) NOT NULL,
  PRIMARY KEY (`IdAdmin`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `administration`
--

INSERT INTO `administration` (`IdAdmin`, `NomComplet`, `Poste`, `Email`, `MotPasse`) VALUES
(1, 'Jean Dupont', 'Inspecteur', 'jean.dupont@example.com', 'password123'),
(2, 'Marie Curie', 'Assistante', 'marie.curie@example.com', 'securepass456'),
(6, 'Code Crack', 'Inspecteur', 'code.crack@example.com', 'Myschool7@');

-- --------------------------------------------------------

--
-- Structure de la table `demandeur`
--

DROP TABLE IF EXISTS `demandeur`;
CREATE TABLE IF NOT EXISTS `demandeur` (
  `IdDmd` int NOT NULL AUTO_INCREMENT,
  `DateHeureDmd` datetime DEFAULT CURRENT_TIMESTAMP,
  `NomDmd` varchar(255) NOT NULL,
  `NumeroTel` varchar(20) NOT NULL,
  `ConcerneAnnee` year NOT NULL,
  `TypeDmd` varchar(255) NOT NULL,
  `DateRdv` date NOT NULL,
  `IdAdmin` int DEFAULT NULL,
  `Etat` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`IdDmd`),
  KEY `IdAdmin` (`IdAdmin`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demandeur`
--

INSERT INTO `demandeur` (`IdDmd`, `DateHeureDmd`, `NomDmd`, `NumeroTel`, `ConcerneAnnee`, `TypeDmd`, `DateRdv`, `IdAdmin`, `Etat`) VALUES
(8, '2025-01-12 11:17:10', 'JKK', '79794776', '2000', 'BAC', '2025-01-12', 1, 0),
(9, '2025-01-12 11:19:30', 'uuuuu', '79794726', '2000', 'BAC', '2025-01-12', 1, 0),
(10, '2025-01-12 11:22:01', 'tttbb', '79794776', '2004', 'BAC', '2025-01-12', 1, 0),
(11, '2025-01-12 11:23:36', 'JPPP', '79794776', '2005', 'BAC', '2025-01-12', 1, 0),
(12, '2025-01-12 11:25:00', 'FG', '79794776', '2006', 'BAC', '2025-01-12', 1, 0),
(13, '2025-01-12 11:27:14', 'steller4', '79794776', '2007', 'BAC', '2025-01-30', 1, 1),
(14, '2025-01-12 13:21:43', 'Chaminage', '333333333333333', '2002', 'BAC, Acte de Naissance', '2025-01-19', 1, 1),
(15, '2025-01-13 21:27:51', 'anselme ', '79794776', '2003', 'BAC, Acte de Naissance', '2025-01-21', 1, 1),
(16, '2025-01-14 13:09:56', 'jonas', '77777777777777', '2004', 'BAC', '2025-01-15', 4, 1),
(17, '2025-01-14 15:13:10', 'ACA', '33333333333', '2003', 'Acte de Naissance', '2025-01-21', 2, 0),
(18, '2025-01-14 15:14:27', 'ZZZZ', '5555555555555555555', '2007', 'Acte de Naissance', '2025-01-22', 1, 0),
(20, '2025-01-14 16:10:26', 'juju', '79794726', '2002', 'BAC', '2025-01-30', 1, 1),
(24, '2025-01-14 17:02:18', 'KEMO', '79794776', '1997', 'Attestation de Nationalité', '2025-01-16', 1, 0),
(23, '2025-01-14 17:01:50', 'mervo', '79794776', '1997', 'Acte de Naissance', '2025-01-17', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `retrait`
--

DROP TABLE IF EXISTS `retrait`;
CREATE TABLE IF NOT EXISTS `retrait` (
  `IdRt` int NOT NULL AUTO_INCREMENT,
  `DateRetrait` datetime DEFAULT CURRENT_TIMESTAMP,
  `NomRecept` varchar(255) NOT NULL,
  `Telephone` varchar(20) NOT NULL,
  `IdDmd` int DEFAULT NULL,
  PRIMARY KEY (`IdRt`),
  KEY `IdDmd` (`IdDmd`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `retrait`
--

INSERT INTO `retrait` (`IdRt`, `DateRetrait`, `NomRecept`, `Telephone`, `IdDmd`) VALUES
(1, '2025-01-10 15:33:58', 'Claire Fontaine', '0123456789', 1),
(2, '2025-01-10 15:33:58', 'David Moreau', '0987654321', 2),
(3, '2025-01-10 15:33:58', 'Claire Fontaine', '0123456789', 1),
(4, '2025-01-10 15:33:58', 'David Moreau', '0987654321', 2),
(10, '2025-01-12 10:49:20', 'STAN', '5566677889', 2),
(11, '2025-01-12 10:57:07', 'STEPHI', '5566677889', 5),
(12, '2025-01-12 11:07:14', 'sisi', '5566677889', 6),
(13, '2025-01-12 11:29:09', 'rot', '5566677889', 13),
(14, '2025-01-12 13:22:52', 'stanley', '99999999999', 14),
(15, '2025-01-13 21:28:48', 'roro', '5566677889', 15),
(16, '2025-01-14 13:10:33', 'JONAS', '7777777777777777', 16),
(17, '2025-01-14 16:11:05', 'JUJU', '5566677889', 20);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
