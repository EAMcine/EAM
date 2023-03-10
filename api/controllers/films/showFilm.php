<?php

class showFilm
{
    public function showFilm($id)
    {
        $query = $this->getDb()->prepare('SELECT * FROM films WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
        $film = $query->fetch(PDO::FETCH_ASSOC);
        return $film;
    }
}