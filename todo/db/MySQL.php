<?php

namespace todo\db;

use todo\db\exception\ConnectException;
use todo\db\exception\QueryException;

class MySQL implements Driver
{
    private $connect;
    private $error = '';

    /**
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     * @param string $db
     * @param string $charset
     * @throws ConnectException
     */
    public function __construct(string $host, int $port, string $user, string $password, string $db, string $charset)
    {
        $this->connect = mysqli_connect($host, $user, $password, $db, $port);
        if ($this->connect) {
            mysqli_set_charset($this->connect, $charset);
            mysqli_select_db($this->connect, $db);
        } else {
            $this->error = mysqli_connect_error();
            throw new ConnectException($this->error);
        }
    }

    /**
     * @throws QueryException
     */
    final public function select(string $sql): array
    {
        $res = mysqli_query($this->connect, $sql);
        if (!$res) {
            $this->error = mysqli_error($this->connect);
            throw new QueryException($this->error);
        }
        $ret_data = [];
        while (false != ($row = mysqli_fetch_assoc($res))) {
            $ret_data[] = $row;
        }
        return $ret_data;
    }

    final public function add(string $sql): int
    {
        $res = mysqli_query($this->connect, $sql);
        if (!$res) {
            $this->error = mysqli_error($this->connect);
            return 0;
        }
        $sql = "SELECT last_insert_id()";
        $res = mysqli_query($this->connect, $sql);
        if (!$res) {
            $this->error = mysqli_error($this->connect);
            return 0;
        }
        $row = mysqli_fetch_assoc($res);
        return intval($row['last_insert_id()']);
    }

    final public function update(string $sql): bool
    {
        $res = mysqli_query($this->connect, $sql);
        if (!$res) {
            $this->error = mysqli_error($this->connect);
        }
        return $res;
    }

    final public function delete(string $sql): bool
    {
        return $this->update($sql);
    }

    final public function start(): bool
    {
        $res = mysqli_autocommit($this->connect, false);
        if (!$res) {
            $this->error = mysqli_error($this->connect);
        }
        return $res;
    }

    final public function commit(): bool
    {
        $res = mysqli_commit($this->connect);
        if (!$res) {
            $this->error = mysqli_error($this->connect);
        }
        return $res;
    }

    final public function rollback(): bool
    {
        $res = mysqli_rollback($this->connect);
        if (!$res) {
            $this->error = mysqli_error($this->connect);
        }
        return $res;
    }

    final public function getError(): string
    {
        return $this->error;
    }
}
