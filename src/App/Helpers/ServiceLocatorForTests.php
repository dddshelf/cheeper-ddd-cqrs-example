<?php

declare(strict_types=1);

namespace App\Helpers;

use Cheeper\Infrastructure\Persistence\DoctrineOrmAuthors;
use Cheeper\Infrastructure\Persistence\DoctrineOrmCheeps;
use Doctrine\Persistence\ManagerRegistry;

final class ServiceLocatorForTests
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private DoctrineOrmAuthors $authors,
        private DoctrineOrmCheeps $cheeps
    ) { }

    public function authors(): DoctrineOrmAuthors
    {
        return $this->authors;
    }

    public function cheeps(): DoctrineOrmCheeps
    {
        return $this->cheeps;
    }

    public function doctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }
}
