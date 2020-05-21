<?php

declare(strict_types=1);

namespace App\API\Resources;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

/**
 * Properties in DTOs always need to be annotated with the @var annotation in order to work properly. This is so because
 * API Platform relies heavily on it to guess property types.
 *
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
     * @var UuidInterface|null
     * @ApiProperty(identifier=true)
     */
    public ?UuidInterface $id = null;

    /** @var string  */
    public string $userName;

    /** @var string  */
    public string $name;

    /** @var string  */
    public string $biography;

    /** @var string  */
    public string $location;

    /** @var string  */
    public string $website;

    /** @var DateTimeImmutable */
    public DateTimeImmutable $birthDate;
}
