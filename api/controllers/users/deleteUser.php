<?php

class deleteUser extends ConnectDatabase
{
    public function deleteUser($id)
    {
        $query = $this->getDb()->prepare('DELETE FROM users WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
    }
}