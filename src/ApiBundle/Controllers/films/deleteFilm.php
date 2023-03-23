<?php

class deleteFilm extends connectDatabaseTrait
{
    public function deleteFilm($id)
    {
        $query = $this->getDb()->prepare('DELETE FROM films WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
    }
}