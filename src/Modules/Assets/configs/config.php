<?php

return [
    'assets' => [
        'path'       => FILE_PATH . 'compiled/',
        'less'       => true,
        'lessjs_url' => FILE_PATH_URL . 'js/less.min.js',
        'libs'       => [
            'less' => [
                'js' => FILE_PATH_URL . 'js/less.js',
            ],
        ],
        'packages' => [
            'less' => ['less'],
        ],
    ],
];
