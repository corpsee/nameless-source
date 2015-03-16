<?php

return [
    'paths' => [
        'migrations' => ROOT_PATH . 'migrations/',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database'        => 'nameless',
        'nameless'                => [
            'adapter' => 'sqlite',
            'name'    => '/dbname.sqlite',
            //'host'    => 'sqlite:/dbname.sqlite',
            //'user'    => 'dbuser',
            //'pass'    => 'dbpassword',
        ],
    ],
];