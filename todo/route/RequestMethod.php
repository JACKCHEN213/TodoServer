<?php

namespace todo\route;

use Exception;

abstract class RequestMethod
{
    public static function getPostData()
    {
        $headers = apache_request_headers();
        if (isset($headers['Content-Type'])) {
            $data = file_get_contents('php://input');
            if (strpos($headers['Content-Type'], 'json') !== false) {
                return json_decode($data, true);
            }
            if (!$data) {
                return $_POST;
            }
            return $data;
        } else {
            return $_POST;
        }
    }

    final public static function buildData(string $type = 'get'): array
    {
        if ($type == 'get') {
            $data = $_GET;
        } else {
            $data = self::getPostData();
        }
        return array_map('addslashes', $data);
    }

    abstract public static function dispatch(string $class): string;
}
