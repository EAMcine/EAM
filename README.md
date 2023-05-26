# EAM+

Projet web de vidéothèque réalisé pour une formation professionnelle au CESI Lyon.

## Installation

### Prérequis

- PHP 8.x (php.ini par défaut + extension tidy)
- MySQL Server 8.x
- Apache 2.4.x

### Procédure d'initialisation

1. Cloner le projet dans le dossier de votre choix
2. Configurer le serveur web pour qu'il pointe vers la racine du projet
3. Créer une base de données MySQL
4. Modifier le fichier `app/.env` pour y renseigner les informations de connexion à la base de données et le nom de domaine du site/virtual host
5. Exécuter la commande `php app/command database:install` pour installer la base de données
6. Exécuter la commande `php app/command database:dummydata` pour générer des données de test
7. Exécuter la commande `php app/command database:admin -u <username> -p <password> -e <email>` pour créer un compte administrateur
8. Se connecter avec le compte administrateur créé à l'étape précédente
9. Profiter du site !
