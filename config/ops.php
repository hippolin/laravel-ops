<?php

return [
    /*
     |---------------------------------------------------------------
     | Ops Path
     |---------------------------------------------------------------
     */
    'path'    => env('OPS_PATH', 'ops'),

    /*
     |---------------------------------------------------------------
     | Ops Master Switch
     |---------------------------------------------------------------
     |
     | This option may be used to disable all Ops function regardless
     | of their individual configuration
     */
    'enabled' => env('OPS_ENABLED', true),

    /*
     |---------------------------------------------------------------
     | Allowed IPs
     |---------------------------------------------------------------
     |
     | These IP ranges are allowed to access the Ops routes by default.
     | You can replace or extend this list for your environment.
     */
    'allowed_ips' => [
        '127.0.0.0/8',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
        '::1/128',
        'fc00::/7',
        'fe80::/10',
    ],

    /*
     |--------------------------------------------------------------------------
     | Ops Route Middleware
     |--------------------------------------------------------------------------
     |
     | These middleware will be assigned to every Ops route, giving you
     | the chance to add your own middleware to this list or change any of
     | the existing middleware. Or, you can simply stick with this list.
     |
     */
    'middleware' => [
        // 'web',
    ],
];
