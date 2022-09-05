<?php

declare(strict_types=1);

namespace Cheeper\Application;

/** @template T of Command */
interface CommandBus
{
    /** @psalm-param T $command */
    public function handle(Command $command): void;
}
