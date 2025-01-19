-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 19 jan. 2025 à 21:51
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
  `est_notifie` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `action`
--

INSERT INTO `action` (`Id_Action`, `Id_Annee`, `Id_Departement`, `numSemestre`, `Id_Etudiant`, `Id_Stage`, `Id_TypeAction`, `date_realisation`, `lienDocument`, `est_notifie`) VALUES
(1, 1, 1, 4, 1, 1, 1, NULL, NULL, 1),
(2, 1, 1, 4, 1, 1, 2, NULL, NULL, 1);

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
(3);

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
(1, '2023-2024', '2023-09-01', '2024-06-30');

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
(1, 'Informatique');

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
(2, 'Bureau 101');

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
(1, '123 Rue de Paris', '75000', 'Paris', '5ème étage', '0156789012');

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
(1);

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
(1, 4, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `secretaire`
--

CREATE TABLE `secretaire` (
  `Id_Secretaire` int(11) NOT NULL,
  `Bureau` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(4, 1, 2, 1);

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
(1, 1, 1, 4, 1, '2024-01-15', '2024-03-15', 'Développement d\'une application web', '2024-03-20', 'Salle A101', 2, 4);

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
(4, 1);

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
(1, 'Rendre rapport de stage', 'Etudiant', 'Tuteur pédagogique', 5, 'date_fin', 1, 'modeles/rapport_modele.docx'),
(2, 'Prise de contact entreprise', 'Tuteur pédagogique', 'Entreprise', 14, 'date_debut', 0, NULL);

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
(1, 'Dupont', 'Jean', 'jean.dupont@example.com', '0612345678', 'jdupont', '$2y$10$wgyib0zO5Abs9autJALdGu/fNWMPR03O3vHWsojPiCitujjBJbtmC'),
(2, 'Martin', 'Sophie', 'sophie.martin@example.com', '0611223344', 'smartin', '$2y$10$pGDUZTi3HaY1Cjcvk4wzd.sfdxNNvP3xi4yPaCLDxVjS7SwK6yORC'),
(3, 'Poirier', 'Fatou', 'fatou.poirier@example.com', '0600000000', 'admin', '$2y$10$CPivxshAcBttHZf9H9jekehP45lTBR59CwOmk3CGlcMpklUmE/eqS'),
(4, 'ab', 'pierre', 'ab.pierre@example.com', '0123456789', 'pab', '123');

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
  MODIFY `Id_Action` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `annee`
--
ALTER TABLE `annee`
  MODIFY `Id_Annee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
  MODIFY `Id_Departement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `Id_Entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `stage`
--
ALTER TABLE `stage`
  MODIFY `Id_Stage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `tuteur_entreprise`
--
ALTER TABLE `tuteur_entreprise`
  MODIFY `Id_TuteurEntreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `typeaction`
--
ALTER TABLE `typeaction`
  MODIFY `Id_TypeAction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
