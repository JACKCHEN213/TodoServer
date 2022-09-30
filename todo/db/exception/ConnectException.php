<?php

namespace todo\db\exception;

use Exception;
use Throwable;

class ConnectException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("数据库连接失败: " . $message, $code, $previous);
    }
}
