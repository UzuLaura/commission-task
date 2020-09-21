<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Laura\CommissionTask\Service\Converter;
use Symfony\Component\Dotenv\Dotenv;

class ConverterTest extends TestCase
{
    /**
     * Test currency conversion to base currency.
     *
     * @param $amount
     * @param $currency
     * @param $expected
     * @dataProvider dataForCurrencyConvertingToBaseCurrency
     * @throws \Exception
     */
    public function testCurrencyConvertingToBaseCurrency(string $amount, string $currency, string $expected)
    {
        // Set up test environment
        $dotenv = new Dotenv();
        $dotenv->load('config/test/.env');

        $result = Converter::convertToBaseCurrency($amount, $currency);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data for currency conversion to base currency test.
     *
     * @return array
     */
    public function dataForCurrencyConvertingToBaseCurrency(): array
    {
        return [
            ['1200.11', 'BASE', '1200.11'],
            ['1200.11', 'FIRST', '1043.85'],
            ['1200.11', 'SECOND', '9.27'],
        ];
    }

    /**
     * Test currency conversion from base currency.
     *
     * @param $amount
     * @param $currency
     * @param $expected
     * @dataProvider dataForCurrencyConvertingFromBaseCurrency
     * @throws \Exception
     */
    public function testCurrencyConvertingFromBaseCurrency(string $amount, string $currency, string $expected)
    {
        // Set up test environment
        $dotenv = new Dotenv();
        $dotenv->load('config/test/.env');
        $result = Converter::convertFromBaseCurrency($amount, $currency);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data for currency conversion from base currency test.
     *
     * @return array
     */
    public function dataForCurrencyConvertingFromBaseCurrency(): array
    {
        return [
            ['1200.11', 'BASE', '1200.11'],
            ['1200.11', 'FIRST', '1379.77'],
            ['1200.11', 'SECOND', '155451'],
        ];
    }
}
