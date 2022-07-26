<?php

declare(strict_types=1);

namespace Cheeper\Application;

use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Cheeper\DomainModel\Cheep\Cheep;

final class CheepService
{
    public function __construct(
        private readonly AuthorRepository $authorRepository,
        private readonly CheepRepository  $cheepRepository,
    ) {
    }

    public function postCheep(string $username, string $message): Cheep
    {
        $author = $this->authorRepository->ofUserName(UserName::pick($username));

        if (null === $author) {
            throw new \RuntimeException(sprintf('%s username not found', $username));
        }

        $cheep = $author->compose($message);

        $this->cheepRepository->add($cheep);

        return $cheep;
    }
}
