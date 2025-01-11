

-- Table Utilisateur
CREATE TABLE Utilisateur (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    telephone VARCHAR(15),
    login VARCHAR(50) UNIQUE,
    mot_de_passe VARCHAR(255)
);

-- Table Etudiant
CREATE TABLE Etudiant (
    Id_Etudiant INT PRIMARY KEY,
    FOREIGN KEY (Id_Etudiant) REFERENCES Utilisateur(Id)
);

-- Table Enseignant
CREATE TABLE Enseignant (
    Id_Enseignant INT PRIMARY KEY,
    Bureau VARCHAR(50),
    FOREIGN KEY (Id_Enseignant) REFERENCES Utilisateur(Id)
);

-- Table Administrateur
CREATE TABLE Administrateur (
    Id_Administrateur INT PRIMARY KEY,
    FOREIGN KEY (Id_Administrateur) REFERENCES Utilisateur(Id)
);

-- Table Secrétaire
CREATE TABLE Secretaire (
    Id_Secretaire INT PRIMARY KEY,
    Bureau VARCHAR(50),
    FOREIGN KEY (Id_Secretaire) REFERENCES Utilisateur(Id)
);

-- Table Entreprise
CREATE TABLE Entreprise (
    Id_Entreprise INT AUTO_INCREMENT PRIMARY KEY,
    adresse TEXT,
    code_postal VARCHAR(10),
    ville VARCHAR(100),
    indicationVisite TEXT,
    tel VARCHAR(15)
);

-- Table Tuteur Entreprise
CREATE TABLE Tuteur_Entreprise (
    Id_TuteurEntreprise INT AUTO_INCREMENT PRIMARY KEY,
    Id_Entreprise INT,
    FOREIGN KEY (Id_Entreprise) REFERENCES Entreprise(Id_Entreprise)
);

-- Table Département
CREATE TABLE Departement (
    Id_Departement INT AUTO_INCREMENT PRIMARY KEY,
    Libelle VARCHAR(100)
);

-- Table Année
CREATE TABLE Annee (
    Id_Annee INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(9) NOT NULL, -- Exemple : "2023-2024"
    debut DATE NOT NULL,         -- Date de début de l'année académique
    fin DATE NOT NULL            -- Date de fin de l'année académique
);

-- Table Semestre
CREATE TABLE Semestre (
    numSemestre INT,
    Id_Departement INT,
    Id_Enseignant INT,
    Id_Annee INT,
    PRIMARY KEY (numSemestre, Id_Departement),
    FOREIGN KEY (Id_Departement) REFERENCES Departement(Id_Departement),
    FOREIGN KEY (Id_Enseignant) REFERENCES Enseignant(Id_Enseignant),
    FOREIGN KEY (Id_Annee) REFERENCES Annee(Id_Annee)
);

-- Table Inscription
CREATE TABLE Inscription (
    Id_Annee INT,
    numSemestre INT,
    Id_Departement INT,
    Id_Etudiant INT,
    PRIMARY KEY (Id_Annee, numSemestre, Id_Departement, Id_Etudiant),
    FOREIGN KEY (Id_Etudiant) REFERENCES Etudiant(Id_Etudiant),
    FOREIGN KEY (numSemestre, Id_Departement) REFERENCES Semestre(numSemestre, Id_Departement),
    FOREIGN KEY (Id_Annee) REFERENCES Annee(Id_Annee)
);

-- Table Stage
CREATE TABLE Stage (
    Id_Stage INT AUTO_INCREMENT PRIMARY KEY,
    Id_Annee INT,
    Id_Departement INT,
    numSemestre INT,
    Id_Etudiant INT,
    date_debut DATE,
    date_fin DATE,
    mission TEXT,
    date_soutenance DATE,
    salle_Soutenance VARCHAR(50),
    Id_Enseignant INT,
    Id_TuteurEntreprise INT,
    FOREIGN KEY (Id_Annee) REFERENCES Annee(Id_Annee),
    FOREIGN KEY (Id_Etudiant) REFERENCES Etudiant(Id_Etudiant),
    FOREIGN KEY (Id_Enseignant) REFERENCES Enseignant(Id_Enseignant),
    FOREIGN KEY (Id_TuteurEntreprise) REFERENCES Tuteur_Entreprise(Id_TuteurEntreprise)
);

-- Table TypeAction
CREATE TABLE TypeAction (
    Id_TypeAction INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100),
    Executant VARCHAR(50),
    Destinataire VARCHAR(50),
    delaiEnJours INT,
    ReferenceDelai VARCHAR(50),
    requisDoc BOOLEAN,
    LienModeleDoc TEXT
);

-- Table Action
CREATE TABLE Action (
    Id_Action INT AUTO_INCREMENT PRIMARY KEY,
    Id_Annee INT,
    Id_Departement INT,
    numSemestre INT,
    Id_Etudiant INT,
    Id_Stage INT,
    Id_TypeAction INT,
    date_realisation DATE,
    lienDocument TEXT,
    FOREIGN KEY (Id_Annee) REFERENCES Annee(Id_Annee),
    FOREIGN KEY (Id_Etudiant) REFERENCES Etudiant(Id_Etudiant),
    FOREIGN KEY (Id_Stage) REFERENCES Stage(Id_Stage),
    FOREIGN KEY (Id_TypeAction) REFERENCES TypeAction(Id_TypeAction)
);
