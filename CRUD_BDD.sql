-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : jeu. 06 nov. 2025 à 09:51
-- Version du serveur : 9.4.0
-- Version de PHP : 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `CRUD_BDD`
--

-- --------------------------------------------------------

--
-- Structure de la table `boisson`
--

CREATE TABLE `boisson` (
  `id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `volumeCL` int NOT NULL,
  `prix` double NOT NULL,
  `categorie_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `boisson`
--

INSERT INTO `boisson` (`id`, `nom`, `volumeCL`, `prix`, `categorie_id`) VALUES
(2, 'americano', 15, 5, 2);

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int NOT NULL,
  `libelle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `libelle`) VALUES
(2, 'Cafe'),
(3, 'aergaerg'),
(4, 'Whisky');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `boisson`
--
ALTER TABLE `boisson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8B97C84DBCF5E72D` (`categorie_id`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `boisson`
--
ALTER TABLE `boisson`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `boisson`
--
ALTER TABLE `boisson`
  ADD CONSTRAINT `FK_8B97C84DBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
