<?php

declare(strict_types=1);

namespace App\Dto;

use Cheeper\DomainModel\Author\Author;

final class AuthorDto
{
    /**
     * @psalm-param non-empty-string $id
     * @psalm-param non-empty-string $userName
     * @psalm-param non-empty-string $email
     * @psalm-param non-empty-string|null $name
     * @psalm-param non-empty-string|null $biography
     * @psalm-param non-empty-string|null $location
     * @psalm-param non-empty-string|null $website
     */
    public function __construct(
        public readonly string      $id,
        public readonly string      $userName,
        public readonly string      $email,
        public readonly string|null $name = null,
        public readonly string|null $biography = null,
        public readonly string|null $location = null,
        public readonly string|null $website = null,
        public readonly string|null $birthDate = null,
    ) {
    }

    public static function assembleFrom(Author $author): self
    {
        return new self(
            $author->authorId()->id,
            $author->userName()->userName,
            $author->email()->value,
            $author->name(),
            $author->biography(),
            $author->location(),
            $author->website()?->uri,
            $author->birthDate()?->date()->format(\DateTimeInterface::ATOM)
        );
    }
}
