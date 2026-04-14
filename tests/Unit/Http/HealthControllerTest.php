<?php

declare(strict_types=1);

use Pixelvide\Ops\Http\Controllers\HealthController;

final class HealthControllerProbe extends HealthController
{
    public static function formatSize(mixed $bytes): string
    {
        return parent::formatSizeUnits($bytes);
    }

    public static function memoryUsage(): float|int
    {
        return parent::getServerMemoryUsage();
    }
}

return [
    'formatSizeUnits converts byte counts' => static function (): void {
        ops_assert_same('0 bytes', HealthControllerProbe::formatSize(0));
        ops_assert_same('1 byte', HealthControllerProbe::formatSize(1));
        ops_assert_same('2 bytes', HealthControllerProbe::formatSize(2));
        ops_assert_same('1.00 KB', HealthControllerProbe::formatSize(1024));
        ops_assert_same('1.00 MB', HealthControllerProbe::formatSize(1048576));
        ops_assert_same('1.00 GB', HealthControllerProbe::formatSize(1073741824));
    },

    'getServerMemoryUsage parses free output' => static function (): void {
        $GLOBALS['__ops_shell_exec'] = "total        used        free      shared  buff/cache   available\n".
            "Mem:                1024          256          768            0            0            0\n".
            "Swap:                  0            0            0";

        ops_assert_float_same(25.0, (float) HealthControllerProbe::memoryUsage());
    },

    'health returns a normalized status payload' => static function (): void {
        $GLOBALS['__ops_env'] = [
            'APP_NAME' => 'Ops App',
            'APP_VERSION' => '1.2.3',
        ];
        $GLOBALS['__ops_config'] = [
            'app.debug' => true,
        ];
        $GLOBALS['__ops_request_ip'] = '203.0.113.10';
        $GLOBALS['__ops_sys_getloadavg'] = [1.23456, 0.0, 0.0];
        $GLOBALS['__ops_shell_exec'] = "total        used        free      shared  buff/cache   available\n".
            "Mem:                1000          500          500            1            1            1\n".
            "Swap:                  0            0            0";
        $GLOBALS['__ops_memory_usage'] = 2048;
        $GLOBALS['__ops_disk_free_space'] = 1024;
        $GLOBALS['__ops_disk_total_space'] = 2048;

        $response = (new HealthController())->health();

        ops_assert_same(200, $response['status']);
        ops_assert_same('Ok', $response['health']);
        ops_assert_same('Ops App', $response['app']['name']);
        ops_assert_same('1.2.3', $response['app']['version']);
        ops_assert_same(true, $response['app']['debug']);
        ops_assert_same('203.0.113.10', $response['request']['ip']);
        ops_assert_float_same(1.2346, (float) $response['cpu']['usage']);
        ops_assert_same('50 bytes', $response['memory']['free']);
        ops_assert_same('2.00 KB', $response['memory']['total']);
        ops_assert_same('1.00 KB', $response['disk']['free']);
        ops_assert_same('2.00 KB', $response['disk']['total']);
    },
];
