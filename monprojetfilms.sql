-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 09 mai 2024 à 21:36
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
-- Base de données : `monprojetfilms`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Action'),
(7, 'Fantastique');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `films_id` int NOT NULL,
  `users_id` int NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `date_commentaire` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D9BEC0C4939610EE` (`films_id`),
  KEY `IDX_D9BEC0C467B3B43D` (`users_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `films_id`, `users_id`, `content`, `date_commentaire`) VALUES
(3, 4, 16, 'Bof , j\'ai connu mieux!', '2024-05-02 19:26:26'),
(4, 4, 16, 'Vraiment un super film , les acteurs sont incroyable et les paysages sont magnifiques . Je recommande ce film !', '2024-05-02 19:28:04'),
(7, 2, 16, 'Quel film !! Je le conseil ', '2024-05-02 20:10:43'),
(9, 2, 18, 'Un film remarquable , du grand ART!!!!', '2024-05-03 09:32:43'),
(14, 11, 17, 'super !!', '2024-05-06 22:01:01'),
(15, 11, 17, 'top!!', '2024-05-06 22:03:50'),
(17, 15, 17, 'fun!', '2024-05-06 22:05:20'),
(18, 4, 17, 'fun!', '2024-05-06 22:06:37'),
(19, 16, 17, 'Au top!', '2024-05-06 22:07:15'),
(20, 11, 17, 'fun!', '2024-05-07 09:33:38'),
(22, 11, 17, 'fou', '2024-05-09 21:34:54');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240507124255', '2024-05-07 12:51:26', 193),
('DoctrineMigrations\\Version20240509184714', '2024-05-09 18:47:21', 54);

-- --------------------------------------------------------

--
-- Structure de la table `films`
--

DROP TABLE IF EXISTS `films`;
CREATE TABLE IF NOT EXISTS `films` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filmscateg_id` int NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_de_sortie` datetime DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `images` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CEECCA512E55E5CC` (`filmscateg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`id`, `filmscateg_id`, `title`, `date_de_sortie`, `content`, `images`) VALUES
(2, 1, 'DarkLand', '1993-07-02 00:00:00', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Animi, magni temporibus soluta ipsam quasi necessitatibus adipisci repellendus velit perspiciatis nesciunt libero pariatur, inventore, dolorum et enim doloremque voluptatem sapiente. Deleniti?', NULL),
(4, 7, 'jojo', '2016-05-09 16:43:15', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Animi, magni temporibus soluta ipsam quasi necessitatibus adipisci repellendus velit perspiciatis nesciunt libero pariatur, inventore, dolorum et enim doloremque voluptatem sapiente. Deleniti?', NULL),
(11, 1, 'zouzou', '2023-12-02 00:00:00', 'les loulous', NULL),
(15, 7, 'lou', '2023-11-02 00:00:00', 'lou et le loup', NULL),
(16, 1, 'gégé', '1980-12-22 00:00:00', 'gégé les gros biscotos', NULL),
(18, 1, 'Prince Lorel', '2015-12-05 00:00:00', 'Un prince au service du bien', NULL),
(24, 7, 'ss', '2023-12-02 00:00:00', 'ss', NULL),
(25, 1, 'fffff', '1998-05-07 00:00:00', 'fffff', NULL),
(26, 1, 'ssssssssssssss', '2000-05-02 00:00:00', 'ssssssssss', NULL),
(27, 1, 'dd', '1980-12-22 00:00:00', 'dddd', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `likeuser_id` int NOT NULL,
  `filmslikes_id` int NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_49CA4E7D608427E4` (`likeuser_id`),
  KEY `IDX_49CA4E7DE961C981` (`filmslikes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id`, `likeuser_id`, `filmslikes_id`, `datetime`) VALUES
(39, 19, 11, '2024-05-07 19:38:03'),
(41, 20, 4, '2024-05-07 20:25:10'),
(44, 17, 16, '2024-05-09 14:01:08'),
(50, 17, 4, '2024-05-09 14:08:48');

-- --------------------------------------------------------

--
-- Structure de la table `like_commentaire`
--

DROP TABLE IF EXISTS `like_commentaire`;
CREATE TABLE IF NOT EXISTS `like_commentaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `like_user_id` int DEFAULT NULL,
  `likecom_id_id` int DEFAULT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D4358632F4E399B6` (`like_user_id`),
  KEY `IDX_D4358632A8075CCD` (`likecom_id_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `like_commentaire`
--

INSERT INTO `like_commentaire` (`id`, `like_user_id`, `likecom_id_id`, `date_time`) VALUES
(7, 17, 3, '2024-05-07 13:52:39'),
(9, 17, 20, '2024-05-07 19:36:46');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banni` tinyint(1) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` json NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `firstname`, `email`, `banni`, `password`, `role`) VALUES
(13, 'hugs', 'grey', 'h@h.fr', 1, '$2y$13$Xm41zF/oIriYYAeGekJ2iePk.oPBB8NRsQIlZ1o2Ha8Ct8yoBCijW', '[\"ROLE_USER\"]'),
(14, 'gerard', 'boulon', 'g@g.fr', 1, '$2y$13$Ouq1yMEyZrbe1P0ec9yX7uBL2hnI7FUqCgC3l3NJ49bsoBTFT42.W', '[\"ROLE_USER\"]'),
(16, 'dd', 'dd', 'dd@dd.fr', 1, '$2y$13$EIP8yNkJYv9dzg1wlKL1S.pKSWOgdFt48gCfmvfKTmkNIK2pNiZom', '[\"ROLE_USER\"]'),
(17, 'ff', 'ff', 'ff@ff.fr', NULL, '$2y$13$ONOM3y9D74P4K0/dIgQxluneVdP08ToM1i4EiKvY8Iia9DRR1tx0y', '[\"ROLE_USER\"]'),
(18, 'Gérard', 'Boulot', 'gege@trez.fr', NULL, '$2y$13$PaUGfQds1U8BU6Z2hUAYF.DUmmwmOKXvHQ3ibbAn1DCmqdBrWyA16', '[\"ROLE_USER\"]'),
(19, 'Steve', 'Elsens', 'steve.elsens@hotmail.com', NULL, '$2y$13$EdOoJ0vKOhf1lWrKGAWNJuzmmhNsBHJXo8pIAK0vT8/nUBuvU8pL2', '[\"ROLE_USER\", \"ROLE_ADMIN\"]'),
(20, 'Aldo', 'Kilos', 'aldo@kilos.fr', NULL, '$2y$13$LdWXRz7sLadRE2FixsDmMeBA6ZfW1ZqvaTyHlvL9lxqoBXj.EWgm6', '[\"ROLE_USER\"]');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `FK_D9BEC0C467B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_D9BEC0C4939610EE` FOREIGN KEY (`films_id`) REFERENCES `films` (`id`);

--
-- Contraintes pour la table `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `FK_CEECCA512E55E5CC` FOREIGN KEY (`filmscateg_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `FK_49CA4E7D608427E4` FOREIGN KEY (`likeuser_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_49CA4E7DE961C981` FOREIGN KEY (`filmslikes_id`) REFERENCES `films` (`id`);

--
-- Contraintes pour la table `like_commentaire`
--
ALTER TABLE `like_commentaire`
  ADD CONSTRAINT `FK_D4358632A8075CCD` FOREIGN KEY (`likecom_id_id`) REFERENCES `commentaires` (`id`),
  ADD CONSTRAINT `FK_D4358632F4E399B6` FOREIGN KEY (`like_user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
