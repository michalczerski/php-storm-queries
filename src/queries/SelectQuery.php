<?php

namespace Storm\Query\queries;

use Storm\Query\IConnection;
use Storm\Query\Mapper\Map;
use Storm\Query\Mapper\Mapper;
use Storm\Query\ParameterNormalizer;
use Storm\Query\sql\SqlSelectBuilder;

class SelectQuery
{
    private SqlSelectBuilder $selectBuilder;

    public function __construct(private IConnection $connection)
    {
        $this->selectBuilder = new SqlSelectBuilder();
    }

    public function from(string $table): SelectQuery
    {
        $this->selectBuilder->from($table);
        return $this;
    }

    public function select(string ...$fields): SelectQuery
    {
        call_user_func_array([$this->selectBuilder, 'select'], func_get_args());
        return $this;
    }

    public function leftJoin(string $table, $l, $r): SelectQuery
    {
        $this->selectBuilder->leftJoin('', $table, $l, $r);
        return $this;
    }

    public function leftOuterJoin(string $table, $l, $r): SelectQuery
    {
        $this->selectBuilder->leftJoin('OUTER', $table, $l, $r);
        return $this;
    }

    public function where(): SelectQuery
    {
        call_user_func_array([$this->selectBuilder, 'where'], func_get_args());
        return $this;
    }

    public function orWhere(): SelectQuery
    {
        call_user_func_array([$this->selectBuilder, 'orWhere'], func_get_args());
        return $this;
    }

    public function having(): SelectQuery
    {
        call_user_func_array([$this->selectBuilder, 'having'], func_get_args());
        return $this;
    }

    public function orHaving(): SelectQuery
    {
        call_user_func_array([$this->selectBuilder, 'orHaving'], func_get_args());
        return $this;
    }

    public function orderByDesc(string $column): SelectQuery
    {
        $this->selectBuilder->orderByDesc($column);
        return $this;
    }

    public function orderByAsc(string $column): SelectQuery
    {
        $this->selectBuilder->orderByAsc($column);
        return $this;
    }

    public function orderBy(string $column, int $direction): SelectQuery
    {
        $this->selectBuilder->orderBy($column, $direction);
        return $this;
    }

    public function groupBy(string ...$fields): SelectQuery
    {
        call_user_func_array([$this->selectBuilder, 'groupBy'], func_get_args());
        return $this;
    }

    public function limit(int $limit): SelectQuery
    {
        $this->selectBuilder->limit($limit);
        return $this;
    }

    public function offset(int $offset): SelectQuery
    {
        $this->selectBuilder->offset($offset);
        return $this;
    }

    public function getSql(): string
    {
        return $this->selectBuilder->toSql();
    }

    public function getParameters(): array
    {
        return $this->selectBuilder->getParameters();
    }

    public function findSingle(Map $map = null): ?object
    {
        $results = $this->find();
        if ($map !== null) {
            $results = Mapper::map($results, $map);
        }
        return count($results) > 0 ? $results[0] : null;
    }

    public function find(Map $map = null): array
    {
        $sql = $this->selectBuilder->toSql();
        $parameters = ParameterNormalizer::normalize($this->selectBuilder->getParameters());
        $results = $this->connection->query($sql, $parameters);
        if ($map !== null) {
            $results = Mapper::map($results, $map);
        }
        return $results;
    }
}