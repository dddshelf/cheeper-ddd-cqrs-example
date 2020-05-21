<?php

declare(strict_types=1);

namespace App\API\Resources;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Ramsey\Uuid\UuidInterface;

/**
 * Properties in DTOs always need to be annotated with the "@var" annotation in order to work properly. This is so
 * because API Platform relies heavily on it to guess property types.
 *
 * @psalm-suppress MissingConstructor
 *
 * @ApiResource(
 *    collectionOperations={"post"},
 *    itemOperations={"get"}
 * )
 */
final class Follower
{
    /**
     * @var UuidInterface|null
     * @ApiProperty(identifier=true)
     */
    public ?UuidInterface $id;

    /** @var string */
    public string $from;

    /** @var string */
    public string $to;
}
