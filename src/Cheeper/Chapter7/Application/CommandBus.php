<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application;

use Symfony\Component\Messenger\Envelope;

// snippet command-bus
interface CommandBus
{
    public function handle(object $command): Envelope;
}
//end-snippet
