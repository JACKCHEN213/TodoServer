<?php

namespace todo\db;

interface Driver
{
    public function select(string $sql): array;

    public function add(string $sql): int;

    public function update(string $sql): bool;

    public function delete(string $sql): bool;

    public function start(): bool;

    public function commit(): bool;

    public function rollback(): bool;

    public function getError(): string;
}
