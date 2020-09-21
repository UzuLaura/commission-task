<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Config;

class Config
{
    protected $data;
    protected $default;

    /**
     * Loads data from config file.
     *
     * @param string $file path to file
     */
    public function load(string $file)
    {
        $this->data = require $file;
    }

    /**
     * Gets data from data array by key.
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->default = $default;

        $segments = explode('.', $key);
        $data = $this->data;

        foreach ($segments as $segment) {
            if (isset($data[$segment])) {
                $data = $data[$segment];
            } else {
                $data = $this->default;
                break;
            }
        }

        return $data;
    }
}
