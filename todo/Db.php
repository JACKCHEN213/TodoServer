<?php

namespace todo;

use todo\db\Driver;

class Db
{
    private static $driver;
    public static function setDriver(Driver $driver): void
    {
        self::$driver = $driver;
    }

    public static function query(string $sql): array
    {
        return self::$driver->select($sql);
    }

    public static function add(string $sql): int
    {
        return self::$driver->add($sql);
    }

    public static function update(string $sql): bool
    {
        return self::$driver->update($sql);
    }

    public static function delete(string $sql): bool
    {
        return self::$driver->delete($sql);
    }

    public static function start(): bool
    {
        return self::$driver->start();
    }

    public static function commit(): bool
    {
        return self::$driver->commit();
    }

    public static function rollback(): bool
    {
        return self::$driver->rollback();
    }

    public static function getError(): string
    {
        return self::$driver->getError();
    }
}
