<?php

// Default Nameless configuration
return [
    'environment'      => 'production',
    'timezone'         => 'UTC',
    'locale'           => 'en',
    'language'         => 'en',
    'http_port'        => 80,
    'https_port'       => 443,
    'cache_path'       => APPLICATION_PATH . 'cache' . DS,
    'templates_path'   => APPLICATION_PATH . 'templates' . DS,
    'error_controller' => 'Nameless\\Core\\ErrorController::error',
    'session' => [
        'type'            => 'files', // 'memcached'
        'path'            => '',      // '127.0.0.1:11211'
        'options'         => [],
        'handler_options' => [],
    ],
];