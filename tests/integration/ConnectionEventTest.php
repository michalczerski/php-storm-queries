<?php

namespace integration;

use data\ConnectionProvider;
use Exception;
use PHPUnit\Framework\TestCase;
use Storm\Query\StormQueries;

interface Notifier
{
    function onSuccess();
    function onFailure();
}

final class ConnectionEventTest  extends TestCase
{
    public function testQuerySuccess(): void
    {
        $called = false;
        $connection = ConnectionProvider::getConnection();
        $connection->onSuccess(function($sql, $interval) use (&$called) {
            $called = true;
        });

        $queries = new StormQueries($connection);
        $queries->select('*')
            ->from('customers')
            ->where('customer_id', 5)
            ->find();

        $this->assertTrue($called);
    }

    public function testOnFailure(): void
    {
        $called = false;
        $connection = ConnectionProvider::getConnection();
        $connection->onFailure(function($sql, $interval, $e) use (&$called) {
            $called = true;
        });
        $queries = new StormQueries($connection);
        try {
            $queries->select('*')
                ->from('customers')
                ->where('customer_i', 5)
                ->find();
        } catch(Exception) { }

        $this->assertTrue($called);
    }
}