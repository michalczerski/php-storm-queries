<?php

namespace integration\queries;

use data\ConnectionProvider;
use PHPUnit\Framework\TestCase;
use Storm\Query\StormQueries;

final class SelectTest extends TestCase
{
    private static StormQueries $queries;

    public function testFindOne(): void
    {
        $item = self::$queries
            ->select('*')
            ->from('customers')
            ->where('city', 'London')
            ->findOne();

        $this->assertNotNull($item);
    }

    public function testFindAll(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('city', 'London')
            ->find();

        $this->assertCount(6, $items);
    }

    public static function setUpBeforeClass(): void
    {
        self::$queries = ConnectionProvider::getStormQueries();
    }
}