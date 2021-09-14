<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use RuntimeException;

final class CheepDoesNotExist extends RuntimeException
{
    private function __construct(string $cheepId)
    {
        parent::__construct(
            sprintf("Cheep with ID %s does not exist", $cheepId)
        );
    }

    public static function withIdOf(CheepId $cheepId): self
    {
        return new self($cheepId->id());
    }
}
