<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Pixelvide\Ops\Http\Middleware\AllowPrivateIps;
use Symfony\Component\HttpKernel\Exception\HttpException;

return [
    'AllowPrivateIps permits RFC1918 addresses by default' => static function (): void {
        unset($GLOBALS['__ops_config']);

        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '10.0.0.15']);
        $response = (new AllowPrivateIps())->handle($request, static fn ($nextRequest) => 'allowed');

        ops_assert_same('allowed', $response);
    },

    'AllowPrivateIps blocks public addresses by default' => static function (): void {
        unset($GLOBALS['__ops_config']);

        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '203.0.113.9']);

        try {
            (new AllowPrivateIps())->handle($request, static fn ($nextRequest) => 'allowed');
            ops_assert_true(false, 'Expected a 403 exception');
        } catch (HttpException $e) {
            ops_assert_same(403, $e->getStatusCode());
        }
    },

    'AllowPrivateIps can be customized with ops.allowed_ips' => static function (): void {
        $GLOBALS['__ops_config'] = [
            'ops.allowed_ips' => ['203.0.113.0/24'],
        ];

        $request = Request::create('/', 'GET', [], [], [], ['REMOTE_ADDR' => '203.0.113.9']);
        $response = (new AllowPrivateIps())->handle($request, static fn ($nextRequest) => 'allowed');

        ops_assert_same('allowed', $response);
    },
];
