<?php

namespace app;

use Exception;
use stdClass;
use todo\Db;

class ListData
{
    final public function get($id): string
    {
        $sql = "SELECT * FROM list WHERE id={$id}";
        $data = Db::query($sql);
        if (count($data) != 1) {
            return sendJson(new stdClass());
        }
        $row = $data[0];
        $row['show'] = boolval($row['show']);
        $row['stat'] = boolval($row['stat']);
        return sendJson($row);
    }

    final public function getAll(): string
    {
        $data = Db::query("SELECT * FROM list");
        foreach ($data as $key => $row) {
            $data[$key]['show'] = boolval($row['show']);
            $data[$key]['stat'] = boolval($row['stat']);
        }
        return sendJson($data);
    }

    final public function post(array $data): string
    {
        $require_field = ['title' => 'strval', 'show' => 'intval', 'stat' => 'intval'];
        $lack_field = array_diff(array_keys($require_field), array_keys($data));
        if ($lack_field) {
            return sendJson("添加失败", 400, '添加事项参数错误，缺少参数: ' . implode(', ', $lack_field));
        }
        $sql = "INSERT INTO `list`(`title`, `show`, `stat`) VALUES (%s)";
        $sql_list = [];
        foreach ($data as $field => $value) {
            if (isset($require_field[$field])) {
                $sql_list[] = "'{$require_field[$field]($value)}'";
            }
        }
        Db::start();
        $res = Db::add(sprintf($sql, implode(", ", $sql_list)));
        if ($res) {
            Db::commit();
            return sendJson($res);
        }
        Db::rollback();
        return sendJson("添加失败", 200, Db::getError());
    }

    final public function put(array $data): string
    {
        if (!isset($data['id'])) {
            return sendJson("修改失败", 400, '修改事项参数错误，缺少参数: id');
        }
        $sql = "SELECT * FROM list WHERE id={$data['id']}";
        $result = Db::query($sql);
        if (!$result) {
            return sendJson("修改失败", 400, "id为 {$data['id']} 的事项不存在");
        }

        $update_field = ['title' => 'strval', 'show' => 'intval', 'stat' => 'intval'];
        $sql = "UPDATE `list` SET";
        $sql_list = [];
        foreach ($data as $field => $value) {
            if (isset($update_field[$field])) {
                $sql_list[] = "`{$field}`='" . $update_field[$field]($value) . "'";
            }
        }
        if (!$sql_list) {
            return sendJson("修改成功");
        }
        Db::start();
        $sql .= implode(",", $sql_list) . " WHERE `id`={$data['id']}";
        $res = Db::update($sql);
        if (!$res) {
            Db::rollback();
            return sendJson("修改失败", 400, Db::getError());
        }
        Db::commit();

        return sendJson("修改成功");
    }

    final public function delete(array $data): string
    {
        if (!isset($data['id'])) {
            return sendJson("删除失败", 400, '删除事项参数错误，缺少参数: id');
        }
        $sql = "SELECT * FROM list WHERE id={$data['id']}";
        $result = Db::query($sql);
        if (!$result) {
            return sendJson("删除失败", 400, "id为 {$data['id']} 的事项不存在");
        }

        $sql = "DELETE FROM list WHERE `id`={$data['id']}";
        Db::start();
        $res = Db::delete($sql);
        if (!$res) {
            Db::rollback();
            return sendJson("删除失败", 400, Db::getError());
        }
        Db::commit();
        return sendJson("删除成功");
    }
}
