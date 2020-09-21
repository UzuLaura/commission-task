<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

use Laura\CommissionTask\Config\Config;

class Formatter
{
    /**
     * Set number of decimal places.
     *
     * @param string $currency name of currency
     *
     * @return int number of decimal places
     */
    public function getDecimalPlaces(string $currency): int
    {
        $config = new Config();
        $config->load($_ENV['CURRENCIES_DATA_PATH']);

        return $config->get('subunits.'.$currency) >= 1000 ? 0 : 2;
    }

    /**
     * Ceil amount to smallest currency unit.
     *
     * @param string $amount   for rounding
     * @param string $currency for finding smallest currency item
     *
     * @return string rounded amount
     *
     * @throws \Exception
     */
    public function roundAmount(string $amount, string $currency): string
    {
        // Number of decimal places
        $dec_places = $this->getDecimalPlaces($currency);

        // Position of decimal points separation symbol
        $point_position = strpos($amount, '.');

        if ($point_position) {
            // Number before decimal point
            $int = substr($amount, 0, $point_position);

            // Full number after decimal point
            $dec = substr($amount, $point_position + 1);

            if ($dec_places <= 0) {
                return $dec > 0 ? (string) ($int + 1) : (string) $int;
            }

            // Number between decimal points separation symbol and decimal number limit
            $smallest_currency_item = substr($amount, ($point_position + 1), $dec_places);

            // Number after decimal number limit
            $remainder = substr($amount, ($point_position + 1 + $dec_places));

            if ($remainder <= 0) {
                return $int.'.'.$smallest_currency_item;
            } else {
                // Smallest currency item rounding logic
                if ($smallest_currency_item[strlen($smallest_currency_item) - 1] !== '9') {
                    $decimal_arr = str_split($smallest_currency_item);
                    ++$decimal_arr[strlen($smallest_currency_item) - 1];
                    $smallest_currency_item = implode($decimal_arr);
                }

                $decimal_array = array_reverse(str_split($smallest_currency_item));
                foreach ($decimal_array as $index => &$number) {
                    if ($number === '9') {
                        if (isset($decimal_array[$index + 1])) {
                            if ($decimal_array[$index + 1] !== '9') {
                                $decimal_array[$index + 1] = $decimal_array[$index + 1] + 1;
                                $number = 0;
                                break;
                            } else {
                                $decimal_array[$index + 1] = 0;
                                $number = 0;

                                if ($index === count($decimal_array) - 2) {
                                    ++$int;
                                }
                            }
                        }
                    }
                }

                $smallest_currency_item = array_reverse($decimal_array);

                return $int.'.'.implode('', $smallest_currency_item);
            }
        } else {
            $addition = str_repeat('0', $dec_places) ?: false;

            if (!$addition) {
                return (string) $amount;
            }

            if (strlen($addition) > 0) {
                return $amount.'.'.$addition;
            }
        }

        throw new \Exception('Something went wrong with formatter.');
    }
}
