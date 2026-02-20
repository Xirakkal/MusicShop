-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 19 déc. 2025 à 16:28
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `301_musique`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `id_auteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `titre`, `contenu`, `id_categorie`, `id_auteur`) VALUES
(4, 'Article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean rutrum porttitor tortor sed rutrum. Morbi pulvinar, justo vitae placerat fermentum, risus dolor pellentesque quam, at porta augue nunc et justo. Sed id sapien et tortor ullamcorper facilisis. Integer in semper magna. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam blandit mauris a purus tristique, bibendum euismod sem pulvinar. Nullam et dui ultricies, tempor neque in, condimentum turpis. Praesent iaculis libero non dignissim consequat. Sed nec erat ac odio lacinia fringilla at non enim.\r\n\r\nUt ultricies sem quis tortor feugiat efficitur. Proin volutpat quam eu bibendum rutrum. Aenean viverra et velit in dictum. Aliquam consequat est ac dolor laoreet semper. Cras in accumsan justo. Ut ac arcu tristique dui efficitur bibendum. Maecenas convallis libero metus, id luctus odio laoreet sit amet. Maecenas in purus nulla. Praesent gravida pretium vulputate.\r\n\r\nNulla vitae augue et lorem consequat porttitor. Sed placerat nibh libero. Suspendisse a nulla ipsum. Phasellus lacinia libero et condimentum dictum. Maecenas nec vulputate mi, vel feugiat neque. Sed vel ante vitae risus dignissim bibendum. Praesent nunc sapien, mollis vitae libero sit amet, fringilla lobortis felis. Nunc metus eros, tempor eu sem ac, lacinia eleifend dui. Ut dictum ultrices augue, varius lobortis dui elementum non. Quisque dignissim, purus nec volutpat laoreet, nunc justo consectetur libero, vel tincidunt quam eros nec neque. Aenean aliquet lobortis neque sit amet congue. Etiam vel aliquet lorem. Vivamus aliquet gravida ex, nec vulputate nisi luctus quis. Donec', 2, 10),
(7, 'Article 2', 'Fusce tempus viverra magna, ut iaculis urna condimentum eget. Curabitur id tincidunt lectus. Sed ac ornare mi. Mauris vel odio non nisl efficitur volutpat. Fusce ultricies nisl nec ipsum fermentum mollis. Fusce sed interdum lacus. Aliquam a metus sem. Cras euismod nunc diam. Ut lorem augue, porta ac semper at, blandit eget purus. Nulla cursus tempus risus, nec lobortis sem malesuada vel. Integer id justo velit. Sed pharetra est eu aliquet tristique. Nunc nisi risus, rhoncus nec hendrerit sit amet, eleifend sit amet nisi. Praesent porta diam purus, quis feugiat elit sollicitudin ac. Aliquam gravida risus tellus, sit amet sagittis leo semper sed. Proin ac dui sed ligula rhoncus aliquam sit amet a augue.', 1, 10),
(8, 'Article 3', 'Mauris volutpat consequat mauris et tincidunt. Vestibulum condimentum sapien massa, non placerat ante tincidunt sed. Vestibulum erat urna, tincidunt consectetur viverra eget, gravida at leo. Vestibulum vitae arcu id ipsum varius venenatis. Sed tellus felis, tempor et arcu vitae, hendrerit pulvinar nunc. Vestibulum eget feugiat orci. Nullam quis maximus justo. Pellentesque elit nibh, imperdiet quis libero et, volutpat fermentum lectus. Pellentesque ultricies porttitor erat, ac sodales purus consequat vel. Sed laoreet velit nunc, non pretium felis pellentesque vitae. Praesent at erat accumsan, sodales turpis nec, malesuada velit. Donec dapibus turpis facilisis, sagittis nunc at, luctus nisi. Aenean non turpis ex. Vestibulum molestie, diam sed tempor efficitur, urna ante efficitur arcu, in feugiat nisl velit sed tortor. Quisque interdum dolor et nisi eleifend, sed eleifend eros commodo. Quisque vel neque sit amet felis consequat tristique.\r\n\r\nDuis non lobortis orci, quis rhoncus diam. Nullam laoreet ultrices justo, nec tristique dui eleifend ac. Ut laoreet sollicitudin nibh, et auctor sem lacinia ac. Proin pharetra sodales maximus. In mollis quam non libero consequat, non laoreet nunc scelerisque. Cras consectetur eu quam posuere bibendum. Vivamus sed placerat felis. In eget erat vel neque lacinia euismod a et eros. Etiam ullamcorper venenatis dolor et placerat. Ut sed elit porta libero condimentum sodales quis vitae nibh. Donec tincidunt dui vitae mi lobortis laoreet.', 1, 10),
(9, 'Article 4', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut lobortis pulvinar ipsum vitae faucibus. Cras hendrerit, dolor eu varius tempus, lorem diam posuere turpis, at lacinia est risus vehicula tortor. Phasellus mattis arcu ut urna cursus, id venenatis sem faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nullam laoreet sagittis ligula quis bibendum. Donec ac quam tristique, dignissim ante ut, mollis ligula. Etiam ultrices eget elit ac euismod. Proin elit nulla, aliquam sit amet elit ut, lacinia egestas neque. Proin non pellentesque neque. Quisque sit amet viverra velit. Integer hendrerit ipsum nibh, eu viverra justo commodo in. Duis massa ante, sollicitudin non vehicula vitae, pretium ut turpis. Nulla orci ipsum, efficitur nec nisi et, sagittis aliquam arcu.\r\n\r\nMauris volutpat consequat mauris et tincidunt. Vestibulum condimentum sapien massa, non placerat ante tincidunt sed. Vestibulum erat urna, tincidunt consectetur viverra eget, gravida at leo. Vestibulum vitae arcu id ipsum varius venenatis. Sed tellus felis, tempor et arcu vitae, hendrerit pulvinar nunc. Vestibulum eget feugiat orci. Nullam quis maximus justo. Pellentesque elit nibh, imperdiet quis libero et, volutpat fermentum lectus. Pellentesque ultricies porttitor erat, ac sodales purus consequat vel. Sed laoreet velit nunc, non pretium felis pellentesque vitae. Praesent at erat accumsan, sodales turpis nec, malesuada velit. Donec dapibus turpis facilisis, sagittis nunc at, luctus nisi. Aenean non turpis ex. Vestibulum molestie, diam sed tempor efficitur, urna ante efficitur arcu, in feugiat nisl velit sed tortor. Quisque interdum dolor et nisi eleifend, sed eleifend eros commodo. Quisque vel neque sit amet felis consequat tristique.\r\n\r\nDuis non lobortis orci, quis rhoncus diam. Nullam laoreet ultrices justo, nec tristique dui eleifend ac. Ut laoreet sollicitudin nibh, et auctor sem lacinia ac. Proin pharetra sodales maximus. In mollis quam non libero consequat, non laoreet nunc scelerisque. Cras consectetur eu quam posuere bibendum. Vivamus sed placerat felis. In eget erat vel neque lacinia euismod a et eros. Etiam ullamcorper venenatis dolor et placerat. Ut sed elit porta libero condimentum sodales quis vitae nibh. Donec tincidunt dui vitae mi lobortis laoreet.\r\n\r\nDuis congue accumsan nisi, eu varius ante efficitur mattis. Aenean at accumsan quam, sed congue enim. Phasellus quis tellus diam. Vivamus scelerisque, ante quis euismod mollis, libero leo dignissim orci, tristique convallis arcu enim sed leo. Maecenas a justo tempus magna tincidunt aliquam. Vivamus finibus fermentum commodo. Ut vehicula sit amet orci at vulputate. Ut pellentesque est a felis accumsan, vel porttitor massa tincidunt.', 3, 10);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'Général'),
(2, 'Actualités'),
(3, 'Guide');

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prix` int(11) NOT NULL,
  `description` text NOT NULL,
  `difficulte` enum('débutant','intermédiaire','avancé') NOT NULL,
  `id_auteur` int(11) NOT NULL,
  `id_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id`, `nom`, `prix`, `description`, `difficulte`, `id_auteur`, `id_type`) VALUES
(2, 'Cours de batterie', 50, 'Vous enseignera les rudiments de batterie - dispo les week-ends', 'débutant', 10, 9),
(3, 'Basse - perfectionnement', 50, 'Technique les plus avancées à la basse', 'avancé', 10, 7),
(4, 'Guitare acoustique', 25, 'Cours de guitare acoustique personnalisé', 'intermédiaire', 10, 1),
(5, 'Saxophone - Débutant', 25, 'Cours pour commencer la pratique du saxophone', 'débutant', 10, 4),
(6, 'Piano - Avancé', 60, 'Cours de piano pour préparer le conservatoire', 'avancé', 10, 8);

-- --------------------------------------------------------

--
-- Structure de la table `instruments`
--

CREATE TABLE `instruments` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `prix` float(10,2) NOT NULL,
  `verification` tinyint(1) NOT NULL DEFAULT 0,
  `id_type` int(11) NOT NULL,
  `id_auteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `instruments`
--

INSERT INTO `instruments` (`id`, `nom`, `description`, `prix`, `verification`, `id_type`, `id_auteur`) VALUES
(1, 'Les Paul - Bon état', 'Les Paul en bon état - remise en main propre', 2850.00, 1, 2, 10),
(4, 'Hautbois', 'Vends un hautbois peu utilisé', 1000.00, 1, 5, 10),
(5, 'Trompette', 'Trompette d\'exception - restaurée à neuf', 2500.00, 1, 4, 10),
(6, 'Basse Warwick', 'Basse Warwick couleur bois, jamais utilisée', 865.00, 1, 7, 10);

-- --------------------------------------------------------

--
-- Structure de la table `partitions`
--

CREATE TABLE `partitions` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `prix` double(10,2) NOT NULL,
  `id_style` int(11) NOT NULL,
  `id_auteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `partitions`
--

INSERT INTO `partitions` (`id`, `nom`, `description`, `prix`, `id_style`, `id_auteur`) VALUES
(2, 'Walk - Pantera', 'Walk est une chanson et le quatrième single de Pantera issu du sixième album Vulgar Display of Power.', 15.00, 3, 10),
(3, 'Can\'t Stop - RHCP', 'Can\'t Stop est une chanson des Red Hot Chili Peppers. C\'est le troisième single extrait de leur album de 2002, By the Way.', 20.00, 4, 10),
(4, 'From Mars to Sirius - Edition Complète', 'From Mars to Sirius est le troisième album du groupe de death metal progressif français Gojira, sorti en 2005.', 75.00, 3, 10),
(5, 'Fly me to the Moon', 'Fly Me to the Moon (en français « emmène-moi sur la Lune ») est un standard de jazz américain, chanson écrite et composée par Bart Howard en 1954. La version de Frank Sinatra dans son album It Might as Well Be Swing (en) enregistrée en 1964 est une des plus célèbres.', 10.00, 5, 10);

-- --------------------------------------------------------

--
-- Structure de la table `styles`
--

CREATE TABLE `styles` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `styles`
--

INSERT INTO `styles` (`id`, `nom`) VALUES
(1, 'Musique de chambre'),
(2, 'Punk'),
(3, 'Metal'),
(4, 'Rock'),
(5, 'Jazz'),
(6, 'Indie');

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`id`, `nom`) VALUES
(1, 'Guitare acoustique'),
(2, 'Guitare électrique'),
(3, 'Synthétiseur'),
(4, 'Cuivre'),
(5, 'Bois'),
(6, 'Percussions'),
(7, 'Basse'),
(8, 'Piano'),
(9, 'Percussions');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `pseudo` varchar(255) DEFAULT NULL,
  `date_creation` date NOT NULL DEFAULT current_timestamp(),
  `role` enum('visiteur','musicien','rédacteur','modérateur','administrateur') NOT NULL DEFAULT 'visiteur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `mdp`, `pseudo`, `date_creation`, `role`) VALUES
(10, 'pbuch57@outlook.fr', 'PassWord', 'Xirakkal', '2025-12-15', 'administrateur'),
(13, 'redacteur@mail.com', 'motdepasse', 'Rédacteur1', '2025-12-16', 'rédacteur'),
(16, 'visiteur@mail.com', 'motdepasse', 'Visiteur1', '2025-12-17', 'visiteur'),
(17, 'musicien@mail.com', 'motdepasse', 'Musicien1', '2025-12-17', 'musicien'),
(18, 'moderateur@mail.com', 'motdepasse', 'Modérateur1', '2025-12-18', 'modérateur'),
(19, 'administrateur@mail.com', 'motdepasse', 'Administrateur1', '2025-12-18', 'administrateur');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categorie` (`id_categorie`,`id_auteur`),
  ADD KEY `id_auteur` (`id_auteur`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_auteur` (`id_auteur`,`id_type`),
  ADD KEY `id_type` (`id_type`);

--
-- Index pour la table `instruments`
--
ALTER TABLE `instruments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type` (`id_type`,`id_auteur`),
  ADD KEY `id_auteur` (`id_auteur`);

--
-- Index pour la table `partitions`
--
ALTER TABLE `partitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_style` (`id_style`,`id_auteur`),
  ADD KEY `id_auteur` (`id_auteur`);

--
-- Index pour la table `styles`
--
ALTER TABLE `styles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `instruments`
--
ALTER TABLE `instruments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `partitions`
--
ALTER TABLE `partitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `styles`
--
ALTER TABLE `styles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`id_auteur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `cours_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types` (`id`),
  ADD CONSTRAINT `cours_ibfk_2` FOREIGN KEY (`id_auteur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `instruments`
--
ALTER TABLE `instruments`
  ADD CONSTRAINT `instruments_ibfk_1` FOREIGN KEY (`id_auteur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `instruments_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `types` (`id`);

--
-- Contraintes pour la table `partitions`
--
ALTER TABLE `partitions`
  ADD CONSTRAINT `partitions_ibfk_1` FOREIGN KEY (`id_auteur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `partitions_ibfk_2` FOREIGN KEY (`id_style`) REFERENCES `styles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
