-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 21 Septembre 2023 à 21:29
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `appvote`
--

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `nom` varchar(64) NOT NULL,
  `date` date NOT NULL,
  `lieu` varchar(128) NOT NULL,
  `departement` varchar(64) NOT NULL,
  `description` varchar(255) NOT NULL,
  `avisParticipant` varchar(4) NOT NULL,
  `avisOrganisateur` varchar(4) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16le;

--
-- Contenu de la table `evenement`
--

INSERT INTO `evenement` (`nom`, `date`, `lieu`, `departement`, `description`, `avisParticipant`, `avisOrganisateur`, `id`) VALUES
('BIDON', '2023-09-18', 'BIDON', 'BIDON', 'BIDON', '0%', '0%', 1),
('BIDON2', '2023-09-18', 'BIDON', 'BIDON', 'BIDON', '0%', '0%', 2);

-- --------------------------------------------------------

--
-- Structure de la table `gestion`
--

CREATE TABLE `gestion` (
  `user` int(11) NOT NULL,
  `evenement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16le;

--
-- Contenu de la table `gestion`
--

INSERT INTO `gestion` (`user`, `evenement`) VALUES
(1, 1),
(8, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `user` varchar(100) NOT NULL,
  `mdp` varchar(1024) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16le;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`user`, `mdp`, `id`) VALUES
('chumnon', '18850153f825f7c2f7408b1a79c88dcf221ae1b7', 1),
('BIDON', '664c2019f9fdad00b3fa0ace08d341b411beca2f', 7),
('BIDON2', '1505a5b7a9e06544031ee699910ed77d389fa21a', 8);

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE `vote` (
  `id` int(11) NOT NULL,
  `avis` varchar(4) NOT NULL,
  `evenementID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16le;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gestion`
--
ALTER TABLE `gestion`
  ADD PRIMARY KEY (`user`,`evenement`),
  ADD KEY `FK_GESTIONE` (`evenement`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`id`,`evenementID`),
  ADD KEY `evenementID` (`evenementID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `vote`
--
ALTER TABLE `vote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `gestion`
--
ALTER TABLE `gestion`
  ADD CONSTRAINT `FK_GESTIONE` FOREIGN KEY (`evenement`) REFERENCES `evenement` (`id`),
  ADD CONSTRAINT `FK_gestionaire` FOREIGN KEY (`user`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`evenementID`) REFERENCES `evenement` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
