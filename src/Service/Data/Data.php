<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

use Laura\CommissionTask\Config\Config;

class Data extends File
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $data_keys;

    /**
     * Set data keys array with keys from config file for resetting data array indexes.
     *
     * @throws \Exception
     */
    private function setDataKeys()
    {
        $config = new Config();
        $config->load($_ENV['OPERATION_KEYS_DATA_PATH']);
        $this->data_keys = $config->get('data_order', false);

        if (!$this->data_keys) {
            throw new \Exception('No data keys are available. Check your configuration file.');
        }
    }

    /**
     * Reset index names in data array.
     */
    private function resetDataIndexes()
    {
        foreach ($this->data as $data_index => $data) {
            foreach ($this->data_keys as $key_name => $key_index) {
                if (isset($data[$key_index])) {
                    $this->data[$data_index][$key_name] = (string) $data[$key_index];
                    unset($this->data[$data_index][$key_index]);
                }
            }
        }
    }

    /**
     * Set data array.
     *
     * @throws \Exception
     */
    private function setData()
    {
        try {
            $this->checkIfExist();
            $handle = $this->handleData();
            while ($data = fgetcsv($handle)) {
                $this->data[] = $data;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }

        if (empty($this->data)) {
            throw new \Exception('No data available in '.$this->file_path);
        }
    }

    /**
     * Get data array.
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getData()
    {
        try {
            $this->setData();
            $this->setDataKeys();
            $this->resetDataIndexes();

            return $this->data;
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }
}
