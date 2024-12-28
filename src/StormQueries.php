<?php

namespace Storm\Query;

class StormQueries
{
    public function __construct(private IConnection $connection)
    {
    }

    public function select(...$fields): SelectQuery
    {
        $selectQuery = new SelectQuery($this->connection);
        call_user_func_array([$selectQuery, 'select'], func_get_args());
        return $selectQuery;
    }
}