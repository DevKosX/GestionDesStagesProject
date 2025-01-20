# Plateforme de Gestion de Stages du BUT

## Description du Projet

Ce projet est une application web développée pour faciliter la gestion des stages des étudiants du Bachelor Universitaire de Technologie (BUT) au sein de l'IUT de Villetaneuse. Elle permet :

*   Aux étudiants de suivre leurs stages, déposer des documents, échanger avec leurs tuteurs, et consulter les échéances importantes.
*   Aux tuteurs (pédagogiques et entreprises) de piloter et suivre les stagiaires dont ils sont responsables.
*  Aux responsables et directeurs d'étude d'avoir une vue d'ensemble sur les stages.

L'application permet de gérer le cycle de vie complet d'un stage :

*   Avant la campagne de stage : chargement des informations dans l'application.
*   Au début du stage : dépôt du compte rendu d'installation.
*   Pendant le stage : contact tuteur-entreprise;
*   À la fin du stage : planification de la soutenance, dépôt du rapport de stage.





*   **Arborescence du projet :**
* Il est important de noter que pendant la réalisation de cette SAÉ, l'arborescence du projet a été refaite à partir du dossier `AppStage`, cela afin de mieux correspondre à la structure de la base de données. 
*  Une arborescence précédent se situait dans un dossier nommé `Ancien_projet`, qui n'a pas été gardé.
*  Cette documentation est donc faite en considérant l'arborescence actuelle et non celle qui était au départ.


## Technologies Utilisées

*   **Langages :** PHP, JavaScript, HTML, CSS
*   **Base de données :** phpMyAdmin

*   ## Installation et Configuration

1.  **Cloner le dépôt (si le projet est sur GitHub ou autre) :**
    ```bash
    git clone https://github.com/DevKosX/GestionDesStagesProject.git

    ```

2.  **Créer la base de données :**
    *   Utilise le script SQL fourni dans le fichier `stage.sql` pour créer la base de données sur ton serveur phpMyAdmin.
  
      
## Comment Exécuter le Projet

1.  **Ouvre** ton navigateur web.
2.  **Accède** à l'URL du projet, cela devrait lancer le fichier `index.php` qui sert de point d'entré.
3.   Si tu n'es pas connecté, tu seras redirigé vers la page de connexion.

## Accès aux différentes pages :

1.  **Page d'accueil (index.php):**
   * Affichage de la page d'accueil pour les non connectés, sinon redirige vers la page d'acceuil connecté.

2.  **Page de connexion (connexion.php):**
    *   Formulaire pour se connecter à la plateforme (récupération du role en cas de succès).

3.  **Page d'accueil connectée (accueilConnect.php) :**
    *   Page d'accueil principale, pour tout les utilisateur qui sont connectés.

4.  **Tableau de bord (tableaudebord.php):**
    *   Affiche le tableau de bord spécifique à chaque rôle (`tdb_etudiant.php`, `tdb_enseignant.php`, etc.) en fonction du rôle de l'utilisateur connecté.

5.  **Gestion des stages (gestiondestages.php) :**
    *   Affiche la page de gestion des stages spécifique à chaque rôle (`gds_etudiant.php`, `gds_enseignant.php`, etc.).

6.  **Page de profil (profil.php):**
    *  Affiche les information du profile de l'utilisateur.

## Informations de sécurité

*   **Mots de passe :** Les mots de passe sont stockés sous forme de hash avec la fonction `password_hash()` de PHP.

*   ## Membres de l'équipe

*   Mohamed Kosbar, David Diema, Mohammed Ben Jillani, Mohamed Essaoudi, Yacine Rachidi, Moilim Abdallah

## Mots de passe
* Nous avons 3 vues, 
* Le compte de l'étudiant est jean.dupont@example.com et le mot de passe est 123
* Le compte de l'enseignant est sophie.martin@example.com et le mot de passe est 123
* Le compte de l'adminstrateur est fatou.poirier@example.com et le mot de passe est 123


