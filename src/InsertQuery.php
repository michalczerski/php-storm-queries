<?php

namespace Storm\Query;

use Storm\Query\sql\SqlInsertBuilder;
use DateTime;

class InsertQuery
{
    private SqlInsertBuilder $insertBuilder;

    public function __construct(private readonly IConnection $connection)
    {
        $this->insertBuilder = new SqlInsertBuilder();
    }

    public function into(string $table): InsertQuery
    {
        $this->insertBuilder->into($table);
        return $this;
    }

    public function setValues(array $values): InsertQuery
    {
        $normalized = ParameterNormalizer::normalize($values);
        $this->insertBuilder->values($normalized);
        return $this;
    }

    public function getSql(): string
    {
        return $this->insertBuilder->toSql();
    }

    public function getParameters(): array
    {
        return $this->insertBuilder->getParameters();
    }

    public function execute(): void
    {
        $this->connection->execute($this->getSql(), $this->getParameters());
    }

    public function getLastInsertedId(): string
    {
        return $this->connection->getLastInsertedId();
    }
}