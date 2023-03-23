<?php

class showUser extends connectDatabaseTrait
{
    public function showUser($id)
    {
        $query = $this->getDb()->prepare('SELECT * FROM users WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
        $user = $query->fetch(PDO::FETCH_OBJ);
        return $user;
    }
}