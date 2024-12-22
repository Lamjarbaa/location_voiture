-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 22 déc. 2024 à 12:15
-- Version du serveur : 8.0.30
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `location-voiture`
--

-- --------------------------------------------------------

--
-- Structure de la table `app_categories_voiture`
--

CREATE TABLE `app_categories_voiture` (
  `id` int NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description_en` text,
  `name_fr` varchar(255) DEFAULT NULL,
  `description_fr` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `app_categories_voiture`
--

INSERT INTO `app_categories_voiture` (`id`, `name_en`, `description_en`, `name_fr`, `description_fr`, `created_at`, `updated_at`) VALUES
(8, 'test-en100', 'tesren100', 'test-fr', 'testfr', '2024-12-19 10:57:23', '2024-12-19 13:53:11'),
(9, 'test-en', 'testwn', 'test-fr', 'testfr', '2024-12-19 10:58:33', '2024-12-19 10:58:33'),
(10, 'test-en', 'erfrdwe', 'edwed', 'erfffe', '2024-12-19 10:59:11', '2024-12-19 10:59:11'),
(11, 'test-en10', 'test-en10', 'test-fr10', 'test-fr10', '2024-12-19 11:18:16', '2024-12-19 11:18:16'),
(12, 'test-en', 'test-en', 'test-fr', 'test-fr', '2024-12-19 11:27:16', '2024-12-19 11:27:16'),
(13, 'test-en', 'test-en', 'test-fr', 'test-fr', '2024-12-19 11:28:22', '2024-12-19 11:28:22'),
(14, 'en', 'en', 'fr', 'fr', '2024-12-19 11:29:59', '2024-12-19 11:29:59'),
(15, 'test', 'test', 'test-fr', 'test', '2024-12-19 13:26:55', '2024-12-19 13:26:55'),
(16, 'test-en100', 'tesren100', 'test-fr', 'testfr', '2024-12-19 13:53:11', '2024-12-19 13:53:11'),
(17, 'test-en', 'teST', 'test', 'djkdk', '2024-12-19 13:55:12', '2024-12-19 14:07:23'),
(19, 'test-en', 'TEST', 'test-fr', 'TEST', '2024-12-19 14:08:22', '2024-12-19 14:08:22'),
(21, 'test-en', 'STT', 'TTTT', 'oui', '2024-12-19 14:08:41', '2024-12-19 14:12:21'),
(23, 'aaaaa', 'bbb', 'ccc', 'ddd', '2024-12-19 14:12:42', '2024-12-19 14:12:42');

-- --------------------------------------------------------

--
-- Structure de la table `app_saison`
--

CREATE TABLE `app_saison` (
  `id` int UNSIGNED NOT NULL,
  `nom_saison_fr` varchar(100) NOT NULL,
  `nom_saison_en` varchar(100) NOT NULL,
  `start_time` date NOT NULL,
  `end_time` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `app_saison`
--

INSERT INTO `app_saison` (`id`, `nom_saison_fr`, `nom_saison_en`, `start_time`, `end_time`) VALUES
(2, 'basse saison', 'base saison', '2025-02-01', '2026-02-12'),
(5, 'test1 fr', 'test1 en', '2025-01-21', '2025-03-28');

-- --------------------------------------------------------

--
-- Structure de la table `app_users`
--

CREATE TABLE `app_users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `etat` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `app_users`
--

INSERT INTO `app_users` (`id`, `name`, `email`, `tel`, `genre`, `password`, `role`, `etat`, `created_at`, `updated_at`) VALUES
(2, 'admin', 'admin@gmail.com', '060000000', 'femme', 'admin', 'admin', '', '2024-12-20 09:55:07', '2024-12-20 09:55:47');

-- --------------------------------------------------------

--
-- Structure de la table `app_vehicule`
--

CREATE TABLE `app_vehicule` (
  `id` int UNSIGNED NOT NULL,
  `nom_vehicule` varchar(100) NOT NULL,
  `modele` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `clim` enum('Oui','Non') NOT NULL,
  `passager` int NOT NULL,
  `transmission` enum('Manuelle','Automatique') NOT NULL,
  `portes` int NOT NULL,
  `bagages` varchar(50) NOT NULL,
  `images` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `app_vehicule`
--

INSERT INTO `app_vehicule` (`id`, `nom_vehicule`, `modele`, `description`, `clim`, `passager`, `transmission`, `portes`, `bagages`, `images`, `created_at`, `updated_at`) VALUES
(3, 'test', 'test', 'test', 'Oui', 2, 'Automatique', 3, '2pt', NULL, '2024-12-22 12:03:36', '2024-12-22 12:03:36');

-- --------------------------------------------------------

--
-- Structure de la table `app_ville`
--

CREATE TABLE `app_ville` (
  `id` int NOT NULL,
  `name_ville` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `app_ville`
--

INSERT INTO `app_ville` (`id`, `name_ville`, `created_at`, `updated_at`) VALUES
(2, 'casablanca', '2024-12-20 10:41:25', '2024-12-20 11:00:13'),
(3, 'rabat', '2024-12-20 11:00:06', '2024-12-20 11:00:06'),
(4, 'marakech', '2024-12-20 11:00:24', '2024-12-20 11:00:24');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `app_categories_voiture`
--
ALTER TABLE `app_categories_voiture`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `app_saison`
--
ALTER TABLE `app_saison`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `app_users`
--
ALTER TABLE `app_users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `app_vehicule`
--
ALTER TABLE `app_vehicule`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `app_ville`
--
ALTER TABLE `app_ville`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `app_categories_voiture`
--
ALTER TABLE `app_categories_voiture`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `app_saison`
--
ALTER TABLE `app_saison`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `app_users`
--
ALTER TABLE `app_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `app_vehicule`
--
ALTER TABLE `app_vehicule`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `app_ville`
--
ALTER TABLE `app_ville`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;