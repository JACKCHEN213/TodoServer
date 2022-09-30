<?php

namespace todo\route;

class Post extends RequestMethod
{
    public static function dispatch($class): string
    {
        $data = self::buildData('post');
        return (new $class())->post($data);
    }
}
