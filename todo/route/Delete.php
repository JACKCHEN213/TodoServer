<?php

namespace todo\route;

class Delete extends RequestMethod
{
    public static function dispatch($class): string
    {
        $data = self::buildData('delete');
        return (new $class())->delete($data);
    }
}
