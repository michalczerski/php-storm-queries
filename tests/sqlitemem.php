<?php

require_once 'autoload.php';

use data\ConnectionProvider;

const CONNECTION_STRING = "sqlite:memory";

$connection = ConnectionProvider::getConnection();

$schema = file_get_contents(__DIR__ . "/data/sqlite.sql");
$data = file_get_contents(__DIR__ . "/data/data.sql");

$connection->executeCommands($schema);
$connection->executeCommands($data);

