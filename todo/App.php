<?php

namespace todo;

use Exception;
use todo\db\MySQL;

/**
 * @property Config $config
 * @property Route $route
 */
class App
{
    private $initilized = false;
    private $root_path;
    private $config_path;
    private static $instance;
    private $bind = [
        'config' => Config::class,
        'route' => Route::class,
    ];

    public function __construct()
    {
        $this->root_path = realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . DIRECTORY_SEPARATOR;
        $this->config_path = $this->root_path . 'config' . DIRECTORY_SEPARATOR;
    }

    public function __get(string $name)
    {
        if (isset($this->bind[$name])) {
            if (!isset($this->$name)) {
                $this->$name = new $this->bind[$name]();
            }
            return $this->$name;
        } else {
            return null;
        }
    }

    private function loadPublicFunctions(string $file): void
    {
        require_once $file;
    }

    private function init(): void
    {
        if ($this->initilized) {
            return;
        }
        $this->initilized = true;
        // 加载公共函数
        $this->loadPublicFunctions(__DIR__ . DIRECTORY_SEPARATOR . 'common.php');
        // 加载配置文件的配置
        $config_list = scandir($this->config_path);
        foreach ($config_list as $file_name) {
            if ($file_name == '.' || $file_name == '..') {
                continue;
            }
            $this->config->load($this->config_path . $file_name);
        }
        // 设置MySQL
        Db::setDriver(new MySQL(
            $this->config->getConfig('database', 'host'),
            $this->config->getConfig('database', 'port'),
            $this->config->getConfig('database', 'user'),
            $this->config->getConfig('database', 'password'),
            $this->config->getConfig('database', 'db'),
            $this->config->getConfig('database', 'charset'),
        ));
    }

    final public function run(): Route
    {
        $this->init();
        self::setInstance($this);
        return $this->route->run();
    }

    public static function getInstance(): object
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public static function setInstance(object $instance): void
    {
        self::$instance = $instance;
    }
}
