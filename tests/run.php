<?php

declare(strict_types=1);

require __DIR__.'/bootstrap.php';

$testFiles = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__.'/Unit', FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), 'Test.php')) {
        $testFiles[] = $file->getPathname();
    }
}

sort($testFiles);

$tests = [];
foreach ($testFiles as $file) {
    $suite = require $file;
    if (is_array($suite)) {
        $tests += $suite;
    }
}

$failures = 0;
foreach ($tests as $name => $test) {
    try {
        $test();
        echo ".";
    } catch (Throwable $e) {
        $failures++;
        fwrite(STDERR, PHP_EOL."FAIL: {$name}".PHP_EOL.$e->getMessage().PHP_EOL);
    }
}

echo PHP_EOL.sprintf('Ran %d tests, %d failed.', count($tests), $failures).PHP_EOL;

exit($failures === 0 ? 0 : 1);
