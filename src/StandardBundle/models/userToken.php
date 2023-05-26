<?php

namespace StandardBundle\Models;

use Framework\Components\Model as Model;
use Framework\Core\Database as Database;

final class UserToken extends Model {
    protected static string|null $_pkName = 'user';
    protected static string $_table = 'users_tokens';

    /**
     * @param string $user
     */
    public static function create(mixed ...$args) : UserToken|false {
        if(count($args) != 1)
            return false;

        $user = $args[0];

        $user = $user->get('email');
        $token = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', time()+172800);

        $data = array(
            'user' => $user,
            'token' => $token,
            'expiration' => $expiration
        );

        if (self::selectOneByPk($user))
            return false;

        $userToken = new UserToken($data);
        $userToken->insert();

        return $userToken;
    }

    public static function select(string $where = null, array $params = null) : array|false {
        $result = Database::select(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        $usersTokens = array();
        foreach ($result as $userToken) {
            $usersTokens[] = new self($userToken);
        }
        return $usersTokens;
    }

    public static function selectOne(string $where = null, array $params = null) : self|false {
        $result = Database::selectOne(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        return new self($result);
    }

    public static function selectOneByPk(mixed $pk) : self|false {
        $result = Database::selectOneByPk(static::getTable(), $pk, static::getPkName());
        if ($result == false) {
            return false;
        }
        return new self($result);
    }

    public static function selectAll() : array|false {
        $result = Database::selectAll(static::getTable());
        if ($result == false) {
            return false;
        }
        $usersTokens = array();
        foreach ($result as $userToken) {
            $usersTokens[] = new self($userToken);
        }
        return $usersTokens;
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `users_tokens` (
            `user` varchar(320) NOT NULL,
            `token` varchar(255) NOT NULL,
            `expiration` datetime NOT NULL,
            PRIMARY KEY (`user`)
            )';
    }
}
