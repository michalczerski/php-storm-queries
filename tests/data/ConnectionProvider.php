<?php

namespace data;

use Storm\Query\Connection;
use Storm\Query\StormQueries;

class ConnectionProvider
{
    private static ?Connection $connection = null;

    public static function createConnection(): Connection
    {
        return Connection::createFromString(CONNECTION_STRING);
    }

    public static function getConnection(): Connection
    {
        if (self::$connection === null) {
            self::$connection = Connection::createFromString(CONNECTION_STRING);
        }
        return self::$connection;
    }

    public static function getStormQueries(): StormQueries
    {
        return new StormQueries(self::getConnection());
    }
}