<?php

declare(strict_types=1);

namespace App\Dto;

use Cheeper\DomainModel\Author\Author;

final class AuthorDto
{
    public function __construct(
        public readonly string  $id,
        public readonly string  $userName,
        public readonly string  $email,
        public readonly ?string $name = null,
        public readonly ?string $biography = null,
        public readonly ?string $location = null,
        public readonly ?string $website = null,
        public readonly ?string $birthDate = null,
    )
    {
    }

    public static function assembleFrom(Author $author): self
    {
        return new self(
            $author->authorId()->toString(),
            $author->userName()->userName(),
            $author->email()->value(),
            $author->name(),
            $author->biography(),
            $author->location(),
            $author->website()?->toString(),
            $author->birthDate()?->date()->format(\DateTimeInterface::ATOM)
        );
    }
}