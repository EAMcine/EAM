<?php

namespace ApiBundle\controllers;

include_once 'src/ApiBundle/entities/userEntity.php';

use ApiBundle\Entities\userEntity as userEntity;

class userController {

    public static function addUser() {
        if (isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['name'], $_POST['surname'], $_POST['birthdate'], $_POST['gender'], $_POST['country'], $_POST['city'], $_POST['address'], $_POST['postalCode'], $_POST['phone'], $_POST['role'])) {
            $success = userEntity::addUser($_POST['username'], $_POST['password'], $_POST['email'], $_POST['name'], $_POST['surname'], $_POST['birthdate'], $_POST['gender'], $_POST['country'], $_POST['city'], $_POST['address'], $_POST['postalCode'], $_POST['phone'], $_POST['role']);
            header('content-type: application/json; charset=utf-8');
            echo json_encode($success);
        }
        header('content-type: application/json; charset=utf-8');
        echo json_encode(false);
    }

    public static function deleteUser($id) {
        $success = userEntity::deleteUser($id);
        echo json_encode($success);
    }

    public static function modifyUser($id, $username, $password, $email, $name, $surname, $birthdate, $gender, $country, $city, $address, $postalCode, $phone, $role) {
        $success = userEntity::modifyUser($id, $username, $password, $email, $name, $surname, $birthdate, $gender, $country, $city, $address, $postalCode, $phone, $role);
        echo json_encode($success);
    }

    public static function getUser($id) {
        $user = userEntity::getUser($id);
        echo json_encode($user);
    }

}
