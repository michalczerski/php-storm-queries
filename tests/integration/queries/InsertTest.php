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
                'name' => 'One two three',
                'is_checked' => true,
                'num' => 7,
                'num_f' => 77.7,
                'date' => new DateTime('2020-01-01'),
            ]);

        $query->execute();

        $this->assertEquals(1, $query->getLastInsertedId());
    }
    public static function setUpBeforeClass(): void
    {
        self::$queries = ConnectionProvider::getStormQueries();
    }
}