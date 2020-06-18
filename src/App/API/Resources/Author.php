<?php

declare(strict_types=1);

namespace App\API\Resources;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress MissingConstructor
 *
 * @ApiResource(
 *    collectionOperations={"post"},
 *    itemOperations={"get"}
 * )
 */
final class Author
{
    /**
     * @ApiProperty(identifier=true)
     */
    public ?UuidInterface $id = null;

    public string $userName;
    public string $email;
    public string $name;
    public string $biography;
    public string $location;
    public string $website;
    public DateTimeImmutable $birthDate;
}
