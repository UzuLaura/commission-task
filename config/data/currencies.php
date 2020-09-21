<?php
/* ----------------------------------------------------------------------
    This file is for setting and adding currency exchange rates
    and currencies.
    Base currency tells which currency is currently being used
    as base currency.
    Base currency rate is set as 1 by data.
---------------------------------------------------------------------- */
return [
    'base' => 'EUR',
    'rates' => [
        'USD' => '1.1497',
        'JPY' => '129.53',
    ],
    /* ------------------------------------------------------------------
        For 2 numbers after decimal point set value of 100 or don't set
        any value.
        For 0 numbers after decimal point set 1000.
    ------------------------------------------------------------------ */
    'subunits' => [
        'USD' => 100,
        'JPY' => 1000,
    ]
];