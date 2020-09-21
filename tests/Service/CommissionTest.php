<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Tests\Service;

use Laura\CommissionTask\Service\Commission;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

class CommissionTest extends TestCase
{
    /**
     * @param string $amount
     * @param string $currency
     * @param string $expected
     * @throws \Exception
     * @dataProvider dataForCashInCommissionTest
     */
    public function testCashInCommission(string $amount, string $currency, string $expected)
    {
        // Set up test environment
        $dotenv = new Dotenv();
        $dotenv->load('config/test/.env');

        $result = Commission::getCashInCommission($amount, $currency);
        $this->assertEquals($expected, $result);
    }

    /**
     * @param string $amount
     * @param int $operation_count
     * @param string $week_amount
     * @param string $currency
     * @param string $expected
     * @throws \Exception
     * @dataProvider dataForNaturalCashOutCommissionTest
     */
    public function testNaturalCashOutCommission(string $amount, int $operation_count, string $week_amount, string $currency, string $expected)
    {
        // Set up test environment
        $dotenv = new Dotenv();
        $dotenv->load('config/test/.env');

        $result = Commission::getNaturalCashOutCommission($amount, $operation_count, $week_amount, $currency);
        $this->assertEquals($expected, $result);
    }

    /**
     * @param string $amount
     * @param string $currency
     * @param string $expected
     * @throws \Exception
     * @dataProvider dataForLegalCashOutCommissionTest
     */
    public function testLegalCashOutCommission(string $amount, string $currency, string $expected)
    {
        // Set up test environment
        $dotenv = new Dotenv();
        $dotenv->load('config/test/.env');

        $result = Commission::getLegalCashOutCommission($amount, $currency);
        $this->assertEquals($expected, $result);
    }

    public function dataForCashInCommissionTest(): array
    {
        return [
            ['50000', 'BASE', '5.00'],
            ['50000', 'FIRST', '5.75'],
            ['5000000', 'SECOND', '648'],

            ['500', 'BASE', '0.15'],
            ['500', 'FIRST', '0.15'],
            ['500', 'SECOND', '1'],
        ];
    }

    public function dataForNaturalCashOutCommissionTest(): array
    {
        return [
            ['100', 1, '0', 'BASE', '0.00'],
            ['1000', 1, '0', 'BASE', '0.00'],
            ['1010', 1, '0', 'BASE', '0.03'],
            ['100', 3, '100', 'BASE', '0.00'],
            ['100', 4, '100', 'BASE', '0.30'],
            ['1000', 4, '100', 'BASE', '3.00'],
            ['1000', 4, '1000', 'BASE', '3.00'],
            ['100', 2, '1000', 'BASE', '0.30'],
            ['100', 2, '910', 'BASE', '0.03'],
            ['1000', 2, '100', 'BASE', '0.30'],
            ['1000', 2, '1000', 'BASE', '3.00'],
            ['10', 2, '900', 'BASE', '0.00'],

            ['100', 1, '0', 'FIRST', '0.00'],
            ['1149.70', 1, '0', 'FIRST', '0.00'],
            ['1200', 1, '0', 'FIRST', '0.16'],
            ['100', 3, '100', 'FIRST', '0.00'],
            ['100', 4, '100', 'FIRST', '0.30'],
            ['1000', 4, '100', 'FIRST', '3.00'],
            ['1000', 4, '1000', 'FIRST', '3.00'],
            ['100', 2, '1000', 'FIRST', '0.30'],
            ['100', 2, 910, 'FIRST', '0.00'],
            ['100', 2, '920', 'FIRST', '0.03'],
            ['1100', 2, '100', 'FIRST', '0.20'],
            ['1000', 2, '1000', 'FIRST', '3.00'],
            ['10', 2, 900, 'FIRST', '0.00'],

            ['100', 1, '0', 'SECOND', '0'],
            ['129530', 1, '0', 'SECOND', '0'],
            ['130000', 1, '0', 'SECOND', '2'],
            ['100', 3, '100', 'SECOND', '0'],
            ['100', 4, '100', 'SECOND', '1'],
            ['1000', 4, '100', 'SECOND', '3'],
            ['1000', 4, '1000', 'SECOND', '3'],
            ['100', 2, '1000', 'SECOND', '1'],
            ['10000', 2, '980', 'SECOND', '23'],
            ['129530', 2, '100', 'SECOND', '39'],
            ['129530', 2, '1000', 'SECOND', '389'],
            ['1296', 2, '900', 'SECOND', '0'],
        ];
    }

    public function dataForLegalCashOutCommissionTest(): array
    {
        return [
            ['50', 'BASE', '0.50'],
            ['50', 'FIRST', '0.58'],
            ['500', 'SECOND', '65'],

            ['5000', 'BASE', '15.00'],
            ['5000', 'FIRST', '15.00'],
            ['50000', 'SECOND', '150'],
        ];
    }
}
