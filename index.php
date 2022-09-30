<?php

require_once "vendor/autoload.php";

use todo\App;

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin:" . $_SERVER['HTTP_ORIGIN']);
}
header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE, PUT, PATCH");
$allow_header = [
    "Authorization", "DNT", "X-Mx-ReqToken", "Keep-Alive", "User-Agent",
    "X-Requested-With", "If-Modified-Since", "Cache-Control",
    "Content-Type", "Accept-Language", "Origin", "Accept-Encoding",
];
header("Access-Control-Allow-Headers:" . implode(',', $allow_header));

(new App())->run()->send();
