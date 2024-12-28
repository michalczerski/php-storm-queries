<?php

namespace Storm\Query;

interface IConnection
{
    function begin(): void;

    public function commit(): void;

    public function rollback(): void;

    public function execute(string $query, array $parameters = []): bool;

    public function getLastInsertedId(): string;

    public function executeCommands($content): void;
}