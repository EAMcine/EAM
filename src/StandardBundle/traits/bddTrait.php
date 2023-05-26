<?php

namespace StandardBundle\Traits;

trait BddTrait {

    public static function bddInit() {
        try {
            $sql = "CREATE DATABASE IF NOT EXISTS ". DB_NAME ." DEFAULT CHARACTER SET ". DB_CHARSET ." COLLATE ". DB_CHARSET_COLLATE .";";
            $dbexist = mysqli_query(mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD), $sql);
            
            if ($dbexist == false) {
                throw new \Exception('Erreur lors de la création de la base de données');
            };

            $bdd = new \PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
            
            $bdd->query('CREATE TABLE IF NOT EXISTS `users` (
                `email` varchar(320) NOT NULL,
                `password` varchar(60) NOT NULL,
                `firstname` varchar(255) NOT NULL,
                `lastname` varchar(255) NOT NULL,
                `group` int(11) NOT NULL,
                `active` tinyint(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`email`)
                ) ENGINE='. DB_ENGINE .' DEFAULT CHARSET='. DB_CHARSET .';');

            $bdd->query('CREATE TABLE IF NOT EXISTS `users_tokens` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user` varchar(320) NOT NULL,
                `token` varchar(255) NOT NULL,
                `expiration` datetime NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE='. DB_ENGINE .' DEFAULT CHARSET='. DB_CHARSET .';');

            $bdd->query('ALTER TABLE `users_tokens` ADD FOREIGN KEY (`user`) REFERENCES `users`(`email`);');

            $bdd->query('CREATE TABLE IF NOT EXISTS `groups` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` varchar(255) NOT NULL,
                `subgroup` int(11) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE='. DB_ENGINE .' DEFAULT CHARSET='. DB_CHARSET .';');

            $bdd->query('ALTER TABLE `users` ADD FOREIGN KEY (`group`) REFERENCES `groups`(`id`);');
            $bdd->query('ALTER TABLE `groups` ADD FOREIGN KEY (`subgroup`) REFERENCES `groups`(`id`);');

            $bdd->query('CREATE TABLE IF NOT EXISTS `permissions` (
                `code` varchar(255) NOT NULL,
                `name` varchar(255) NOT NULL,
                `description` varchar(255) NOT NULL,
                PRIMARY KEY (`code`)
                ) ENGINE='. DB_ENGINE .' DEFAULT CHARSET='. DB_CHARSET .';');

            $bdd->query('CREATE TABLE IF NOT EXISTS `groups_permissions` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `group` int(11) NOT NULL,
                `permission` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE='. DB_ENGINE .' DEFAULT CHARSET='. DB_CHARSET .';');

            $bdd->query('ALTER TABLE `groups_permissions` ADD FOREIGN KEY (`group`) REFERENCES `groups`(`id`);');
            $bdd->query('ALTER TABLE `groups_permissions` ADD FOREIGN KEY (`permission`) REFERENCES `permissions`(`code`);');

            $bdd->query('CREATE TRIGGER IF NOT EXISTS `delete_groups_permissions` AFTER DELETE ON `groups` FOR EACH ROW DELETE FROM `groups_permissions` WHERE `group` = OLD.`id`;');

            $bdd->query('CREATE TABLE IF NOT EXISTS `users_permissions` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user` varchar(320) NOT NULL,
                `permission` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE='. DB_ENGINE .' DEFAULT CHARSET='. DB_CHARSET .';');

            $bdd->query('ALTER TABLE `users_permissions` ADD FOREIGN KEY (`user`) REFERENCES `users`(`email`);');
            $bdd->query('ALTER TABLE `users_permissions` ADD FOREIGN KEY (`permission`) REFERENCES `permissions`(`code`);');

            $bdd->query('CREATE TRIGGER IF NOT EXISTS `delete_users_permissions` AFTER DELETE ON `users` FOR EACH ROW DELETE FROM `users_permissions` WHERE `user` = OLD.`email`;');

            $bdd->query('CREATE TABLE IF NOT EXISTS `imageformats` (
                `receivedformat` varchar(255) NOT NULL,
                `localformat` varchar(255) NOT NULL,
                PRIMARY KEY (`receivedformat`) 
                ) ENGINE='. DB_ENGINE .' DEFAULT CHARSET='. DB_CHARSET .';');

            $bdd->query('SET GLOBAL event_scheduler = ON;');

            $bdd->query('CREATE EVENT IF NOT EXISTS `clean_tokens` ON SCHEDULE EVERY 1 HOUR STARTS CURRENT_TIMESTAMP DO DELETE FROM `users_tokens` WHERE `expiration` < NOW();');

        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
