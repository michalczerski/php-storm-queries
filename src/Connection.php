<?php

namespace Storm\Query;

use DateTime;
use PDO;
use PDOStatement;
use Exception;

class Connection implements IConnection
{
    private array $successCallback = [];
    private array $failCallbacks = [];

    public function __construct(
        private PDO $pdo
    ) { }

    public static function createFromString($connectionString): Connection
    {
        return new Connection(new PDO($connectionString));
    }

    public function begin(): void
    {
         $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }
    }

    public function rollback(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollback();
        }
    }

    public function onSuccess(callable $callback): void
    {
        $this->successCallback[] = $callback;
    }

    public function onFailure(callable $callback): void
    {
        $this->failCallbacks[] = $callback;
    }

    public function query(string $query, array $parameters = []): array
    {
        $started = new DateTime();
        try
        {
            $stmt = $this->pdo->prepare($query);
            $this->bindParams($stmt, $parameters);
            $stmt->execute();
            $result = [];
            while ($row = $stmt->fetchObject()) {
                $result[] = $row;
            }
            $this->runOnSuccess($query, $started);
            return $result;
        }
        catch(Exception $exception) {
            $this->runOnFailure($query, $started, $exception);
            throw $exception;
        }
    }

    private function bindParams(PDOStatement $statement, array $parameters): void
    {
        foreach ($parameters as $index => $value) {
            if (is_int($value)) {
                $type = PDO::PARAM_INT;
            }
            else if (is_bool($value)) {
                $type = PDO::PARAM_BOOL;
            }
            else {
                $type = PDO::PARAM_STR;
            }

            $statement->bindValue($index + 1, $value, $type);
        }
    }

    public function execute(string $query, array $parameters = []): bool
    {
        $started = new DateTime();
        try
        {
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute($parameters);
            $this->runOnSuccess($query, $started);
            return $result;
        }
        catch(Exception $exception) {
            $this->runOnFailure($query, $started, $exception);
            throw $exception;
        }
    }

    public function getLastInsertedId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function executeCommands($content): void
    {
        $started = new DateTime();
        try {
            $this->pdo->exec($content);
            $this->runOnSuccess("...", $started);
        }
        catch(Exception $exception) {
            $this->runOnFailure("...", $started, $exception);
            throw $exception;
        }
    }

    private function runOnSuccess(string $sql, DateTime $started): void
    {
        $interval = date_diff(new DateTime(), $started);
        foreach ($this->successCallback as $callback) {
            $callback($sql, $interval);
        }
    }

    private function runOnFailure(string $sql, DateTime $started, Exception $exception): void
    {
        $interval = date_diff(new DateTime(), $started);
        foreach ($this->failCallbacks as $callback) {
            $callback($sql, $interval, $exception);
        }
    }

    /*
    public function insert(string $query, ...$args): int
    {
        $args = $this->prepareData($args);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
        return $this->pdo->lastInsertId();
    }

    public function insertArgs(string $query, array $args): int
    {
        $args = $this->prepareData($args);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
        return $this->pdo->lastInsertId();
    }

    public function insertNoneIdRecord(string $query, ...$args): void
    {
        $args = $this->prepareData($args);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
    }

    public function insertUuid(string $query, ...$args): string
    {
        $args = $this->prepareData($args);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
        return $stmt->fetchColumn();
    }

    public function update(string $query, ...$args): void
    {
        $args = $this->prepareData($args);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
    }

    public function delete(string $query, ...$args): void
    {
        $args = $this->prepareData($args);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
    }

    public function fetch(string $statement, ...$args): array
    {
        return $this->query($statement, null, $args);
    }

    public function fetchArgs(string $statement, array $args): array
    {
        return $this->query($statement, null, $args);
    }
    public function fetchOneArgs(string $statement, array $args): object|null
    {
        $results = $this->query($statement, null, $args);
        return count($results) ? $results[0] : null;
    }
    public function fetchOne(string $statement, ...$args): object|null
    {
        $results = $this->query($statement, null, $args);
        return count($results) ? $results[0] : null;
    }

    public function lastInsertedId(): string
    {
        return $this->pdo->lastInsertId();
    }

    private function prepareData($args): array
    {
        foreach($args as $key => $arg)
        {
            if ($arg instanceof DateTime)
            {
                $args[$key] = $arg->format('Y-m-d H:i:s T');
            }
            if (is_bool($arg))
            {
                $args[$key] = $arg ? 1 : 0;
            }
        }

        return $args;
    }

    private function query(string $query, ?string $obj, array $args): array
    {
        $args = $this->prepareData($args);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
        $result = [];
        while ($row = $stmt->fetchObject($obj)) {
            $result[] = $row;
        }

        return $result;
    }
    */
}