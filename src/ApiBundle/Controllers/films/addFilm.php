<?php

class addFilm extends connectDatabaseTrait
{
    public function addFilm($title, $description, $year, $duration, $director, $trailer, $genre)
    {
        $query = $this->getDb()->prepare('INSERT INTO films (title, description, year, duration, director, trailer, genre) VALUES (:title, :description, :year, :duration, :director, :trailer, :genre)');
        $query->execute([
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