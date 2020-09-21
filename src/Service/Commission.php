<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

use Laura\CommissionTask\Config\Config;

class Commission
{
    /**
     * Calculate cash in commission fee.
     *
     * @param string $amount   operation amount
     * @param string $currency operation currency
     *
     * @return string commission fee
     *
     * @throws \Exception
     */
    public static function getCashInCommission(string $amount, string $currency): string
    {
        bcscale(100);

        // Load configuration
        $config = new Config();
        $config->load($_ENV['FEES_AND_LIMITS_DATA_PATH']);

        // Set up commission fee rate
        $commission_rate = (string) $config->get('fees.cash_in.standard_rate');
        $max_commission_fee = $config->get('fees.cash_in.max');

        // Set up formatter
        $formatter = new Formatter();

        $commission = bcmul($amount, $commission_rate);

        if (Converter::convertToBaseCurrency($commission, $currency) > (int) $max_commission_fee) {
            return Converter::convertFromBaseCurrency((string) $max_commission_fee, $currency);
        }

        return $formatter->roundAmount($commission, $currency);
    }

    /**
     * Calculate cash out commission fee for natural client.
     *
     * @param string $amount          operation amount
     * @param int    $operation_count number of weekly operations
     * @param string $week_amount     amount of weekly operations
     * @param string $currency        operation currency
     *
     * @return string commission fee
     *
     * @throws \Exception
     */
    public static function getNaturalCashOutCommission(string $amount, int $operation_count, string $week_amount, string $currency): string
    {
        bcscale(100);

        // Set up initial commission fee
        $commission = '0';

        // Load configuration
        $config = new Config();
        $config->load($_ENV['FEES_AND_LIMITS_DATA_PATH']);

        // Set up limits
        $weekly_amount_limit = $config->get('limits.weekly.cash_out.amount');
        $weekly_count_limit = $config->get('limits.weekly.cash_out.count');

        // Set up commission fee rate
        $commission_rate = (string) $config->get('fees.cash_out.standard_rate');

        // Set up formatter
        $formatter = new Formatter();

        $converted_amount = Converter::convertToBaseCurrency($amount, $currency);

        if ($operation_count === 1) {
            if ($converted_amount > $weekly_amount_limit) {
                $remainder = Converter::convertFromBaseCurrency(bcsub($converted_amount, (string) $weekly_amount_limit), $currency);
                $commission = bcmul($remainder, $commission_rate);
            }
        } else {
            if ($operation_count <= $weekly_count_limit) {
                $remainder = Converter::convertFromBaseCurrency(bcsub((string) $weekly_amount_limit, $week_amount), $currency);

                if ($remainder > 0) {
                    if ($amount > $remainder) {
                        $commission = bcmul(bcsub($amount, $remainder), $commission_rate);
                    }
                } else {
                    $commission = bcmul($amount, $commission_rate);
                }
            } else {
                $commission = bcmul($amount, $commission_rate);
            }
        }

        return $formatter->roundAmount($commission, $currency);
    }

    /**
     * Calculate cash out commission fee for legal client.
     *
     * @param string $amount   operation amount
     * @param string $currency operation currency
     *
     * @return string commission fee
     *
     * @throws \Exception
     */
    public static function getLegalCashOutCommission(string $amount, string $currency): string
    {
        // Load configuration
        $config = new Config();
        $config->load($_ENV['FEES_AND_LIMITS_DATA_PATH']);

        // Set up commission fee rate
        $commission_rate = (string) $config->get('fees.cash_out.standard_rate');
        $min_commission_fee = (string) $config->get('fees.cash_out.legal.min');

        // Set up formatter
        $formatter = new Formatter();

        $commission = bcmul($amount, $commission_rate);

        if (Converter::convertToBaseCurrency($commission, $currency) < $min_commission_fee) {
            return Converter::convertFromBaseCurrency($min_commission_fee, $currency);
        }

        return $formatter->roundAmount($commission, $currency);
    }
}
