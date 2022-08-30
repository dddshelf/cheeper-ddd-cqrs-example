<?php

declare(strict_types=1);

namespace App\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

trait ProtectsInvaritans
{
    /** @psalm-assert non-empty-string $id */
    private function assertNotEmptyString(string $id, string $fieldName): void
    {
        if ('' === $id) {
            throw new BadRequestException("${fieldName} cannot be empty");
        }
    }

    private function assertValidUuid(string $id): void
    {
        if (!Uuid::isValid($id)) {
            throw new BadRequestException("The identifier ${id} is not a valid UUID.");
        }
    }
}
