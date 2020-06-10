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
     * This is really not used. It's a convenience property placed here just for API Platform to be
     * able to generate IRIs. Because, by design, API Platform does not support composite identifiers
     * on resources.
     *
     * @ApiProperty(identifier=true)
     */
    public string $id;

    public string $from;
    public string $to;

    public function getId(): string
    {
        return $this->from . '-' . $this->to;
    }
}
