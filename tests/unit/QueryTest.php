<?php

use PHPUnit\Framework\TestCase;
use Storm\Query\IConnection;
use Storm\Query\SelectQuery;

final class QueryTest extends TestCase
{
    private SelectQuery $selectBuilder;

    public function testQueryValidationWhereThereIsNoSelectAndFromClause(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->selectBuilder->getSql();
    }

    public function testQueryValidationWhereThereIsNoSelectClause(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->selectBuilder->from('users');

        $this->selectBuilder->getSql();
    }

    public function testQueryValidationWhereThereIsNoFromClause(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->selectBuilder->select('*');

        $this->selectBuilder->getSql();
    }

    public function testQueryWithSelectAndFrom(): void
    {
        $this->selectBuilder->select('*');
        $this->selectBuilder->from('users');
        $query = $this->selectBuilder->getSql();
        $query = trim(str_replace("\n", ' ', $query));

        $this->assertEquals("SELECT * FROM users", $query);
    }

    protected function setUp(): void
    {
        $mock = $this->createMock(IConnection::class);
        $this->selectBuilder = new SelectQuery($mock);
    }
}