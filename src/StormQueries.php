<?php

namespace Storm\Query;

use Storm\Query\queries\DeleteQuery;
use Storm\Query\queries\InsertQuery;
use Storm\Query\queries\SelectQuery;
use Storm\Query\queries\UpdateQuery;

readonly class StormQueries
{
    public function __construct(private IConnection $connection)
    {
    }

    public function insert($table, $values = array()): InsertQuery
    {
        $query = new InsertQuery($this->connection);
        $query->into($table);
        if (count($values)) {
            $query->setValues($values);
        }
        return $query;
    }

    public function select(...$fields): SelectQuery
    {
        $selectQuery = new SelectQuery($this->connection);
        call_user_func_array([$selectQuery, 'select'], func_get_args());
        return $selectQuery;
    }

    public function from(string $table, string $where = '', ...$parameters): SelectQuery
    {
        $selectQuery = new SelectQuery($this->connection);
        $selectQuery->select('*');
        $selectQuery->from($table);
        if (!empty($where)) {
            $selectQuery->whereString($where, $parameters);
        }
        return $selectQuery;
    }

    public function update($table, $values = array()): UpdateQuery
    {
        $query = new UpdateQuery($this->connection);
        $query->update($table);
        if (count($values)) {
            $query->setValues($values);
        }
        return $query;
    }

    public function delete($table): DeleteQuery
    {
        $query = new DeleteQuery($this->connection);
        $query->from($table);
        return $query;
    }




}