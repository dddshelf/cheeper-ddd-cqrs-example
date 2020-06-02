<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

trait RefreshDatabase
{
    /** @before */
    protected function refreshDatabase(): void
    {
        if (! method_exists(__CLASS__, 'createKernel')) {
            return;
        }

        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $process = new Process(
            ['php', 'bin/console', 'doctrine:database:drop', '--force', '--if-exists'],
            $container->getParameter('kernel.project_dir'),
            ['APP_ENV' => 'test']
        );

        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $process = new Process(
            ['php', 'bin/console', 'doctrine:database:create'],
            $container->getParameter('kernel.project_dir'),
            ['APP_ENV' => 'test']
        );

        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $process = new Process(
            ['php', 'bin/console', 'doctrine:migrations:migrate', '--no-interaction', '--all-or-nothing'],
            $container->getParameter('kernel.project_dir'),
            ['APP_ENV' => 'test']
        );

        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $process = new Process(
            ['php', 'bin/console', 'messenger:setup-transports'],
            $container->getParameter('kernel.project_dir'),
            ['APP_ENV' => 'test']
        );

        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
