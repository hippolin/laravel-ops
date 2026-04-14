<?php

declare(strict_types=1);

use Pixelvide\Ops\Console\InstallCommand;

final class InstallCommandSpy extends InstallCommand
{
    public array $comments = [];
    public array $infos = [];
    public array $calls = [];

    public function comment($string, $verbosity = null)
    {
        $this->comments[] = $string;
    }

    public function info($string, $verbosity = null)
    {
        $this->infos[] = $string;
    }

    public function callSilent($command, array $parameters = [])
    {
        $this->calls[] = [$command, $parameters];

        return 0;
    }
}

return [
    'InstallCommand publishes the ops config' => static function (): void {
        $command = new InstallCommandSpy();

        $command->handle();

        ops_assert_same(['Publishing Ops Configuration...'], $command->comments);
        ops_assert_same([['vendor:publish', ['--tag' => 'ops-config']]], $command->calls);
        ops_assert_same(['Ops scaffolding installed successfully.'], $command->infos);
    },
];
