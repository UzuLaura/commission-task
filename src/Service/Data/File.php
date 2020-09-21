<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

class File
{
    /**
     * @var string path to file
     */
    protected $file_path;

    /**
     * File constructor.
     *
     * @param $file_path
     */
    public function __construct(string $file_path)
    {
        $this->file_path = $file_path;
    }

    /**
     * Returns error if file doesn't exist.
     *
     * @throws \Exception
     */
    protected function checkIfExist(): bool
    {
        if (!file_exists($this->file_path)) {
            throw new \Exception('File or directory '.$this->file_path.' doesn\'t exist.');
        }

        return true;
    }

    /**
     * Open file for data handling.
     *
     * @return bool|resource
     */
    protected function handleData()
    {
        return fopen($this->file_path, 'r');
    }
}
