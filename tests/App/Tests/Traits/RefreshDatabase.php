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
            ['php', 'bin/console', 'doctrine:schema:drop', '--force'],
            $container->getParameter('kernel.project_dir'),
            ['APP_ENV' => 'test']
        );

        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $process = new Process(
            ['php', 'bin/console', 'doctrine:schema:create'],
            $container->getParameter('kernel.project_dir'),
            ['APP_ENV' => 'test']
        );

        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
