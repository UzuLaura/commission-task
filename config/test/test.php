<?php
/* -------------------------------------------------------------------
    Data in this file is being used for Unit Testing
------------------------------------------------------------------- */
return [
    'base' => 'BASE',
    'rates' => [
        'FIRST' => '1.1497',
        'SECOND' => '129.53',
    ],
    'subunits' => [
        'FIRST' => 100,
        'SECOND' => 1000
    ],
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