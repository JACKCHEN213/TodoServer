<?php

namespace todo\route;

class Get extends RequestMethod
{
    public static function dispatch(string $class): string
    {
        $data = self::buildData();
        if (isset($data['id'])) {
            return (new $class())->get($data['id']);
        }
        return (new $class())->getAll();
    }
}
