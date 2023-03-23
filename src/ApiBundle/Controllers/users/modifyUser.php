<?php

class modifyUser extends connectDatabaseTrait
{
    public function modifyUser($id, $name, $email, $password)
    {
        $query = $this->getDb()->prepare('UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id');
        $query->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }
}