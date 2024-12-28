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

    public function insert($table): InsertQuery
    {
        $query = new InsertQuery($this->connection);
        $query->into($table);
        return $query;
    }

    public function select(...$fields): SelectQuery
    {
        $selectQuery = new SelectQuery($this->connection);
        call_user_func_array([$selectQuery, 'select'], func_get_args());
        return $selectQuery;
    }

    public function update($table): UpdateQuery
    {
        $query = new UpdateQuery($this->connection);
        $query->update($table);
        return $query;
    }

    public function delete($table): DeleteQuery
    {
        $query = new DeleteQuery($this->connection);
        $query->from($table);
        return $query;
    }




}