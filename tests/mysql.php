<?php

require_once 'autoload.php';

use data\ConnectionProvider;

const CONNECTION_STRING = "mysql:host=localhost;port=7801;dbname=storm_test;user=mysql;password=mysql";

try{
    $connection = ConnectionProvider::getConnection();
    $connection->execute("DROP DATABASE IF EXISTS storm_test");
    $connection->execute("CREATE DATABASE storm_test");
    $connection->execute("USE storm_test");

    $schema = file_get_contents(__DIR__ . "/data/mysql.sql");
    $data = file_get_contents(__DIR__ . "/data/data.sql");

    $connection->executeCommands($schema);
    $connection->executeCommands($data);
}
catch(Exception $e) {
    echo ('Cannot connect to database. Run `docker compose up`.');
    die;
}
