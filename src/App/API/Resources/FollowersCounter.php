<?php

declare(strict_types=1);

namespace App\API\Resources;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Ramsey\Uuid\UuidInterface;

#[ApiResource(collectionOperations: ['get'], itemOperations: ['get'])]
final class FollowersCounter
{
    #[ApiProperty(identifier: true)]
    public ?UuidInterface $id = null;

    public string $name;
    public string $userName;
    public int $count;
}
