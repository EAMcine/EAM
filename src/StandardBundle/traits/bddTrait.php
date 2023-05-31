<?php

namespace StandardBundle\Traits;

use Framework\Core\ClassLoader as ClassLoader;
use StandardBundle\Models\Group as Group;
use StandardBundle\Models\GroupPermission;
use StandardBundle\Models\Permission;

trait BddTrait {

    public static function bddInit() {
        try {
            $sql = "CREATE DATABASE IF NOT EXISTS ". DB_NAME ." DEFAULT CHARACTER SET ". DB_CHARSET ." COLLATE ". DB_CHARSET_COLLATE .";";
            $dbexist = mysqli_query(mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD), $sql);
            
            if ($dbexist == false) {
                throw new \Exception('Erreur lors de la création de la base de données');
            };

            $bdd = new \PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
            
            $loader = new ClassLoader();
            $loader->loadFolder('src/StandardBundle/models/');

            foreach (self::getAllModels() as $model) {
                $bdd->query($model::initTable().
                    ' ENGINE='. DB_ENGINE .
                    ' DEFAULT CHARSET='. DB_CHARSET .';');
            }

            // Définition des clés étrangères
            $bdd->query('ALTER TABLE `users_tokens` ADD FOREIGN KEY (`user`) REFERENCES `users`(`email`);');
            $bdd->query('ALTER TABLE `users` ADD FOREIGN KEY (`group`) REFERENCES `groups`(`code`);');
            $bdd->query('ALTER TABLE `groups` ADD FOREIGN KEY (`subgroup`) REFERENCES `groups`(`code`);');
            $bdd->query('ALTER TABLE `groups_permissions` ADD FOREIGN KEY (`group`) REFERENCES `groups`(`code`);');
            $bdd->query('ALTER TABLE `groups_permissions` ADD FOREIGN KEY (`permission`) REFERENCES `permissions`(`code`);');
            $bdd->query('ALTER TABLE `users_permissions` ADD FOREIGN KEY (`user`) REFERENCES `users`(`email`);');
            $bdd->query('ALTER TABLE `users_permissions` ADD FOREIGN KEY (`permission`) REFERENCES `permissions`(`code`);');

            // Création des triggers
            $bdd->query('CREATE TRIGGER IF NOT EXISTS `delete_groups_permissions` AFTER DELETE ON `groups` FOR EACH ROW DELETE FROM `groups_permissions` WHERE `group` = OLD.`code`;');
            $bdd->query('CREATE TRIGGER IF NOT EXISTS `delete_users_permissions` AFTER DELETE ON `users` FOR EACH ROW DELETE FROM `users_permissions` WHERE `user` = OLD.`email`;');

            // Création des évènements récurrents
            $bdd->query('SET GLOBAL event_scheduler = ON;');
            $bdd->query('CREATE EVENT IF NOT EXISTS `clean_tokens` ON SCHEDULE EVERY 1 HOUR STARTS CURRENT_TIMESTAMP DO DELETE FROM `users_tokens` WHERE `expiration` < NOW();');

            // Création des groupes
            if (Group::selectOneByPk('user') == false)
                $user = Group::create('user', 'Utilisateur/Utilisatrice', 'Groupe des utilisateurs non abonnés au service premium.');
            else
                $user = Group::selectOneByPk('user');

            if (Group::selectOneByPk('premium') == false)
                Group::create('premium', 'Abonné/Abonnée', 'Groupe des utilisateurs abonnés au service premium.', $user);

            if (Group::selectOneByPk('moderator') == false)
                $moderator = Group::create('moderator', 'Modérateur/Modératrice', 'Groupe des modérateurs, donne accès aux fonctionnalités de modération.', $user);
            else
                $moderator = Group::selectOneByPk('moderator');

            if (Group::selectOneByPk('admin') == false)
                $admin = Group::create('admin', 'Administrateur/Administratrice', 'Groupe des administrateurs, donne accès aux fonctionnalités d\'administration.', $moderator);
            else
                $admin = Group::selectOneByPk('admin');

            if (Group::selectOneByPk('developer') == false)
                $developer = Group::create('developer', 'Développeur/Développeuse', 'Groupe des développeurs, donne accès aux fonctionnalités de développement.', $admin);
            else
                $developer = Group::selectOneByPk('developer');


            // Création des permissions

            if (Permission::selectOneByPk('DEBUG') == false)
                $debug = Permission::create('DEBUG', 'Permission de debug', 'Permission de debug, donne accès aux fonctionnalités de debug.');
            else
                $debug = Permission::selectOneByPk('DEBUG');

            // Attribution des permissions

            if ($developer->can($debug) == false)
                GroupPermission::create($developer, $debug);
            

        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getAllModels() {
        $models = [];
        $dir = new \DirectoryIterator(__DIR__ . '/../models');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                array_push($models, 'StandardBundle\\Models\\' . ucfirst(substr($fileinfo->getFilename(), 0, -4)));
            }
        }
        return $models;
    }
}
