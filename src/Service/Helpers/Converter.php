<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

use Laura\CommissionTask\Config\Config;

class Converter
{
    /**
     * Find and return exchange rate from config file by currency name.
     *
     * @param string $amount   for error handling
     * @param string $currency name for getting exchange rate
     *
     * @return string exchange rate
     *
     * @throws \Exception
     */
    public static function getExchangeRate(string $amount, string $currency): string
    {
        $config = new Config();
        $config->load($_ENV['CURRENCIES_DATA_PATH']);

        if ($currency === $config->get('base')) {
            return '1';
        }

        $exchange_rate = (string) $config->get('rates.'.$currency, false);

        if (!$exchange_rate) {
            throw new \Exception('Currency - '.$currency.' is unsupported. Check if currency is listed in \'.\config\rates.php\'. Currency names are case sensitive.');
        }

        if (!is_numeric($exchange_rate)) {
            throw new \Exception('Wrongly set exchange rate: '.$exchange_rate.'. Exchange rate must be valid number.');
        }

        if (!is_numeric($amount)) {
            throw new \Exception('Cannot convert '.$amount.'. Amount must be in number format.');
        }

        return (string) $exchange_rate;
    }

    /**
     * Convert given amount to amount in base currency.
     *
     * @param string $amount   for conversion
     * @param string $currency of initial amount
     *
     * @return string converted amount
     *
     * @throws \Exception
     */
    public static function convertToBaseCurrency(string $amount, string $currency): string
    {
        bcscale(100);

        self::getExchangeRate($amount, $currency);

        $exchange_rate = self::getExchangeRate($amount, $currency);
        $formatter = new Formatter();

        $config = new Config();
        $config->load($_ENV['CURRENCIES_DATA_PATH']);

        $base_currency = $config->get('base');

        return $formatter->roundAmount(bcdiv($amount, $exchange_rate), $base_currency);
    }

    /**
     * Convert given base currency amount to other currency amount.
     *
     * @param string $amount   of base currency for conversion
     * @param string $currency to convert to
     *
     * @return string converted amount
     *
     * @throws \Exception
     */
    public static function convertFromBaseCurrency(string $amount, string $currency): string
    {
        bcscale(100);

        self::getExchangeRate($amount, $currency);

        $exchange_rate = self::getExchangeRate($amount, $currency);
        $formatter = new Formatter();

        return $formatter->roundAmount(bcmul($amount, $exchange_rate), $currency);
    }
}
