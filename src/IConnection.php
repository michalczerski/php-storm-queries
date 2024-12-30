<?php

namespace Storm\Query;

interface IConnection
{
    function begin(): void;

    public function commit(): void;

    public function rollback(): void;

    public function query(string $query, array $parameters = []): array;
    public function execute(string $query, array $parameters = []): bool;

    public function getLastInsertedId(): int;

    public function executeCommands($content): void;

    public function getDatabaseType(): string;
}