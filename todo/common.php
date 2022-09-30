<?php

if (!function_exists('sendJson')) {
    function sendJson($data, $http_code = 200, $message = ""): string
    {
        http_response_code($http_code);
        header('Content-Type: application/json;charset=utf-8');
        $json_data = [
            'result' => $data,
            'error' => $message
        ];
        return json_encode($json_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('sendHtml')) {
    function sendHtml(string $data, $http_code = 200): string
    {
        http_response_code($http_code);
        header('Content-Type: text/html;charset=utf-8');
        return $data;
    }
}
