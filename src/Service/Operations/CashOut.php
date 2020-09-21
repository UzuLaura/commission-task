<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

class CashOut extends Data
{
    /**
     * @var array data of all cash out operations
     */
    private $data;

    /**
     * Set cash out operations data.
     *
     * @throws \Exception
     */
    private function setCashOutData()
    {
        $this->data = Filter::filterData($this->getData(), 'operation_type', 'cash_out');
    }

    /**
     * Set commission results for natural cash out operations.
     *
     * @throws \Exception
     */
    private function naturalCashOut()
    {
        $natural_data = Filter::filterData($this->data, 'client_type', 'natural');

        $clients = Filter::filterClientsData($natural_data);

        foreach ($clients as $client_index => $client) {
            foreach ($client as $operation_index => $operation) {
                try {
                    $operation_data = new Operation($operation_index, $operation);

                    // Set weekly operations count
                    if (!isset($weekly_operations_count)) {
                        $weekly_operations_count = 1;
                    }

                    // Set weekly operations sum
                    if (!isset($weekly_operations_amount)) {
                        $weekly_operations_amount = '0';
                    }

                    $weekly_operations_count = Counter::weeklyOperations($operation, $client) ? ($weekly_operations_count + 1) : 1;
                    $weekly_operations_amount = Counter::weeklyOperationsAmount($operation, $client, $weekly_operations_amount);
                    $amount = $operation_data->getAmount();
                    $commission = Commission::getNaturalCashOutCommission($amount, $weekly_operations_count, $weekly_operations_amount, $operation_data->getCurrency());
                } catch (\Exception $e) {
                    $commission = $e->getMessage();
                }
                Results::setResult($operation_index, $commission);
            }
        }
    }

    /**
     * Set commission results for legal cash out operations.
     *
     * @throws \Exception
     */
    private function legalCashOut()
    {
        $legal_data = Filter::filterData($this->data, 'client_type', 'legal');

        foreach ($legal_data as $index => $operation) {
            try {
                $operation_data = new Operation($index, $operation);
                $commission = Commission::getLegalCashOutCommission($operation_data->getAmount(), $operation_data->getCurrency());
            } catch (\Exception $e) {
                $commission = $e->getMessage();
            }
            Results::setResult($index, $commission);
        }
    }

    /**
     * Run all cash out methods.
     *
     * @throws \Exception
     */
    public function cashOut()
    {
        $this->setCashOutData();
        $this->naturalCashOut();
        $this->legalCashOut();
    }
}
