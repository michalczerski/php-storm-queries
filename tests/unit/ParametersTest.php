<?php

namespace unit;

use PHPUnit\Framework\TestCase;
use Storm\Query\IConnection;
use Storm\Query\SelectQuery;

/*
 * test zagniezdzonych andów i orów w unitach
 */
class ParametersTest extends TestCase
{
    private SelectQuery $selectQuery;

    public function testParameters(): void
    {
        $this->selectQuery->select('*')
            ->from('customers')
            ->where('id', 7)
            ->where('id', '>', 10)
            ->where('id', 'BETWEEN', 50, 70)
            ->where('name', 'LIKE', '%Micheal%')
            ->where(function ($query) {
                $query->where('country', 'IN', ['US', 'CA']);
                $query->orWhere('country', 'IN', ['DE', 'FR']);
            });
        $parameters = $this->selectQuery->getParameters();

        $this->assertEquals([7, 10, 50, 70, '%Micheal%', 'US', 'CA', 'DE', 'FR'], $parameters);
    }

    protected function setUp(): void
    {
        $mock = $this->createMock(IConnection::class);
        $this->selectQuery = new SelectQuery($mock);
    }
}