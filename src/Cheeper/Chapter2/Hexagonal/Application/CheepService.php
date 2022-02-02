<?php

declare(strict_types=1);

namespace Cheeper\Chapter2\Hexagonal\Application;

use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter2\Cheep;
use Cheeper\Chapter2\Hexagonal\DomainModel\AuthorRepository;
use Cheeper\Chapter2\Hexagonal\DomainModel\CheepRepository;

//snippet cheep-service
final class CheepService
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private CheepRepository $cheepRepository,
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
//end-snippet
