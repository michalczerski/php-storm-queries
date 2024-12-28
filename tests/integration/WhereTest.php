<?php

namespace integration;

use data\ConnectionProvider;
use PHPUnit\Framework\TestCase;
use Storm\Query\StormQueries;

final class WhereTest extends TestCase
{
    private static StormQueries $queries;

    public function testDefault(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_id', 77)
            ->find();

        $this->assertCount(1, $items);
    }

    public function testEqual(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_id', '=', 77)
            ->find();

        $this->assertCount(1, $items);
    }

    public function testWhereWithNotEqual(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('country', '<>', 'USA')
            ->find();

        $this->assertCount(78, $items);
    }

    public function testWhereWithNotWordEqual(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('country', 'NOT', 'USA')
            ->find();

        $this->assertCount(78, $items);
    }

    public function testWhereIn(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('country', 'IN', ['USA', 'Germany'])
            ->find();

        $this->assertCount(24, $items);
    }

    public function testWhereNotIn(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('country', 'NOT IN', ['Germany', 'USA'])
            ->find();

        $this->assertCount(67, $items);
    }

    public function testWhereGreater(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_id', '>', 89)
            ->find();

        $this->assertCount(2, $items);
    }

    public function testWhereGreaterEqual(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_id', '>=', 89)
            ->find();

        $this->assertCount(3, $items);
    }

    public function testWhereLess(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_id', '<', 5)
            ->find();

        $this->assertCount(4, $items);
    }

    public function testWhereLessEqual(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_id', '<=', 5)
            ->find();

        $this->assertCount(5, $items);
    }

    public function testWherePercentLike(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_name', 'LIKE', '%a')
            ->find();

        $this->assertCount(7, $items);
    }

    public function testWhereLikePercent(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_name', 'LIKE', 'A%')
            ->find();

        $this->assertCount(4, $items);
    }

    public function testWherePercentLikePercent(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_name', 'LIKE', '%z%')
            ->find();

        $this->assertCount(5, $items);
    }

    public function testWhereIsNull(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('shippers')
            ->where('phone', 'IS NULL')
            ->find();

        $this->assertCount(1, $items);
    }

    public function testWhereIsNotNull(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('shippers')
            ->where('phone', 'IS NOT NULL')
            ->find();

        $this->assertCount(3, $items);
    }

    public function testWhereBetween(): void
    {
        $items = self::$queries
            ->select('*')
            ->from('customers')
            ->where('customer_id', 'BETWEEN', 10 , 20)
            ->find();

        $this->assertCount(11, $items);
    }

    public static function setUpBeforeClass(): void
    {
        self::$queries = ConnectionProvider::getStormQueries();
    }
}