<?php

namespace todo\route;

class Put extends RequestMethod
{
    public static function dispatch($class): string
    {
        $data = self::buildData('put');
        return (new $class())->put($data);
    }
}
