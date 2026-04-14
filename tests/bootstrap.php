<?php

declare(strict_types=1);

namespace {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

    require __DIR__.'/../vendor/autoload.php';

    if (!class_exists('Request', false)) {
        class Request
        {
            public static function ip(): string
            {
                return $GLOBALS['__ops_request_ip'] ?? '127.0.0.1';
            }
        }
    }

    function ops_assert_true(bool $condition, string $message = 'Assertion failed'): void
    {
        if (!$condition) {
            throw new RuntimeException($message);
        }
    }

    function ops_assert_same(mixed $expected, mixed $actual, string $message = ''): void
    {
        if ($expected !== $actual) {
            $details = $message !== '' ? $message.' ' : '';
            throw new RuntimeException($details.'Expected '.var_export($expected, true).' but got '.var_export($actual, true));
        }
    }

    function ops_assert_float_same(float $expected, float $actual, float $delta = 0.0001, string $message = ''): void
    {
        if (abs($expected - $actual) > $delta) {
            $details = $message !== '' ? $message.' ' : '';
            throw new RuntimeException($details.'Expected '.$expected.' ±'.$delta.' but got '.$actual);
        }
    }
}

namespace Pixelvide\Ops\Http\Controllers {
    function env(string $key, mixed $default = null): mixed
    {
        return $GLOBALS['__ops_env'][$key] ?? $default;
    }

    function config(string $key, mixed $default = null): mixed
    {
        return $GLOBALS['__ops_config'][$key] ?? $default;
    }

    function shell_exec(string $command): mixed
    {
        return $GLOBALS['__ops_shell_exec'] ?? null;
    }

    function sys_getloadavg(): mixed
    {
        return $GLOBALS['__ops_sys_getloadavg'] ?? [0.0, 0.0, 0.0];
    }

    function memory_get_usage(bool $real_usage = false): int
    {
        return $GLOBALS['__ops_memory_usage'] ?? 0;
    }

    function disk_free_space(string $directory): int|float|false
    {
        return $GLOBALS['__ops_disk_free_space'] ?? 0;
    }

    function disk_total_space(string $directory): int|float|false
    {
        return $GLOBALS['__ops_disk_total_space'] ?? 0;
    }
}

namespace Pixelvide\Ops\Http\Middleware {
    function config(string $key, mixed $default = null): mixed
    {
        return $GLOBALS['__ops_config'][$key] ?? $default;
    }
}
