# Application de Gestion des Stages - Architecture MVC

## Structure du Projet

```
NewApp/
├── app/
│   ├── controllers/     # Contrôleurs (logique métier)
│   ├── models/         # Modèles (accès aux données)
│   ├── views/          # Vues (présentation)
│   └── core/           # Classes de base MVC
├── config/             # Configuration (base de données)
├── public/             # Point d'entrée et assets
├── routes/             # Définition des routes
└── storage/            # Fichiers uploadés
```

## Architecture MVC

### Modèles (Models)
- **Action.php** : Gestion des actions et échéances
- **Evenement.php** : Gestion des soutenances et événements
- **Message.php** : Système de messagerie
- **Document.php** : Gestion des documents
- **User.php** : Authentification et utilisateurs

### Vues (Views)
- **common/dashboard.php** : Interface principale multi-rôles
- **auth/connexion.php** : Page de connexion
- **documents/index.php** : Gestion des documents

### Contrôleurs (Controllers)
- **DashboardController.php** : Contrôleur principal
- **AuthController.php** : Authentification
- **DocumentController.php** : Gestion des documents
- **MessageController.php** : Messagerie

### Core (Framework)
- **Controller.php** : Classe de base pour les contrôleurs
- **Model.php** : Classe de base pour les modèles
- **View.php** : Moteur de rendu des vues

## Fonctionnalités

### Calendrier Intelligent
- Calcul automatique des échéances basé sur les types d'actions
- Filtrage par rôle utilisateur
- Interface responsive avec filtres dynamiques

### Système de Rôles
- **Étudiants** : Voient uniquement leurs actions à effectuer
- **Enseignants** : Voient leurs actions + celles de leurs étudiants
- **Admin/Secrétaire** : Accès complet à toutes les actions

### Sécurité
- Protection contre les injections SQL (requêtes préparées)
- Échappement des données (protection XSS)
- Contrôle d'accès basé sur les rôles
- Headers de sécurité configurés

## Installation

1. Configurer la base de données dans `config/database.php`
2. Importer le schéma SQL
3. Configurer le serveur web pour pointer vers `public/`

## Utilisation

L'application utilise un système de routage simple :
- `/dashboard` : Interface principale
- `/auth/connexion` : Connexion
- `/documents` : Gestion des documents
- `/messages` : Messagerie

## Standards de Code

- Respect des principes MVC
- Séparation claire des responsabilités
- Gestion d'erreurs robuste
- Code documenté et commenté 