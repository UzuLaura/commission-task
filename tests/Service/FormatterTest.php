<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Tests\Service;

use Laura\CommissionTask\Config\Config;
use PHPUnit\Framework\TestCase;
use Laura\CommissionTask\Service\Formatter;
use Symfony\Component\Dotenv\Dotenv;

class FormatterTest extends TestCase
{
    private $formatter;

    public function setUp()
    {
        // Set up test environment
        $dotenv = new Dotenv();
        $dotenv->load('config/test/.env');

        $this->formatter = new Formatter();
    }

    /**
     * @param string $amount
     * @param string $no_decimal
     * @param string $two_decimal
     * @dataProvider dataForCurrencyRounding
     */
    public function testCurrencyRounding(string $amount, string $no_decimal, string $two_decimal)
    {
        // Set up test environment
        $dotenv = new Dotenv();
        $dotenv->load('config/test/.env');

        $config = new Config();
        $config->load($_ENV['CURRENCIES_DATA_PATH']);
        $subunits = $config->get('subunits');

        foreach ($subunits as $currency_name => $subunit) {
            $formatted_amount = $this->formatter->roundAmount($amount, $currency_name);

            if ($subunit >= 1000) {
                $this->assertEquals($no_decimal, $formatted_amount);
            } else {
                $this->assertEquals($two_decimal, $formatted_amount);
            }
        }
    }

    public function dataForCurrencyRounding(): array
    {
        return [
            ['0', '0', '0.00'],
            ['99', '99', '99.00'],
            ['0.001', '1', '0.01'],
            ['0.091', '1', '0.10'],
            ['0.999', '1', '1.00'],
            ['1.000001', '2', '1.01'],
            ['1.999991', '2', '2.00'],
            ['99.000001', '100', '99.01'],
            ['99.999999', '100', '100.00']
        ];
    }
}
