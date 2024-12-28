<?php

namespace Storm\Query\sql;

class SqlInsertBuilder
{
    private string $tableName;
    private array $values;

    public function into(string $tableName): SqlInsertBuilder
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function values(array $values): SqlInsertBuilder
    {
        $this->values = $values;
        return $this;
    }

    public function toSql(): string
    {
        $parameters = str_repeat('?,', count($this->values) - 1) . '?';
        $columns = "(" . implode(', ', array_keys($this->values)) . ")";
        return "INSERT INTO {$this->tableName} {$columns} VALUES ({$parameters})";
    }

    public function getParameters(): array
    {
        return array_values($this->values);
    }
}