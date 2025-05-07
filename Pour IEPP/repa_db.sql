-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 04 avr. 2025 à 04:40
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `repa_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `appareils`
--

CREATE TABLE `appareils` (
  `idapp` int(10) UNSIGNED NOT NULL,
  `marqueapp` varchar(255) NOT NULL,
  `imageapp` varchar(255) DEFAULT NULL,
  `autres_infos` text DEFAULT NULL,
  `id_client` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `idcli` int(10) UNSIGNED NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `date_arrivee` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `idfac` int(10) UNSIGNED NOT NULL,
  `id_reparation` int(10) UNSIGNED NOT NULL,
  `date_facture` timestamp NOT NULL DEFAULT current_timestamp(),
  `montant` decimal(10,2) NOT NULL CHECK (`montant` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reparations`
--

CREATE TABLE `reparations` (
  `idrepa` int(10) UNSIGNED NOT NULL,
  `type_repa` enum('xpress','standard') NOT NULL DEFAULT 'standard',
  `details` text NOT NULL,
  `date_debut` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_fin` timestamp NULL DEFAULT NULL,
  `id_appareil` int(10) UNSIGNED NOT NULL,
  `id_technicien` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `techniciens`
--

CREATE TABLE `techniciens` (
  `idtech` int(10) UNSIGNED NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `numtel` varchar(20) NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `iduser` int(10) UNSIGNED NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('jdadmin','jduser') NOT NULL DEFAULT 'jdadmin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`iduser`, `nom_complet`, `email`, `mot_de_passe`, `role`, `created_at`) VALUES
(1, 'JD admin', 'jdadmin@gmail.com', 'Jdadmin123', 'jdadmin', '2025-04-04 01:57:49');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `appareils`
--
ALTER TABLE `appareils`
  ADD PRIMARY KEY (`idapp`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`idcli`),
  ADD UNIQUE KEY `contact` (`contact`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`idfac`),
  ADD KEY `id_reparation` (`id_reparation`);

--
-- Index pour la table `reparations`
--
ALTER TABLE `reparations`
  ADD PRIMARY KEY (`idrepa`),
  ADD KEY `id_appareil` (`id_appareil`),
  ADD KEY `id_technicien` (`id_technicien`);

--
-- Index pour la table `techniciens`
--
ALTER TABLE `techniciens`
  ADD PRIMARY KEY (`idtech`),
  ADD UNIQUE KEY `numtel` (`numtel`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appareils`
--
ALTER TABLE `appareils`
  MODIFY `idapp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `idcli` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `idfac` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reparations`
--
ALTER TABLE `reparations`
  MODIFY `idrepa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `techniciens`
--
ALTER TABLE `techniciens`
  MODIFY `idtech` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `iduser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appareils`
--
ALTER TABLE `appareils`
  ADD CONSTRAINT `appareils_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`idcli`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`id_reparation`) REFERENCES `reparations` (`idrepa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reparations`
--
ALTER TABLE `reparations`
  ADD CONSTRAINT `reparations_ibfk_1` FOREIGN KEY (`id_appareil`) REFERENCES `appareils` (`idapp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reparations_ibfk_2` FOREIGN KEY (`id_technicien`) REFERENCES `techniciens` (`idtech`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
