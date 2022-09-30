<?php

namespace todo;

class Config
{
    private $config = [];

    public function __construct()
    {
    }

    final public function load(string $file): void
    {
        if (is_file($file)) {
            $file_info = pathinfo($file);
            if (!(isset($file_info['extension']) && strtolower($file_info['extension']) == 'php')) {
                return;
            }
            if (!isset($file_info['filename'])) {
                return;
            }
            $this->config[$file_info['filename']] = require_once $file;
        }
    }

    /**
     * @param string $config_name
     * @param string|null $index_name
     * @return mixed|null
     */
    final public function getConfig(string $config_name, string $index_name = null)
    {
        if (isset($this->config[$config_name])) {
            if ($index_name == null) {
                return $this->config[$config_name];
            }
            if (isset($this->config[$config_name][$index_name])) {
                return $this->config[$config_name][$index_name];
            }
        }
        return null;
    }
}
