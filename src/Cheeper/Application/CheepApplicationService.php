<?php

declare(strict_types=1);

namespace Cheeper\Application;

use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepRepository;

final class CheepApplicationService
{
    public function __construct(
        private readonly CheepRepository  $cheepRepository,
    ) {
    }

    /** @psalm-param non-empty-string $id */
    public function getCheep(string $id): Cheep|null
    {
        return $this->cheepRepository->ofId(CheepId::fromString($id));
    }
}
