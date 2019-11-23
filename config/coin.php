<?php

return [
    'btc' => [
        'name' => 'Bitcoin',
        'coin_shortcut' => 'btc',
        'coin_name' => 'Bitcoin',
        'coin_label' => 'Bitcoin',
        'wallet_id' => env('SOTATEK_BTC_WALLET_ID'),
        'confirmations' => 1,
        'conversion_rate' => '100000000'
    ],
    'eth' => [
        'name' => 'Ethereum',
        'coin_shortcut' => 'eth',
        'coin_name' => 'Ether',
        'coin_label' => 'Ether',
        'confirmations' => 10,
        'wallet_id' => env('SOTATEK_ETH_WALLET_ID'),
        'conversion_rate' => '1000000000000000000'
    ],
    'xrp' => [
        'name' => 'Ripple',
        'coin_shortcut' => 'xrp',
        'coin_name' => 'Ripple',
        'coin_label' => 'Ripple',
        'confirmations' => 2,
        'wallet_id' => env('SOTATEK_XRP_WALLET_ID'),
        'conversion_rate' => 1
    ],
    'erc20' => [
        'name' => '',
        'coin_shortcut' => 'wpc',
        'coin_name' => '',
        'coin_label' => '',
        'confirmations' => 10,
        'wallet_id' => env('SOTATEK_ETH_WALLET_ID'),
        'conversion_rate' => '1000000000000000000'
    ]
];
