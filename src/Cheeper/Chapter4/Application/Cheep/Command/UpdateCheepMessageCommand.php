<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\Application\Cheep\Command;

use Cheeper\Chapter4\Application\Command;

final class UpdateCheepMessageCommand implements Command
{
    public function __construct(
        private string $cheepId,
        private string $message,
    ) {
    }

    public function cheepId(): string
    {
        return $this->cheepId;
    }

    public function message(): string
    {
        return $this->message;
    }
}
