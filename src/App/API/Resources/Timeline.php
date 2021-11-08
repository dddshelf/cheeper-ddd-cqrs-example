<?php

declare(strict_types=1);

namespace App\API\Resources;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Ramsey\Uuid\UuidInterface;

#[ApiResource(collectionOperations: [], itemOperations: ['get'])]
final class Timeline
{
    #[ApiProperty(identifier: true)]
    public ?UuidInterface $id = null;

    public array $cheeps;
}