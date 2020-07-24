<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Cheep;

final class RecomposeCheep
{
    private string $cheepId;
    private string $message;

    public function __construct(string $cheepId, string $message)
    {
        $this->cheepId = $cheepId;
        $this->message = $message;
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
