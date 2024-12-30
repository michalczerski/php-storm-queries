<?php

namespace integration\queries;

use data\ConnectionProvider;
use PHPUnit\Framework\TestCase;
use Storm\Query\StormQueries;

final class DeleteTest extends TestCase
{
    private static StormQueries $queries;

    public function testDelete(): void
    {
        self::$queries
            ->delete('delete_test')
            ->where('id', 1)
            ->execute();

        $count = self::$queries->select('count(*) as count')->from('delete_test')->findSingle()->count;

        $this->assertEquals(1, $count);
    }
    public static function setUpBeforeClass(): void
    {
        self::$queries = ConnectionProvider::getStormQueries();
    }
}