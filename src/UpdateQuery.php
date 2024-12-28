<?php

namespace Storm\Query;

use Storm\Query\sql\SqlUpdateBuilder;
use DateTime;

class UpdateQuery
{
    private SqlUpdateBuilder $updateBuilder;

    public function __construct(private readonly IConnection $connection)
    {
        $this->updateBuilder = new SqlUpdateBuilder();
    }

    public function update(string $table): UpdateQuery
    {
        $this->updateBuilder->update($table);
        return $this;
    }

    public function setValues(array $values): UpdateQuery
    {
        $normalized = ParameterNormalizer::normalize($values);
        $this->updateBuilder->values($normalized);
        return $this;
    }

    public function where(): UpdateQuery
    {
        call_user_func_array([$this->updateBuilder, 'where'], func_get_args());
        return $this;
    }

    public function orWhere(): UpdateQuery
    {
        call_user_func_array([$this->updateBuilder, 'orWhere'], func_get_args());
        return $this;
    }

    public function getSql(): string
    {
        return $this->updateBuilder->toSql();
    }

    public function getParameters(): array
    {
        return $this->updateBuilder->getParameters();
    }

    public function execute(): void
    {
        $this->connection->execute($this->getSql(), $this->getParameters());
    }
}