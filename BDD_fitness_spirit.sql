-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 30 oct. 2022 à 08:57
-- Version du serveur : 10.5.13-MariaDB-cll-lve
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u786407711_fitness_spirit`
--

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE `salles` (
  `salles_name` varchar(50) NOT NULL,
  `salles_adresse` varchar(100) NOT NULL,
  `salles_planning` tinyint(1) NOT NULL,
  `salles_boissons` tinyint(1) NOT NULL,
  `salles_materiels` tinyint(1) NOT NULL,
  `salles_tel` varchar(10) NOT NULL,
  `salles_prop` int(11) NOT NULL,
  `salles_active` int(11) NOT NULL,
  `salles_description` varchar(200) NOT NULL,
  `salles_id` int(11) NOT NULL,
  `salles_email` varchar(50) NOT NULL,
  `salles_confirm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `client_name` varchar(100) NOT NULL,
  `client_email` varchar(100) NOT NULL,
  `client_description` varchar(500) NOT NULL,
  `client_perm` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_active` int(11) NOT NULL,
  `client_password` varchar(100) NOT NULL,
  `client_tel` int(10) NOT NULL,
  `password_asked_date` date DEFAULT NULL,
  `password_token` varchar(50) NOT NULL,
  `client_adresse` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`client_name`, `client_email`, `client_description`, `client_perm`, `client_id`, `client_active`, `client_password`, `client_tel`, `password_asked_date`, `password_token`, `client_adresse`) VALUES
('Admin', 'admin@fitnessspirit.fr', 'Profil administrateur de base', 1, 129, 0, '46528001f0ab078f4da0bddca07c7bfd941bd66b', 625252525, '2022-10-29', 'VRsBlpIuwNHXgQyZdoiW', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`salles_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`client_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
  MODIFY `salles_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
