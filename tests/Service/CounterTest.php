<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Laura\CommissionTask\Service\Counter;

class CounterTest extends TestCase
{
    /**
     * @param string $first_date
     * @param string $second_date
     * @dataProvider dataForDatesInSameWeekTest
     */
    public function testDatesInSameWeek(string $first_date, string $second_date)
    {
        $true = Counter::compareDates($first_date, $second_date);
        $this->assertTrue($true);
    }

    /**
     * @param string $first_date
     * @param string $second_date
     * @dataProvider dataForDatesNotInSameWeekTest
     */
    public function testDatesNotInSameWeek(string $first_date, string $second_date)
    {
        $false = Counter::compareDates($first_date, $second_date);
        $this->assertFalse($false);
    }

    public function dataForDatesInSameWeekTest(): array
    {
        return [
            ['2020/08/18', '18-08-2020'],
            ['2020/08/18', '20-8-2020'],
            ['2020/08/17', '23-08-2020'],
            ['2020/08/18', 'August 18th, 2020'],
            ['2019/12/30', '03-01-2020'],
            ['2020/08/16 23:59:59', '2020/08/10 00:00:00'],
        ];
    }

    public function dataForDatesNotInSameWeekTest(): array
    {
        return [
            ['2019/08/18', '18-08-2020'],
            ['2020/09/18', '20-8-2020'],
            ['2020/08/16', '23-08-2020'],
            ['2020/08/18', 'August 1st, 2020'],
            ['2019/12/30', '30-01-2020'],
            ['2020/08/17 00:00:00', '2020/08/10 00:00:00'],
        ];
    }
}
