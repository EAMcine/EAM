<?php

namespace ApiBundle\Entities;

use \Framework\Core\Database as Database;

class userEntity {

    public static function addUser($name, $email, $password)
    {
        $query = Database::getDb()->prepare('INSERT INTO users (name, email, password, group ) VALUES (:name, :email, :password)');
        $query->execute([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }

    public static function deleteUser($id)
    {
        $query = Database::getDb()->prepare('DELETE FROM users WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
    }

    public static function modifyUser($id, $name, $email, $password)
    {
        $query = Database::getDb()->prepare('UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id');
        $query->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }

    public static function getUser($id)
    {
        $query = Database::getDb()->prepare('SELECT * FROM users WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
        $user = $query->fetch(\PDO::FETCH_OBJ);
        return $user;
    }
}
