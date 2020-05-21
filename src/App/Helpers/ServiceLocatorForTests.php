<?php

declare(strict_types=1);

namespace App\Helpers;

use Cheeper\Infrastructure\Persistence\DoctrineOrmAuthors;
use Cheeper\Infrastructure\Persistence\DoctrineOrmCheeps;
use Doctrine\Persistence\ManagerRegistry;

final class ServiceLocatorForTests
{
    private DoctrineOrmAuthors $authors;
    private DoctrineOrmCheeps $cheeps;
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine, DoctrineOrmAuthors $authors, DoctrineOrmCheeps $cheeps)
    {
        $this->authors = $authors;
        $this->cheeps = $cheeps;
        $this->doctrine = $doctrine;
    }

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
