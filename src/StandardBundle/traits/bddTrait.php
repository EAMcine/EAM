<?php

namespace StandardBundle\Traits;

use Framework\Core\ClassLoader as ClassLoader;
use StandardBundle\Models\Group as Group;
use StandardBundle\Models\GroupPermission;
use StandardBundle\Models\Permission;
use StandardBundle\Models\User;
use StandardBundle\Models\ImageFormat;

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
            $loader->loadFolder(__DIR__ . '/../models');

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
            $bdd->query('CREATE TRIGGER `delete_groups_permissions` AFTER DELETE ON `groups` FOR EACH ROW DELETE FROM `groups_permissions` WHERE `group` = OLD.`code`;');
            $bdd->query('CREATE TRIGGER `delete_users_permissions` AFTER DELETE ON `users` FOR EACH ROW DELETE FROM `users_permissions` WHERE `user` = OLD.`email`;');

            // Création des évènements récurrents
            $bdd->query('SET GLOBAL event_scheduler = ON;');
            $bdd->query('CREATE EVENT IF NOT EXISTS `clean_tokens` ON SCHEDULE EVERY 1 HOUR STARTS CURRENT_TIMESTAMP DO DELETE FROM `users_tokens` WHERE `expiration` < NOW();');

            // Création des groupes
            if (Group::selectOneByPk('user') == false)
                $user = Group::create('user', 'Utilisateur/Utilisatrice', 'Groupe des utilisateurs non abonnés au service premium.');
            else
                $user = Group::selectOneByPk('user');

            if (Group::selectOneByPk('premium') == false)
                $premium = Group::create('premium', 'Abonné/Abonnée', 'Groupe des utilisateurs abonnés au service premium.', $user);
            else
                $premium = Group::selectOneByPk('premium');

            if (Group::selectOneByPk('contributor') == false)
                $contributor = Group::create('contributor', 'Contributeur/Contributeuse', 'Groupe des contributeurs, donne accès aux fonctionnalités de contribution.', $premium);
            else
                $contributor = Group::selectOneByPk('contributor');

            if (Group::selectOneByPk('moderator') == false)
                $moderator = Group::create('moderator', 'Modérateur/Modératrice', 'Groupe des modérateurs, donne accès aux fonctionnalités de modération.', $contributor);
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
                Permission::create('debug', 'Permission de debug', 'Permission de debug, donne accès aux fonctionnalités de debug.');
            else
                Permission::selectOneByPk('debug');

            if (Permission::selectOneByPk('debug.*') == false)
                Permission::create('debug.*', 'Permission de debug globale', 'Permission de debug globale, donne accès à toutes les fonctionnalités de debug.');
            else
                Permission::selectOneByPk('debug.*');

            if (Permission::selectOneByPk('debug.phpinfo') == false)
                Permission::create('debug.phpinfo', 'Permission de phpinfo', 'Permission de phpinfo, donne accès à la fonctionnalité phpinfo().');
            else
                Permission::selectOneByPk('debug.phpinfo');

            if (Permission::selectOneByPk('debug.routes') == false)
                Permission::create('debug.routes', 'Permission de routes', 'Permission de routes, donne accès à la fonctionnalité de listing des routes.');
            else
                Permission::selectOneByPk('debug.routes');

            if (Permission::selectOneByPk('debug.logs') == false)
                Permission::create('debug.logs', 'Permission de logs', 'Permission de logs, donne accès à la fonctionnalité de listing des logs.');
            else
                Permission::selectOneByPk('debug.logs');

            if (Permission::selectOneByPk('admin') == false)
                $adminPermission = Permission::create('admin', 'Permission d\'administration', 'Permission d\'administration, donne accès aux fonctionnalités d\'administration.');
            else
                $adminPermission = Permission::selectOneByPk('admin');

            if (Permission::selectOneByPk('admin.*') == false)
                $adminAllPermission = Permission::create('admin.*', 'Permission d\'administration globale', 'Permission d\'administration globale, donne accès à toutes les fonctionnalités d\'administration.');
            else
                $adminAllPermission = Permission::selectOneByPk('admin.*');

            if (Permission::selectOneByPk('admin.users') == false)
                Permission::create('admin.users', 'Permission d\'utilisateurs', 'Permission d\'utilisateurs, donne accès aux fonctionnalités d\'administration des utilisateurs.');
            else
                Permission::selectOneByPk('admin.users');

            if (Permission::selectOneByPk('admin.users.*') == false)
                Permission::create('admin.users.*', 'Permission d\'utilisateurs globale', 'Permission d\'utilisateurs globale, donne accès à toutes les fonctionnalités d\'administration des utilisateurs.');
            else
                Permission::selectOneByPk('admin.users.*');

            if (Permission::selectOneByPk('admin.users.add') == false)
                Permission::create('admin.users.add', 'Permission d\'ajout d\'utilisateurs', 'Permission d\'ajout d\'utilisateurs, donne accès à la fonctionnalité d\'ajout d\'utilisateurs.');
            else
                Permission::selectOneByPk('admin.users.add');

            if (Permission::selectOneByPk('admin.users.edit') == false)
                Permission::create('admin.users.edit', 'Permission d\'édition d\'utilisateurs', 'Permission d\'édition d\'utilisateurs, donne accès à la fonctionnalité d\'édition d\'utilisateurs.');
            else
                Permission::selectOneByPk('admin.users.edit');

            if (Permission::selectOneByPk('admin.users.delete') == false)
                Permission::create('admin.users.delete', 'Permission de suppression d\'utilisateurs', 'Permission de suppression d\'utilisateurs, donne accès à la fonctionnalité de suppression d\'utilisateurs.');
            else
                Permission::selectOneByPk('admin.users.delete');

            if (Permission::selectOneByPk('admin.groups') == false)
                Permission::create('admin.groups', 'Permission de groupes', 'Permission de groupes, donne accès aux fonctionnalités d\'administration des groupes.');
            else
                Permission::selectOneByPk('admin.groups');

            if (Permission::selectOneByPk('admin.groups.*') == false)
                Permission::create('admin.groups.*', 'Permission de groupes globale', 'Permission de groupes globale, donne accès à toutes les fonctionnalités d\'administration des groupes.');
            else
                Permission::selectOneByPk('admin.groups.*');

            if (Permission::selectOneByPk('admin.groups.add') == false)
                Permission::create('admin.groups.add', 'Permission d\'ajout de groupes', 'Permission d\'ajout de groupes, donne accès à la fonctionnalité d\'ajout de groupes.');
            else
                Permission::selectOneByPk('admin.groups.add');

            if (Permission::selectOneByPk('admin.groups.edit') == false)
                Permission::create('admin.groups.edit', 'Permission d\'édition de groupes', 'Permission d\'édition de groupes, donne accès à la fonctionnalité d\'édition de groupes.');
            else
                Permission::selectOneByPk('admin.groups.edit');

            if (Permission::selectOneByPk('admin.groups.delete') == false)
                Permission::create('admin.groups.delete', 'Permission de suppression de groupes', 'Permission de suppression de groupes, donne accès à la fonctionnalité de suppression de groupes.');
            else
                Permission::selectOneByPk('admin.groups.delete');

            if (Permission::selectOneByPk('admin.permissions') == false)
                Permission::create('admin.permissions', 'Permission de permissions', 'Permission de permissions, donne accès aux fonctionnalités d\'administration des permissions.');
            else
                Permission::selectOneByPk('admin.permissions');

            if (Permission::selectOneByPk('admin.permissions.*') == false)
                Permission::create('admin.permissions.*', 'Permission de permissions globale', 'Permission de permissions globale, donne accès à toutes les fonctionnalités d\'administration des permissions.');
            else
                Permission::selectOneByPk('admin.permissions.*');

            if (Permission::selectOneByPk('admin.permissions.add') == false)
                Permission::create('admin.permissions.add', 'Permission d\'ajout de permissions', 'Permission d\'ajout de permissions, donne accès à la fonctionnalité d\'ajout de permissions.');
            else
                Permission::selectOneByPk('admin.permissions.add');

            if (Permission::selectOneByPk('admin.permissions.edit') == false)
                Permission::create('admin.permissions.edit', 'Permission d\'édition de permissions', 'Permission d\'édition de permissions, donne accès à la fonctionnalité d\'édition de permissions.');
            else
                Permission::selectOneByPk('admin.permissions.edit');

            if (Permission::selectOneByPk('admin.permissions.delete') == false)
                Permission::create('admin.permissions.delete', 'Permission de suppression de permissions', 'Permission de suppression de permissions, donne accès à la fonctionnalité de suppression de permissions.');
            else
                Permission::selectOneByPk('admin.permissions.delete');

            if (Permission::selectOneByPk('admin.contributions') == false)
                $contributionsPermission = Permission::create('admin.contributions', 'Permission de contributions', 'Permission de contributions, donne accès aux fonctionnalités d\'administration des contributions.');
            else
                $contributionsPermission = Permission::selectOneByPk('admin.contributions');

            if (Permission::selectOneByPk('admin.contributions.*') == false)
                $contributionsAllPermission = Permission::create('admin.contributions.*', 'Permission de contributions globale', 'Permission de contributions globale, donne accès à toutes les fonctionnalités d\'administration des contributions.');
            else
                $contributionsAllPermission = Permission::selectOneByPk('admin.contributions.*');

            if (Permission::selectOneByPk('admin.contributions.add') == false)
                Permission::create('admin.contributions.add', 'Permission d\'ajout de contributions', 'Permission d\'ajout de contributions, donne accès à la fonctionnalité d\'ajout de contributions.');
            else
                Permission::selectOneByPk('admin.contributions.add');

            if (Permission::selectOneByPk('admin.contributions.edit') == false)
                Permission::create('admin.contributions.edit', 'Permission d\'édition de contributions', 'Permission d\'édition de contributions, donne accès à la fonctionnalité d\'édition de contributions.');
            else
                Permission::selectOneByPk('admin.contributions.edit');

            if (Permission::selectOneByPk('admin.contributions.accept') == false)
                Permission::create('admin.contributions.accept', 'Permission d\'acceptation de contributions', 'Permission d\'acceptation de contributions, donne accès à la fonctionnalité d\'acceptation de contributions.');
            else
                Permission::selectOneByPk('admin.contributions.accept');

            if (Permission::selectOneByPk('admin.contributions.refuse') == false)
                Permission::create('admin.contributions.refuse', 'Permission de refus de contributions', 'Permission de refus de contributions, donne accès à la fonctionnalité de refus de contributions.');
            else
                Permission::selectOneByPk('admin.contributions.refuse');

            if (Permission::selectOneByPk('admin.contributions.delete') == false)
                Permission::create('admin.contributions.delete', 'Permission de suppression de contributions', 'Permission de suppression de contributions, donne accès à la fonctionnalité de suppression de contributions.');
            else
                Permission::selectOneByPk('admin.contributions.delete');

            if (Permission::selectOneByPk('contributions') == false)
                $contributorPermission = Permission::create('contributions', 'Permission des contributeurs', 'Permission des contributeurs, donne accès aux fonctionnalités de contributions.');
            else
                $contributorPermission = Permission::selectOneByPk('contributions'); 

            if (Permission::selectOneByPk('contributions.*') == false)
                $contributorAllPermission = Permission::create('contributions.*', 'Permission des contributeurs globale', 'Permission des contributeurs globale, donne accès à toutes les fonctionnalités de contributions.');
            else
                $contributorAllPermission = Permission::selectOneByPk('contributions.*');

            if (Permission::selectOneByPk('contributions.add') == false)
                Permission::create('contributions.add', 'Permission d\'ajout de contributions', 'Permission d\'ajout de contributions, donne accès à la fonctionnalité d\'ajout de contributions.');
            else
                Permission::selectOneByPk('contributions.add');

            if (Permission::selectOneByPk('contributions.edit') == false)
                Permission::create('contributions.edit', 'Permission d\'édition de contributions', 'Permission d\'édition de contributions, donne accès à la fonctionnalité d\'édition de contributions.');
            else
                Permission::selectOneByPk('contributions.edit');

            if (Permission::selectOneByPk('contributions.delete') == false)
                Permission::create('contributions.delete', 'Permission de suppression de contributions', 'Permission de suppression de contributions, donne accès à la fonctionnalité de suppression de contributions.');
            else
                Permission::selectOneByPk('contributions.delete');

            // Attribution des permissions aux groupes

            if ($developer->can($globalPermission) == false)
                GroupPermission::create($developer, $globalPermission);

            if ($admin->can($adminAllPermission) == false)
                GroupPermission::create($admin, $adminAllPermission);

            if ($admin->can($adminPermission) == false)
                GroupPermission::create($admin, $adminPermission);

            if ($moderator->can($contributionsAllPermission) == false)
                GroupPermission::create($moderator, $contributionsAllPermission);

            if ($moderator->can($contributionsPermission) == false)
                GroupPermission::create($moderator, $contributionsPermission);

            if ($contributor->can($contributorAllPermission) == false)
                GroupPermission::create($contributor, $contributorAllPermission);

            if ($contributor->can($contributorPermission) == false)
                GroupPermission::create($contributor, $contributorPermission);

            // Création des utilisateurs de test

            $users = [
                array('admin@eam.fr', 'admin@EAM.fr12345', 'Admin', 'EAM+', '1990/01/01', 'other', $admin, 1),
                array('developper@eam.fr', 'developper@EAM.fr12345', 'Developper', 'EAM+', '1990/01/01', 'other', $developer, 1),
                array('user@eam.fr', 'user@EAM.fr12345', 'User', 'EAM+', '1990/01/01', 'other', $user, 1),
                array('moderator@eam.fr', 'moderator@EAM.fr12345', 'Moderator', 'EAM+', '1990/01/01', 'other', $moderator, 1),
                array('premium@eam.fr', 'premium@EAM.fr12345', 'Premium', 'EAM+', '1990/01/01', 'other', 'premium', 1),
                array('contributor@eam.fr', 'contributor@EAM.fr12345', 'Contributor', 'EAM+', '1990/01/01', 'other', $contributor, 1),
            ];

            foreach ($users as $row) {
                User::create($row[0], SecurityTrait::hash($row[1]), $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
            }

            // Création des ImageFormats

            $imageFormats = [
                array('jpg', 'jpeg'),
                array('jpeg', 'jpeg'),
                array('png', 'png'),
            ];

            foreach ($imageFormats as $row) {
                ImageFormat::create($row[0], $row[1]);
            }

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
