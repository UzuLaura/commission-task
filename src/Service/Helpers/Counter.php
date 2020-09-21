<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

class Counter
{
    /**
     * Check if previous and current date are in the same week.
     *
     * @param string $previous date
     * @param string $current  date
     * @return bool
     */
    public static function compareDates(string $previous, string $current): bool
    {
        $previous_week = date('oW', strtotime($previous));
        $current_week = date('oW', strtotime($current));

        return $current_week === $previous_week;
    }

    /**
     * Determine if weekly operations count should be increased or not.
     *
     * @throws \Exception
     */
    public static function weeklyOperations(array $operation, array $client): bool
    {
        $current_operation = new Operation($operation['id'], $operation);

        if (isset($client[$operation['id'] - 1])) {
            $previous_operation = new Operation('0', $client[$operation['id'] - 1]);

            return self::compareDates($current_operation->getDate(), $previous_operation->getDate());
        }

        return false;
    }

    /**
     * Count total amount of operations in current week.
     *
     * @param array  $operation                current operation data
     * @param array  $client                   all client operations data
     * @param string $weekly_operations_amount current amount of operations current week
     *
     * @return string new amount of operations current week
     *
     * @throws \Exception
     */
    public static function weeklyOperationsAmount(array $operation, array $client, string $weekly_operations_amount): string
    {
        bcscale(100);

        $current_operation = new Operation($operation['id'], $operation);

        if (isset($client[$operation['id'] - 1])) {
            $previous_operation = new Operation(($operation['id'] - 1), $client[$operation['id'] - 1]);
            $amount = $previous_operation->getAmount();
            $currency = $previous_operation->getCurrency();

            if (self::compareDates($current_operation->getDate(), $previous_operation->getDate())) {
                $weekly_operations_amount = bcadd(Converter::convertToBaseCurrency($amount, $currency), $weekly_operations_amount);
            } else {
                $weekly_operations_amount = '0';
            }
        } else {
            $weekly_operations_amount = '0';
        }

        return $weekly_operations_amount;
    }
}
