<?php

namespace StandardBundle\Traits;

trait SecurityTrait {

    public static function hash(string $password) : string {
        return crypt($password, PASSWORD_HASH);
    }

    public static function verify(string $postPassword, string $hashedPassword) : bool {
        return crypt($postPassword, PASSWORD_HASH) == $hashedPassword;
        
    }
}