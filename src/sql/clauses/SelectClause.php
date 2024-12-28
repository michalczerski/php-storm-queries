<?php

namespace Storm\Query\sql\clauses;

use InvalidArgumentException;

class SelectClause
{
    private array $columns = [];

    public function add(array $columns): void
    {
        $this->columns = array_merge($this->columns, $columns);
    }

    public function toString(): string
    {
        count($this->columns) or throw new InvalidArgumentException("Columns must not be empty");

        return "SELECT " . implode(", ", $this->columns);
    }
}