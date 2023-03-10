<?php

class addUser extends ConnectDatabase
{
    public function addUser($name, $email, $password)
    {
        $query = $this->getDb()->prepare('INSERT INTO users (name, email, password, group ) VALUES (:name, :email, :password)');
        $query->execute([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }
}