<?php

declare(strict_types=1);

namespace Cheeper\Chapter2\Hexagonal\Application;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter2\Author;
use Cheeper\Chapter2\Hexagonal\DomainModel\AuthorRepository;

//snippet author-service
final class AuthorService
{
    public function __construct(
        private AuthorRepository $authorRepository
    ) {
    }

    public function update(
        string $id,
        string $username,
        ?string $website,
        ?string $bio
    ): Author {
        $author = $this->authorRepository->ofId(AuthorId::fromString($id));

        if (null === $author) {
            throw new \RuntimeException(sprintf('%s author not found', $username));
        }

        $author->setUsername($username);
        $author->setWebsite($website);
        $author->setBio($bio);

        $this->authorRepository->add($author);

        return $author;
    }
}
//end-snippet
