<?php

namespace integration\queries\select;

use data\ConnectionProvider;
use PHPUnit\Framework\TestCase;
use Storm\Query\StormQueries;

final class HavingTest extends TestCase
{
    private static StormQueries $queries;

    public function testHaving(): void
    {
        $items = self::$queries
            ->select('country, city, count(*)')
            ->from('customers')
            ->groupBy('country, city')
            ->having('count(*)', '>', 1)
            ->having('city', 'LIKE', '%o%')
            ->find();

        $this->assertCount(7, $items);
    }

    public static function setUpBeforeClass(): void
    {
        self::$queries = ConnectionProvider::getStormQueries();
    }
}