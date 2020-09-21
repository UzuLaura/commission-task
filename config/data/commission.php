<?php
/* -------------------------------------------------------------------
    This file is for setting and adding commission fees and limits.
    Current data is being used in code, you can change data values
    and add new data.
    Changing current data index names will affect output results.
------------------------------------------------------------------- */
return [
    'fees' => [
        'cash_in' => [
            'standard_rate' => '0.0003',
            'max' => '5'
        ],
        'cash_out' => [
            'standard_rate' => '0.003',
            'legal' => [
                'min' => '0.5'
            ]
        ]
    ],
    'limits' => [
        'weekly' => [
            'cash_out' => [
                'amount' => '1000',
                'count' => '3'
            ]
        ]
    ]
];