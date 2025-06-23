-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 17 juin 2025 à 23:36
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
-- Base de données : `stage`
--

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

CREATE TABLE `action` (
  `Id_Action` int(11) NOT NULL,
  `Id_Annee` int(11) DEFAULT NULL,
  `Id_Departement` int(11) DEFAULT NULL,
  `numSemestre` int(11) DEFAULT NULL,
  `Id_Etudiant` int(11) DEFAULT NULL,
  `Id_Stage` int(11) DEFAULT NULL,
  `Id_TypeAction` int(11) DEFAULT NULL,
  `date_realisation` date DEFAULT NULL,
  `lienDocument` text DEFAULT NULL,
  `est_notifie` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `action`
--

INSERT INTO `action` (`Id_Action`, `Id_Annee`, `Id_Departement`, `numSemestre`, `Id_Etudiant`, `Id_Stage`, `Id_TypeAction`, `date_realisation`, `lienDocument`, `est_notifie`) VALUES
(1, 1, 1, 2, 1, 1, 1, '2024-02-05', '/uploads/rapport_stage_s2_pierre_martin.pdf', 1),
(2, 1, 1, 2, 1, 1, 2, '2023-10-20', NULL, 1),
(3, 1, 1, 2, 1, 1, 3, '2024-02-10', '/uploads/evaluation_s2_pierre_martin.pdf', 1),
(4, 1, 1, 2, 1, 1, 4, '2024-02-01', '/uploads/convocation_s2_pierre_martin.pdf', 1),
(5, 1, 1, 2, 1, 1, 5, '2024-02-08', '/uploads/fiche_visite_s2_pierre_martin.pdf', 1),
(6, 1, 1, 4, 1, 2, 1, '2024-06-01', '/uploads/rapport_stage_s4_pierre_martin.pdf', 1),
(7, 1, 1, 4, 1, 2, 2, '2024-01-20', NULL, 1),
(8, 1, 1, 4, 1, 2, 3, NULL, NULL, 0),
(9, 1, 1, 4, 1, 2, 4, '2024-06-01', '/uploads/convocation_s4_pierre_martin.pdf', 1),
(10, 1, 1, 4, 1, 2, 5, NULL, NULL, 0),
(11, 1, 1, 2, 2, 3, 1, NULL, NULL, 0),
(12, 1, 1, 2, 2, 3, 2, '2024-01-10', NULL, 1),
(13, 1, 1, 2, 2, 3, 3, '2024-04-20', '/uploads/evaluation_marie_dubois.pdf', 1),
(14, 1, 1, 2, 2, 3, 4, '2024-04-25', '/uploads/convocation_marie_dubois.pdf', 1),
(15, 1, 1, 2, 2, 3, 5, '2024-04-22', '/uploads/fiche_visite_marie_dubois.pdf', 1);

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `Id_Administrateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`Id_Administrateur`) VALUES
(5);

-- --------------------------------------------------------

--
-- Structure de la table `annee`
--

CREATE TABLE `annee` (
  `Id_Annee` int(11) NOT NULL,
  `libelle` varchar(9) NOT NULL,
  `debut` date NOT NULL,
  `fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `annee`
--

INSERT INTO `annee` (`Id_Annee`, `libelle`, `debut`, `fin`) VALUES
(1, '2023-2024', '2023-09-01', '2024-06-30'),
(2, '2024-2025', '2024-09-01', '2025-06-30');

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE `departement` (
  `Id_Departement` int(11) NOT NULL,
  `Libelle` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`Id_Departement`, `Libelle`) VALUES
(1, 'Informatique'),
(2, 'Génie Électrique');

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `Id_Enseignant` int(11) NOT NULL,
  `Bureau` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`Id_Enseignant`, `Bureau`) VALUES
(3, 'Bureau 201 - Bâtiment A'),
(4, 'Bureau 105 - Bâtiment B');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `Id_Entreprise` int(11) NOT NULL,
  `adresse` text DEFAULT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `indicationVisite` text DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`Id_Entreprise`, `adresse`, `code_postal`, `ville`, `indicationVisite`, `tel`) VALUES
(1, '123 Avenue des Technologies', '75001', 'Paris', 'Bâtiment principal, 3ème étage, bureau 301', '0142345678'),
(2, '456 Rue de l\'Innovation', '69000', 'Lyon', 'Tour Innovation, 15ème étage, accueil', '0478901234'),
(3, '789 Boulevard du Numérique', '31000', 'Toulouse', 'Immeuble WebDev, 7ème étage, réception', '0561234567');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `Id_Etudiant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`Id_Etudiant`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `Id_Annee` int(11) NOT NULL,
  `numSemestre` int(11) NOT NULL,
  `Id_Departement` int(11) NOT NULL,
  `Id_Etudiant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`Id_Annee`, `numSemestre`, `Id_Departement`, `Id_Etudiant`) VALUES
(1, 2, 1, 1),
(1, 2, 1, 2),
(1, 4, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `expediteur_id` int(11) NOT NULL,
  `destinataire_id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` datetime NOT NULL DEFAULT current_timestamp(),
  `lu` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `expediteur_id`, `destinataire_id`, `contenu`, `date_envoi`, `lu`) VALUES
(1, 1, 3, 'Bonjour, j\'ai terminé mon rapport de stage S2. Pouvez-vous me confirmer sa réception ?', '2024-02-05 14:30:00', 1),
(2, 3, 1, 'Bonjour Pierre, j\'ai bien reçu votre rapport S2. Félicitations pour ce bon travail !', '2024-02-06 09:15:00', 1),
(3, 9, 3, 'Le stage de Pierre Martin s\'est très bien passé. Évaluation très positive de notre part.', '2024-02-10 11:00:00', 1),
(4, 1, 4, 'Bonjour, je souhaiterais prendre rendez-vous pour discuter de mon stage S4. Quand seriez-vous disponible ?', '2024-01-25 10:30:00', 1),
(5, 4, 1, 'Bonjour Pierre, je suis libre mardi prochain à 14h. Cela vous convient-il ?', '2024-01-25 14:20:00', 1),
(6, 1, 4, 'Parfait, je serai présent mardi à 14h dans votre bureau. Merci !', '2024-01-25 14:25:00', 0),
(7, 7, 4, 'Bonjour, Pierre Martin a commencé son stage S4 aujourd\'hui. Tout se passe bien pour le moment.', '2024-02-01 09:15:00', 1),
(8, 4, 7, 'Parfait, merci pour cette information. N\'hésitez pas à me tenir informé de son évolution.', '2024-02-01 11:30:00', 0),
(9, 2, 3, 'Bonjour, j\'ai une question concernant la rédaction de mon rapport de stage.', '2024-04-10 16:45:00', 1),
(10, 3, 2, 'Bonjour Marie, je suis disponible demain à 10h pour en discuter si vous le souhaitez.', '2024-04-10 17:20:00', 0),
(11, 6, 1, 'Rappel : votre soutenance de stage S4 aura lieu le 15 juin à 14h en Amphi A.', '2024-06-01 08:00:00', 1),
(12, 6, 2, 'Rappel : votre soutenance de stage aura lieu le 10 mai à 15h en Salle 203.', '2024-05-01 08:00:00', 1),
(13, 3, 1, 'Félicitations pour votre excellente soutenance S2 ! Bon courage pour votre stage S4.', '2024-02-21 16:00:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `secretaire`
--

CREATE TABLE `secretaire` (
  `Id_Secretaire` int(11) NOT NULL,
  `Bureau` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `secretaire`
--

INSERT INTO `secretaire` (`Id_Secretaire`, `Bureau`) VALUES
(6, 'Secrétariat - Bâtiment Principal');

-- --------------------------------------------------------

--
-- Structure de la table `semestre`
--

CREATE TABLE `semestre` (
  `numSemestre` int(11) NOT NULL,
  `Id_Departement` int(11) NOT NULL,
  `Id_Enseignant` int(11) DEFAULT NULL,
  `Id_Annee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `semestre`
--

INSERT INTO `semestre` (`numSemestre`, `Id_Departement`, `Id_Enseignant`, `Id_Annee`) VALUES
(1, 1, 3, 1),
(1, 2, 3, 1),
(2, 1, 3, 1),
(2, 2, 4, 1),
(3, 1, 4, 1),
(4, 1, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `stage`
--

CREATE TABLE `stage` (
  `Id_Stage` int(11) NOT NULL,
  `Id_Annee` int(11) DEFAULT NULL,
  `Id_Departement` int(11) DEFAULT NULL,
  `numSemestre` int(11) DEFAULT NULL,
  `Id_Etudiant` int(11) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `date_soutenance` date DEFAULT NULL,
  `salle_Soutenance` varchar(50) DEFAULT NULL,
  `Id_Enseignant` int(11) DEFAULT NULL,
  `Id_TuteurEntreprise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `stage`
--

INSERT INTO `stage` (`Id_Stage`, `Id_Annee`, `Id_Departement`, `numSemestre`, `Id_Etudiant`, `date_debut`, `date_fin`, `mission`, `date_soutenance`, `salle_Soutenance`, `Id_Enseignant`, `Id_TuteurEntreprise`) VALUES
(1, 1, 1, 2, 1, '2023-11-01', '2024-01-31', 'Développement d\'un site e-commerce avec PHP et MySQL', '2024-02-20', 'Salle 105 - Bâtiment A', 3, 9),
(2, 1, 1, 4, 1, '2024-02-01', '2024-05-31', 'Développement d\'une application web de gestion des commandes avec React et Node.js', '2024-06-15', 'Amphi A - Bâtiment Principal', 4, 7),
(3, 1, 1, 2, 2, '2024-01-15', '2024-04-15', 'Analyse et amélioration des performances d\'une base de données MySQL', '2024-05-10', 'Salle 203 - Bâtiment A', 3, 8);

-- --------------------------------------------------------

--
-- Structure de la table `tuteur_entreprise`
--

CREATE TABLE `tuteur_entreprise` (
  `Id_TuteurEntreprise` int(11) NOT NULL,
  `Id_Entreprise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `tuteur_entreprise`
--

INSERT INTO `tuteur_entreprise` (`Id_TuteurEntreprise`, `Id_Entreprise`) VALUES
(7, 1),
(8, 2),
(9, 3);

-- --------------------------------------------------------

--
-- Structure de la table `typeaction`
--

CREATE TABLE `typeaction` (
  `Id_TypeAction` int(11) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `Executant` varchar(50) DEFAULT NULL,
  `Destinataire` varchar(50) DEFAULT NULL,
  `delaiEnJours` int(11) DEFAULT NULL,
  `ReferenceDelai` varchar(50) DEFAULT NULL,
  `requisDoc` tinyint(1) DEFAULT NULL,
  `LienModeleDoc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `typeaction`
--

INSERT INTO `typeaction` (`Id_TypeAction`, `libelle`, `Executant`, `Destinataire`, `delaiEnJours`, `ReferenceDelai`, `requisDoc`, `LienModeleDoc`) VALUES
(1, 'Remise du rapport de stage', 'Etudiant', 'Tuteur pédagogique', 7, 'date_fin', 1, '/modeles/rapport_stage_modele.docx'),
(2, 'Prise de contact avec l\'entreprise', 'Tuteur pédagogique', 'Tuteur entreprise', 14, 'date_debut', 0, NULL),
(3, 'Évaluation du stage par l\'entreprise', 'Tuteur entreprise', 'Tuteur pédagogique', 15, 'date_fin', 1, '/modeles/evaluation_entreprise_modele.pdf'),
(4, 'Convocation à la soutenance', 'Secrétaire', 'Etudiant', 10, 'date_soutenance', 1, '/modeles/convocation_soutenance_modele.pdf'),
(5, 'Remise de la fiche de visite', 'Tuteur pédagogique', 'Secrétaire', 5, 'date_fin', 1, '/modeles/fiche_visite_modele.docx');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `Id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`Id`, `nom`, `prenom`, `email`, `telephone`, `login`, `mot_de_passe`) VALUES
(1, 'Martin', 'Pierre', 'pierre.martin@student.univ.fr', '0612345678', 'pmartin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(2, 'Dubois', 'Marie', 'marie.dubois@student.univ.fr', '0623456789', 'mdubois', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(3, 'Leclerc', 'Jean', 'jean.leclerc@univ.fr', '0634567890', 'jleclerc', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(4, 'Moreau', 'Anne', 'anne.moreau@univ.fr', '0645678901', 'amoreau', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(5, 'Admin', 'Super', 'admin@univ.fr', '0656789012', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(6, 'Secretaire', 'Sylvie', 'sylvie.secretaire@univ.fr', '0667890123', 'ssecretaire', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(7, 'Dupont', 'Paul', 'paul.dupont@techcorp.fr', '0678901234', 'pdupont', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(8, 'Garcia', 'Sophie', 'sophie.garcia@innovtech.fr', '0689012345', 'sgarcia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(9, 'Lemoine', 'Marc', 'marc.lemoine@webdev.fr', '0690123456', 'mlemoine', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`Id_Action`),
  ADD KEY `Id_Annee` (`Id_Annee`),
  ADD KEY `Id_Etudiant` (`Id_Etudiant`),
  ADD KEY `Id_Stage` (`Id_Stage`),
  ADD KEY `Id_TypeAction` (`Id_TypeAction`);

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`Id_Administrateur`);

--
-- Index pour la table `annee`
--
ALTER TABLE `annee`
  ADD PRIMARY KEY (`Id_Annee`);

--
-- Index pour la table `departement`
--
ALTER TABLE `departement`
  ADD PRIMARY KEY (`Id_Departement`);

--
-- Index pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`Id_Enseignant`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`Id_Entreprise`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`Id_Etudiant`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`Id_Annee`,`numSemestre`,`Id_Departement`,`Id_Etudiant`),
  ADD KEY `Id_Etudiant` (`Id_Etudiant`),
  ADD KEY `numSemestre` (`numSemestre`,`Id_Departement`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expediteur_id` (`expediteur_id`),
  ADD KEY `destinataire_id` (`destinataire_id`);

--
-- Index pour la table `secretaire`
--
ALTER TABLE `secretaire`
  ADD PRIMARY KEY (`Id_Secretaire`);

--
-- Index pour la table `semestre`
--
ALTER TABLE `semestre`
  ADD PRIMARY KEY (`numSemestre`,`Id_Departement`),
  ADD KEY `Id_Departement` (`Id_Departement`),
  ADD KEY `Id_Enseignant` (`Id_Enseignant`),
  ADD KEY `Id_Annee` (`Id_Annee`);

--
-- Index pour la table `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`Id_Stage`),
  ADD KEY `Id_Annee` (`Id_Annee`),
  ADD KEY `Id_Etudiant` (`Id_Etudiant`),
  ADD KEY `Id_Enseignant` (`Id_Enseignant`),
  ADD KEY `Id_TuteurEntreprise` (`Id_TuteurEntreprise`);

--
-- Index pour la table `tuteur_entreprise`
--
ALTER TABLE `tuteur_entreprise`
  ADD PRIMARY KEY (`Id_TuteurEntreprise`),
  ADD KEY `Id_Entreprise` (`Id_Entreprise`);

--
-- Index pour la table `typeaction`
--
ALTER TABLE `typeaction`
  ADD PRIMARY KEY (`Id_TypeAction`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `action`
--
ALTER TABLE `action`
  MODIFY `Id_Action` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `annee`
--
ALTER TABLE `annee`
  MODIFY `Id_Annee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
  MODIFY `Id_Departement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `Id_Entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `stage`
--
ALTER TABLE `stage`
  MODIFY `Id_Stage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `tuteur_entreprise`
--
ALTER TABLE `tuteur_entreprise`
  MODIFY `Id_TuteurEntreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `typeaction`
--
ALTER TABLE `typeaction`
  MODIFY `Id_TypeAction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `action`
--
ALTER TABLE `action`
  ADD CONSTRAINT `action_ibfk_1` FOREIGN KEY (`Id_Annee`) REFERENCES `annee` (`Id_Annee`),
  ADD CONSTRAINT `action_ibfk_2` FOREIGN KEY (`Id_Etudiant`) REFERENCES `etudiant` (`Id_Etudiant`),
  ADD CONSTRAINT `action_ibfk_3` FOREIGN KEY (`Id_Stage`) REFERENCES `stage` (`Id_Stage`),
  ADD CONSTRAINT `action_ibfk_4` FOREIGN KEY (`Id_TypeAction`) REFERENCES `typeaction` (`Id_TypeAction`);

--
-- Contraintes pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `administrateur_ibfk_1` FOREIGN KEY (`Id_Administrateur`) REFERENCES `utilisateur` (`Id`);

--
-- Contraintes pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD CONSTRAINT `enseignant_ibfk_1` FOREIGN KEY (`Id_Enseignant`) REFERENCES `utilisateur` (`Id`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`Id_Etudiant`) REFERENCES `utilisateur` (`Id`);

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`Id_Etudiant`) REFERENCES `etudiant` (`Id_Etudiant`),
  ADD CONSTRAINT `inscription_ibfk_2` FOREIGN KEY (`numSemestre`,`Id_Departement`) REFERENCES `semestre` (`numSemestre`, `Id_Departement`),
  ADD CONSTRAINT `inscription_ibfk_3` FOREIGN KEY (`Id_Annee`) REFERENCES `annee` (`Id_Annee`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`expediteur_id`) REFERENCES `utilisateur` (`Id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`destinataire_id`) REFERENCES `utilisateur` (`Id`);

--
-- Contraintes pour la table `secretaire`
--
ALTER TABLE `secretaire`
  ADD CONSTRAINT `secretaire_ibfk_1` FOREIGN KEY (`Id_Secretaire`) REFERENCES `utilisateur` (`Id`);

--
-- Contraintes pour la table `semestre`
--
ALTER TABLE `semestre`
  ADD CONSTRAINT `semestre_ibfk_1` FOREIGN KEY (`Id_Departement`) REFERENCES `departement` (`Id_Departement`),
  ADD CONSTRAINT `semestre_ibfk_2` FOREIGN KEY (`Id_Enseignant`) REFERENCES `enseignant` (`Id_Enseignant`),
  ADD CONSTRAINT `semestre_ibfk_3` FOREIGN KEY (`Id_Annee`) REFERENCES `annee` (`Id_Annee`);

--
-- Contraintes pour la table `stage`
--
ALTER TABLE `stage`
  ADD CONSTRAINT `stage_ibfk_1` FOREIGN KEY (`Id_Annee`) REFERENCES `annee` (`Id_Annee`),
  ADD CONSTRAINT `stage_ibfk_2` FOREIGN KEY (`Id_Etudiant`) REFERENCES `etudiant` (`Id_Etudiant`),
  ADD CONSTRAINT `stage_ibfk_3` FOREIGN KEY (`Id_Enseignant`) REFERENCES `enseignant` (`Id_Enseignant`),
  ADD CONSTRAINT `stage_ibfk_4` FOREIGN KEY (`Id_TuteurEntreprise`) REFERENCES `tuteur_entreprise` (`Id_TuteurEntreprise`);

--
-- Contraintes pour la table `tuteur_entreprise`
--
ALTER TABLE `tuteur_entreprise`
  ADD CONSTRAINT `tuteur_entreprise_ibfk_1` FOREIGN KEY (`Id_Entreprise`) REFERENCES `entreprise` (`Id_Entreprise`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
