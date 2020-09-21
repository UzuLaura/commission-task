<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

class Operation
{
    /**
     * @var string|int (index)
     */
    private $id;
    /**
     * @var string
     */
    private $date;
    /**
     * @var string|int
     */
    private $client_id;
    /**
     * @var string
     */
    private $client_type;
    /**
     * @var string
     */
    private $operation_type;
    /**
     * @var string
     */
    private $amount;
    /**
     * @var string
     */
    private $currency;

    /**
     * Operation constructor.
     *
     * @param $operation_id
     *
     * @throws \Exception
     */
    public function __construct($operation_id, array $operation)
    {
        $this->id = $operation_id;
        $this->setDate($operation);
        $this->setClientID($operation);
        $this->setClientType($operation);
        $this->setOperationType($operation);
        $this->setAmount($operation);
        $this->setCurrency($operation);
    }

    /**
     * Set operation date.
     * Display error if operation date doesn't exist.
     *
     * @param $operation
     *
     * @throws \Exception
     */
    private function setDate(array $operation)
    {
        $date = $operation['date'] ?? null;

        if (!isset($date)) {
            throw new \Exception('Operation with ID:'.$this->id.' date is not set.');
        }

        if (!strtotime($date)) {
            throw new \Exception('Operation with ID:'.$this->id.' date is not in proper date format.');
        }

        $this->date = $date;
    }

    /**
     * Set operation client id.
     * Display error if client id doesn't exist.
     *
     * @param $operation
     *
     * @throws \Exception
     */
    private function setClientID(array $operation)
    {
        $client_id = $operation['client_id'] ?? null;

        if (!isset($client_id)) {
            throw new \Exception('Operation with ID:'.$this->id.' client ID is not set.');
        }

        $this->client_id = $client_id;
    }

    /**
     * Set operation client type.
     * Display error if client type doesn't exist.
     *
     * @param $operation
     *
     * @throws \Exception
     */
    private function setClientType(array $operation)
    {
        $client_type = $operation['client_type'] ?? null;

        if (!isset($client_type)) {
            throw new \Exception('Operation with ID:'.$this->id.' client type is not set.');
        }

        $this->client_type = $client_type;
    }

    /**
     * Set operation type.
     * Display error if operation type doesn't exist.
     *
     * @param $operation
     *
     * @throws \Exception
     */
    private function setOperationType(array $operation)
    {
        $operation_type = $operation['operation_type'] ?? null;

        if (!isset($operation_type)) {
            throw new \Exception('Operation with ID:'.$this->id.' type is not set.');
        }

        $this->operation_type = $operation_type;
    }

    /**
     * Set operation amount.
     * Display error if operation amount doesn't exist.
     *
     * @param $operation
     *
     * @throws \Exception
     */
    private function setAmount(array $operation)
    {
        $amount = $operation['amount'] ?? null;

        if (!isset($amount)) {
            throw new \Exception('Operation with ID:'.$this->id.' amount is not set.');
        }

        if (!is_numeric($amount)) {
            throw new \Exception('Operation with ID:'.$this->id.' amount is not in number format.');
        }

        $this->amount = $amount;
    }

    /**
     * Set operation currency.
     * Display error if operation currency doesn't exist.
     *
     * @param $operation
     *
     * @throws \Exception
     */
    private function setCurrency(array $operation)
    {
        $currency = $operation['currency'] ?? null;

        if (!isset($currency)) {
            throw new \Exception('Operation with ID:'.$this->id.' currency is not set.');
        }

        $this->currency = $currency;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return int|string
     */
    public function getClientID()
    {
        return $this->client_id;
    }

    public function getClientType(): string
    {
        return $this->client_id;
    }

    public function getOperationType(): string
    {
        return $this->operation_type;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
