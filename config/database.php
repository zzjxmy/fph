<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15/11/20
 * Time: 下午2:04
 */
return [
    'fetch'         => PDO::FETCH_CLASS,

    'default'       => env('DB_CONNECTION', 'mysql'),

    'connections'   => [
        'mysql' => [
            'read'      =>  [
                'host'      =>  env('DB_FPH_READ_HOST', 'localhost'),
                'username'  =>  env('DB_FPH_READ_USERNAME', '123456'),
                'password'  =>  env('DB_FPH_READ_PASSWORD', '123456'),
            ],
            'write'     =>  [
                'host'      =>  env('DB_FPH_WRITE_HOST', 'localhost'),
                'username'  =>  env('DB_FPH_WRITE_USERNAME', '123456'),
                'password'  =>  env('DB_FPH_WRITE_PASSWORD', '123456'),
            ],
            'driver'    =>  'mysql',
            'database'  =>  env('DB_FPH', 'fph_81'),
            'charset'   =>  'utf8',
            'collation' =>  'utf8_unicode_ci',
            'prefix'    =>  'fph_',
            'strict'    =>  false,
        ],
        'mongodb'   =>  [
            'driver'    =>  'mongodb',
            'host'      =>  env('DB_MONGODB_HOST', 'localhost'),
            'port'      =>  env('DB_MONGODB_PORT', 'localhost'),
            'username'  =>  '',
            'password'  =>  '',
            'database'  =>  env('DB_MONGODB', 'localhost'),
        ],
        'mongodbCity'   =>  [
            'driver'    =>  'mongodb',
            'host'      =>  env('DB_MONGODB_HOST', 'localhost'),
            'port'      =>  env('DB_MONGODB_PORT', 'localhost'),
            'username'  =>  '',
            'password'  =>  '',
            'database'  =>  env('DB_MONGODB_CITY', 'localhost'),
        ]
    ],
    'redis'     =>  [
        'cluster'   =>  false,
        'default'   =>  [
            'host'      =>  env('DB_REDIS_HOST', 'localhost'),
            'port'      =>  6379,
            'database'  =>  env('DB_REDIS_DEFAULT', 0),
        ]
    ]
];