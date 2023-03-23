<?php

class modifyFilm extends connectDatabaseTrait
{
    public function modifyFilm($id, $title, $description, $year, $duration, $director, $trailer, $genre)
    {
        $query = $this->getDb()->prepare('UPDATE films SET title = :title, description = :description, year = :year, duration = :duration, director = :director, trailer = :trailer, genre = :genre WHERE id = :id');
        $query->execute([
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'year' => $year,
            'duration' => $duration,
            'director' => $director,
            'trailer' => $trailer,
            'genre' => $genre
        ]);
    }
}