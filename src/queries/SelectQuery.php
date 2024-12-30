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

    public function whereString(string $whereCondition, array $parameters): SelectQuery
    {
        $this->selectBuilder->whereString($whereCondition, $parameters);
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

    public function min(string $column): float
    {
        $this->selectBuilder->clearSelect();
        $this->selectBuilder->select('min(' . $column . ') as _min');
        return $this->findSingle()->_min;
    }

    public function max(string $column): float
    {
        $this->selectBuilder->clearSelect();
        $this->selectBuilder->select('max(' . $column . ') as _max');
        return $this->findSingle()->_max;
    }

    public function count(): int
    {
        $this->selectBuilder->clearSelect();
        $this->selectBuilder->select('count(*) as _count');
        return $this->findSingle()->_count;
    }

    public function avg(string $column): float
    {
        $this->selectBuilder->clearSelect();
        $this->selectBuilder->select('avg(' . $column . ') as _avg');
        return round($this->findSingle()->_avg, 5);
    }

    public function sum(string $column): float
    {
        $this->selectBuilder->clearSelect();
        $this->selectBuilder->select('sum(' . $column . ') as _sum');
        return $this->findSingle()->_sum;
    }
}