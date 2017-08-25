<?php
/**
 * user config file
 * @package user
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'user',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/user',
    '__files' => [
        'modules/user' => [
            'install',
            'remove',
            'update'
        ]
    ],
    '__dependencies' => [
        'core',
        'db-mysql',
        '/user-property',
        '/user-email',
        '/user-phone',
        '/user-social'
    ],
    '_services' => [
        'user' => 'User\\Service\\User'
    ],
    '_autoload' => [
        'classes' => [
            'User\\Service\\User' => 'modules/user/service/User.php',
            'User\\Model\\User' => 'modules/user/model/User.php',
            'User\\Model\\UserSession' => 'modules/user/model/UserSession.php'
        ],
        'files' => []
    ],
    
    'formatter' => [
        'user' => [
            'password' => 'delete',
            'updated'  => 'date',
            'created'  => 'date'
        ]
    ]
];