<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

class Results
{
    /**
     * @var string path to file
     */
    private $file_path;

    /**
     * @var array
     */
    private static $results;

    /**
     * Results constructor.
     *
     * @param string $file_path path to file
     */
    public function __construct(string $file_path)
    {
        $this->file_path = $file_path;
    }

    /**
     * Set commission fee in results array by operation id (operation index).
     *
     * @param mixed  $index      operation id (index)
     * @param string $commission fee amount in operation currency
     */
    public static function setResult($index, string $commission)
    {
        self::$results[$index] = $commission;
    }

    /**
     * Call operations methods to set results array.
     *
     * @throws \Exception
     */
    private function setResults()
    {
        $cash_in = new CashIn($this->file_path);
        $cash_in->cashIn();

        $cash_out = new CashOut($this->file_path);
        $cash_out->cashOut();
    }

    /**
     * Sorts results by operation id (index) ascending.
     * Operations with smaller id (index) dates are earlier.
     *
     * @throws \Exception
     */
    private function sortResults()
    {
        if (!isset(self::$results)) {
            throw new \Exception('No results available.');
        }

        ksort(self::$results, SORT_NUMERIC);
    }

    /**
     * Echo sorted results.
     */
    public function getResults()
    {
        try {
            $this->setResults();
            $this->sortResults();
            foreach (self::$results as $result) {
                echo $result."\n";
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
