<?php
return [
    'transport' => 'rabbitmq',

    'subscriber_config' => [
        'main_queue_name' => env('LDE_MAIN_QUEUE_NAME'),
        'subscribe_to' => [
            //
        ],
    ],
    'transport_config' => [
        'confirm_delivery' => env('LDE_CONFIRM_DELIVERY', false),
        'queue_errors' => env('LDE_RABBITMQ_QUEUE_ERRORS', true),
        'user_hostname_as_tag' => env('LDE_RABBITMQ_USE_HOSTNAME_AS_TAG', false)
    ],
    'connections' => [
        'rabbitmq' => [
            'host' => env('LDE_RABBITMQ_HOST', '127.0.0.1'),
            'port' => env('LDE_RABBITMQ_PORT', 5672),
            'vhost' => env('LDE_RABBITMQ_VHOST', '/'),
            'user' => env('LDE_RABBITMQ_USER', 'guest'),
            'password' => env('LDE_RABBITMQ_PASSWORD', 'guest'),
        ]
    ]
];