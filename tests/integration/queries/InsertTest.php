<?php

namespace integration\queries;

use data\ConnectionProvider;
use DateTime;
use PHPUnit\Framework\TestCase;
use Storm\Query\StormQueries;

final class InsertTest extends TestCase
{
    private static StormQueries $queries;

    public function testInsert(): void
    {
        $query = self::$queries
            ->insert('insert_test')
            ->setValues([
                'name' => 'first'
            ]);

        $query->execute();

        $this->assertEquals(1, $query->getLastInsertedId());
    }

    public function testInsertWithOneInvoke(): void
    {
        $query = self::$queries->insert('insert_test', ['name' => 'second']);

        $query->execute();

        $this->assertEquals(2, $query->getLastInsertedId());
    }

    public static function setUpBeforeClass(): void
    {
        self::$queries = ConnectionProvider::getStormQueries();
    }
}