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

            if (Permission::selectOneByPk('*') == false)
                $globalPermission = Permission::create('*', 'Permission globale', 'Permission globale, donne accès à toutes les fonctionnalités.');
            else
                $globalPermission = Permission::selectOneByPk('*');

            if (Permission::selectOneByPk('debug') == false)
                $debugPermission = Permission::create('debug', 'Permission de debug', 'Permission de debug, donne accès aux fonctionnalités de debug.');
            else
                $debugPermission = Permission::selectOneByPk('debug');

            if (Permission::selectOneByPk('debug.*') == false)
                $debugAllPermission = Permission::create('debug.*', 'Permission de debug globale', 'Permission de debug globale, donne accès à toutes les fonctionnalités de debug.');
            else
                $debugAllPermission = Permission::selectOneByPk('debug.*');

            if (Permission::selectOneByPk('debug.phpinfo') == false)
                $phpinfoPermission = Permission::create('debug.phpinfo', 'Permission de phpinfo', 'Permission de phpinfo, donne accès à la fonctionnalité phpinfo().');
            else
                $phpinfoPermission = Permission::selectOneByPk('debug.phpinfo');

            if (Permission::selectOneByPk('debug.routes') == false)
                $routesPermission = Permission::create('debug.routes', 'Permission de routes', 'Permission de routes, donne accès à la fonctionnalité de listing des routes.');
            else
                $routesPermission = Permission::selectOneByPk('debug.routes');

            if (Permission::selectOneByPk('debug.logs') == false)
                $logsPermission = Permission::create('debug.logs', 'Permission de logs', 'Permission de logs, donne accès à la fonctionnalité de listing des logs.');
            else
                $logsPermission = Permission::selectOneByPk('debug.logs');

            if (Permission::selectOneByPk('admin') == false)
                $adminPermission = Permission::create('admin', 'Permission d\'administration', 'Permission d\'administration, donne accès aux fonctionnalités d\'administration.');
            else
                $adminPermission = Permission::selectOneByPk('admin');

            if (Permission::selectOneByPk('admin.*') == false)
                $adminAllPermission = Permission::create('admin.*', 'Permission d\'administration globale', 'Permission d\'administration globale, donne accès à toutes les fonctionnalités d\'administration.');
            else
                $adminAllPermission = Permission::selectOneByPk('admin.*');

            if (Permission::selectOneByPk('admin.users') == false)
                $usersPermission = Permission::create('admin.users', 'Permission d\'utilisateurs', 'Permission d\'utilisateurs, donne accès aux fonctionnalités d\'administration des utilisateurs.');
            else
                $usersPermission = Permission::selectOneByPk('admin.users');

            if (Permission::selectOneByPk('admin.users.add') == false)
                $usersAddPermission = Permission::create('admin.users.add', 'Permission d\'ajout d\'utilisateurs', 'Permission d\'ajout d\'utilisateurs, donne accès à la fonctionnalité d\'ajout d\'utilisateurs.');
            else
                $usersAddPermission = Permission::selectOneByPk('admin.users.add');

            if (Permission::selectOneByPk('admin.users.edit') == false)
                $usersEditPermission = Permission::create('admin.users.edit', 'Permission d\'édition d\'utilisateurs', 'Permission d\'édition d\'utilisateurs, donne accès à la fonctionnalité d\'édition d\'utilisateurs.');
            else
                $usersEditPermission = Permission::selectOneByPk('admin.users.edit');

            if (Permission::selectOneByPk('admin.users.delete') == false)
                $usersDeletePermission = Permission::create('admin.users.delete', 'Permission de suppression d\'utilisateurs', 'Permission de suppression d\'utilisateurs, donne accès à la fonctionnalité de suppression d\'utilisateurs.');
            else
                $usersDeletePermission = Permission::selectOneByPk('admin.users.delete');

            if (Permission::selectOneByPk('admin.groups') == false)
                $groupsPermission = Permission::create('admin.groups', 'Permission de groupes', 'Permission de groupes, donne accès aux fonctionnalités d\'administration des groupes.');
            else
                $groupsPermission = Permission::selectOneByPk('admin.groups');

            if (Permission::selectOneByPk('admin.groups.add') == false)
                $groupsAddPermission = Permission::create('admin.groups.add', 'Permission d\'ajout de groupes', 'Permission d\'ajout de groupes, donne accès à la fonctionnalité d\'ajout de groupes.');
            else
                $groupsAddPermission = Permission::selectOneByPk('admin.groups.add');

            if (Permission::selectOneByPk('admin.groups.edit') == false)
                $groupsEditPermission = Permission::create('admin.groups.edit', 'Permission d\'édition de groupes', 'Permission d\'édition de groupes, donne accès à la fonctionnalité d\'édition de groupes.');
            else
                $groupsEditPermission = Permission::selectOneByPk('admin.groups.edit');

            if (Permission::selectOneByPk('admin.groups.delete') == false)
                $groupsDeletePermission = Permission::create('admin.groups.delete', 'Permission de suppression de groupes', 'Permission de suppression de groupes, donne accès à la fonctionnalité de suppression de groupes.');
            else
                $groupsDeletePermission = Permission::selectOneByPk('admin.groups.delete');

            if (Permission::selectOneByPk('admin.permissions') == false)
                $permissionsPermission = Permission::create('admin.permissions', 'Permission de permissions', 'Permission de permissions, donne accès aux fonctionnalités d\'administration des permissions.');
            else
                $permissionsPermission = Permission::selectOneByPk('admin.permissions');

            if (Permission::selectOneByPk('admin.permissions.add') == false)
                $permissionsAddPermission = Permission::create('admin.permissions.add', 'Permission d\'ajout de permissions', 'Permission d\'ajout de permissions, donne accès à la fonctionnalité d\'ajout de permissions.');
            else
                $permissionsAddPermission = Permission::selectOneByPk('admin.permissions.add');

            if (Permission::selectOneByPk('admin.permissions.edit') == false)
                $permissionsEditPermission = Permission::create('admin.permissions.edit', 'Permission d\'édition de permissions', 'Permission d\'édition de permissions, donne accès à la fonctionnalité d\'édition de permissions.');
            else
                $permissionsEditPermission = Permission::selectOneByPk('admin.permissions.edit');

            if (Permission::selectOneByPk('admin.permissions.delete') == false)
                $permissionsDeletePermission = Permission::create('admin.permissions.delete', 'Permission de suppression de permissions', 'Permission de suppression de permissions, donne accès à la fonctionnalité de suppression de permissions.');
            else
                $permissionsDeletePermission = Permission::selectOneByPk('admin.permissions.delete');

            // Attribution des permissions

            if ($developer->can($globalPermission) == false)
                GroupPermission::create($developer, $globalPermission);

            if ($admin->can($adminAllPermission) == false)
                GroupPermission::create($admin, $adminAllPermission);

            if ($admin->can($adminPermission) == false)
                GroupPermission::create($admin, $adminPermission);

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
