<?php

namespace StandardBundle\Models;

use Framework\Components\Model as Model;
use Framework\Core\Database as Database;

final class Contribution extends Model {
    protected static string $_table = 'contributions';


    /**
     * @param string|User $user
     * @param string $title
     * @param string $genre
     * @param string $review
     * @param int $date
     * @param string $actor
     * @param string $director


     */
    public static function create(... $args) : Contribution {
        if(count($args) != 5)
            return false;

        $user = $args[0];
        $title = $args[1];
        $genre = $args[2];
        $review = $args[3];
        $director = $args[4];
        $actor = $args[5];
        $date = $args[6];



        if ($user instanceof User) {
            $user = $user->get('email');
        }

        $data = array(
            'user' => $user,
            'title' => $title,
            'actor' => $actor,
            'review' => $review,
            'director' => $director,
            'genre' => $genre,
            'date' => $date

        );

        $contribution = new Contribution($data);
        $contribution->insert();

        $id = Database::class::lastInsertId();
        if (!$id)
            return false;

        $id = (int) $id;
        $contribution->setPk($id);

        return $contribution;
    }

    public static function select(string $where = null, array $params = null) : array|false {
        $result = Database::select(static::getTable(), $where, $params);
        if ($result == false) {
            return false;
        }
        $groups = array();
        foreach ($result as $group) {
            $groups[] = new self($group);
        }
        return $groups;
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

    public static function selectAll(): array|false {
        $result = Database::selectAll(static::getTable());
        if ($result == false) {
            return false;
        }
        $groups = array();
        foreach ($result as $group) {
            $groups[] = new self($group);
        }
        return $groups;
    }

    public static function initTable() : string {
        return 'CREATE TABLE IF NOT EXISTS `contributions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user` varchar(320) NOT NULL,
            `title` varchar(255) NOT NULL,
            `sinopsis` text(65535) NOT NULL,
            `review` text(65535) NOT NULL,
            `genre` text(65535) NOT NULL,
            `actor` text(65535) NOT NULL,
            `director` text (65535) NOT NULL,
            `date` int(11) NOT NULL,
            PRIMARY KEY (`id`)
            )';
    }
}
