<?php

declare(strict_types=1);

namespace Cheeper\Application;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\Author;

final class AuthorService
{
    public function __construct(
        private readonly AuthorRepository $authorRepository
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