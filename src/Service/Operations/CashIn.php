<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

class CashIn extends Data
{
    /**
     * @var array data of all cash in operations
     */
    private $data;

    /**
     * Set cash in operations data.
     *
     * @throws \Exception
     */
    public function setCashInData()
    {
        $this->data = Filter::filterData($this->getData(), 'operation_type', 'cash_in');
    }

    /**
     * Set commission results for cash in operations.
     *
     * @throws \Exception
     */
    public function cashIn()
    {
        $this->setCashInData();

        foreach ($this->data as $index => $operation) {
            try {
                $operation_data = new Operation($index, $operation);
                $commission = Commission::getCashInCommission($operation_data->getAmount(), $operation_data->getCurrency());
            } catch (\Exception $e) {
                $commission = $e->getMessage();
            }
            Results::setResult($index, $commission);
        }
    }
}
