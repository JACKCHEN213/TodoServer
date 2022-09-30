<?php

namespace app;

class Index
{
    final public function index(): string
    {
        $index = <<<EOF
<!DOCTYPE html>
<html>
<head>
<title>Welcome to nginx!</title>
<style>
html { color-scheme: light dark; }
body { width: 35em; margin: 0 auto;
font-family: Tahoma, Verdana, Arial, sans-serif; }
</style>
</head>
<body>
<h1>Welcome to nginx!</h1>
<p>If you see this page, the nginx web server is successfully installed and
working. Further configuration is required.</p>

<p>For online documentation and support please refer to
<a href="http://nginx.org/">nginx.org</a>.<br/>
Commercial support is available at
<a href="http://nginx.com/">nginx.com</a>.</p>

<p><em>Thank you for using nginx.</em></p>
</body>
</html>
EOF;
        return sendHtml($index);
    }

    final public function notFound(): string
    {
        $not_found = <<<EOF
<html>
<head><title>404 Not Found</title></head>
<body>
<center><h1>404 Not Found</h1></center>
<hr><center>nginx/1.15.11</center>
</body>
</html>
EOF;
        return sendHtml($not_found, 404);
    }

    final public function unSupportMethod(string $method): string
    {
        $un_support_method = <<<EOF
<html>
<head><title>405 Method Not Allowed</title></head>
<body>
<center><h1>404 Method Not Allowed($method)</h1></center>
<hr><center>nginx/1.15.11</center>
</body>
</html>
EOF;

        return sendHtml($un_support_method, 405);
    }
}
