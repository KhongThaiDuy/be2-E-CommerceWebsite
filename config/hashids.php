<?php

return [

    'default' => 'main',

    'connections' => [

        'main' => [
            'salt' => env('APP_KEY'), // <-- dùng APP_KEY
            'length' => 10,           // độ dài hash ID
            // 'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
        ],

        'alternative' => [
            'salt' => 'your-salt-string',
            'length' => 8,
        ],

    ],

];
