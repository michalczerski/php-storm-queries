<?php

namespace Storm\Query;

use PDO;

class ConnectionFactory
{
    public static function createFromString(string $connection, string $user, string $password): IConnection
    {
        $pdo = new PDO($connection, $user, $password);
        return new Connection($pdo);
    }
}