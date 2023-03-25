<?php

namespace ApiBundle\controllers;

include_once 'src/ApiBundle/entities/userEntity.php';

use ApiBundle\Entities\filmEntity as filmEntity;

class filmController {

    public static function addFilm($title, $description, $year, $duration, $director, $trailer, $genre) {
        $success = filmEntity::addFilm($title, $description, $year, $duration, $director, $trailer, $genre);
        echo json_encode($success);
    }

    public static function deleteFilm($id) {
        $success = filmEntity::deleteFilm($id);
        echo json_encode($success);
    }

    public static function modifyFilm($id, $title, $description, $year, $duration, $director, $trailer, $genre) {
        $success = filmEntity::modifyFilm($id, $title, $description, $year, $duration, $director, $trailer, $genre);
        echo json_encode($success);
    }

    public static function getFilm($id)
    {
        $film = filmEntity::getFilm($id);
        echo json_encode($film);
    }
}
