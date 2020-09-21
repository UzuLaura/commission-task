<?php

declare(strict_types=1);

namespace Laura\CommissionTask\Service;

class Filter
{
    /**
     * Get filtered data from array by index and keyword.
     *
     * @param array  $data
     * @param string $filter keyword for filtering
     *
     * @return array filtered data
     *
     * @throws \Exception
     */
    public static function filterData($data, string $index, string $filter): array
    {
        $filtered_data = [];

        if (!isset($data)) {
            throw new \Exception('No data');
        }

        foreach ($data as $operation_index => $operation) {
            if (isset($operation[$index]) && $operation[$index] === $filter) {
                $filtered_data[$operation_index] = $operation;
            }
        }

        return $filtered_data;
    }

    /**
     * Get single client operations data.
     *
     * @return array filtered client data
     *
     * @throws \Exception
     */
    public static function filterClientsData(array $data): array
    {
        $clients = [];

        foreach ($data as $index => $operation) {
            if (!isset($operation)) {
                throw new \Exception('Operation '.$index.' doesn\'t exist');
            }

            $clients[$operation['client_id']][$index] = $operation;
            $clients[$operation['client_id']][$index]['id'] = $index;
        }

        return $clients;
    }
}
