<?php

declare(strict_types=1);

use Pixelvide\Ops\Console\PublishCommand;

final class PublishCommandSpy extends PublishCommand
{
    public bool $force = false;
    public array $calls = [];

    public function option($key = null)
    {
        return $key === 'force' ? $this->force : null;
    }

    public function call($command, array $parameters = [])
    {
        $this->calls[] = [$command, $parameters];

        return 0;
    }
}

return [
    'PublishCommand forwards the force option' => static function (): void {
        $command = new PublishCommandSpy();
        $command->force = true;

        $command->handle();

        ops_assert_same([['vendor:publish', ['--tag' => 'ops-config', '--force' => true]]], $command->calls);
    },

    'PublishCommand leaves force disabled by default' => static function (): void {
        $command = new PublishCommandSpy();

        $command->handle();

        ops_assert_same([['vendor:publish', ['--tag' => 'ops-config', '--force' => false]]], $command->calls);
    },
];
