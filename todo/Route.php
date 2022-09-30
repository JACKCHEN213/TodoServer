<?php

namespace todo;

use app\Index;
use Exception;

class Route
{
    private $response;
    private $allow_method = [
        'get', 'post', 'put', 'delete',
    ];

    /**
     * @throws Exception
     */
    final public function parseUri(string $uri): array
    {
        $query_list = explode('?', $uri);
        if (count($query_list) < 1) {
            throw new Exception('路径解析失败: ' . $uri);
        }
        $base_uri = str_replace("_", "", $query_list[0]);
        $uri_list = explode('/', $base_uri);
        if (count($uri_list) == 1) {
            return ['uri' => $base_uri, 'class' => $uri_list[0]];
        }
        return ['uri' => $base_uri, 'class' => $uri_list[1]];
    }

    final public function run(): Route
    {
        $uri_data = $this->parseUri($_SERVER['REQUEST_URI']);
        if ($uri_data['uri'] == '/') {
            $this->response = (new Index())->index();
            return $this;
        }
        if (!$uri_data['class']) {
            $this->response = (new Index())->notFound();
            return $this;
        }
        $class = "app\\" . $uri_data['class'];
        if (!class_exists($class)) {
            $this->response = (new Index())->notFound();
            return $this;
        }
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        if (in_array($method, $this->allow_method)) {
            $handle = "todo\\route\\" . ucfirst($method);
            $this->response = $handle::dispatch($class);
        } else {
            if ($method = 'options') {
                $this->response = ((new Index())->index());
            } else {
                $this->response = (new Index())->unSupportMethod($method);
            }
        }
        return $this;
    }

    final public function send(): void
    {
        echo $this->response;
    }
}
