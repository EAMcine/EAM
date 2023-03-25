<?php

namespace ApiBundle\Entities;

include_once 'src/ApiBundle/traits/databaseTrait.php';

use ApiBundle\Traits\databaseTrait as databaseTrait;

class filmEntity extends databaseTrait {

    public static function addFilm($title, $description, $year, $duration, $director, $trailer, $genre) {
        $query = databaseTrait::getDb()->prepare('INSERT INTO films (title, description, year, duration, director, trailer, genre) VALUES (:title, :description, :year, :duration, :director, :trailer, :genre)');
        return $query->execute([
            'title' => $title,
            'description' => $description,
            'year' => $year,
            'duration' => $duration,
            'director' => $director,
            'trailer' => $trailer,
            'genre' => $genre
        ]);
    }

    public static function deleteFilm($id) {
        $query = databaseTrait::getDb()->prepare('DELETE FROM films WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
    }

    public static function modifyFilm($id, $title, $description, $year, $duration, $director, $trailer, $genre) {
        $query = databaseTrait::getDb()->prepare('UPDATE films SET title = :title, description = :description, year = :year, duration = :duration, director = :director, trailer = :trailer, genre = :genre WHERE id = :id');
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

    public static function getFilm($id)
    {
        $query = databaseTrait::getDb()->prepare('SELECT * FROM films WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
        $film = $query->fetch(\PDO::FETCH_ASSOC);
        return $film;
    }
}