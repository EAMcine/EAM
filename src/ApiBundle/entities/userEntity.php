<?php

namespace ApiBundle\Entities;

include_once 'src/ApiBundle/traits/databaseTrait.php';

use ApiBundle\Traits\databaseTrait as databaseTrait;

class userEntity extends databaseTrait {

    public static function addUser($name, $email, $password)
    {
        $query = databaseTrait::getDb()->prepare('INSERT INTO users (name, email, password, group ) VALUES (:name, :email, :password)');
        $query->execute([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }

    public function deleteUser($id)
    {
        $query = databaseTrait::getDb()->prepare('DELETE FROM users WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
    }

    public static function modifyUser($id, $name, $email, $password)
    {
        $query = databaseTrait::getDb()->prepare('UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id');
        $query->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }

    public static function getUser($id)
    {
        $query = databaseTrait::getDb()->prepare('SELECT * FROM users WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
        $user = $query->fetch(\PDO::FETCH_OBJ);
        return $user;
    }
}
