<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\Infrastructure\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter9\DomainModel\Author\AuthorsWithMoreThanAThousandOfFollowers;

final readonly class TextFileAuthorsWithMoreThanAThousandOfFollowers implements AuthorsWithMoreThanAThousandOfFollowers
{
    public function __construct(
        private string $filePath
    ) {
    }

    public function __invoke(): array
    {
        return array_map(
            fn(string $authorId) => AuthorId::fromString($authorId),
            file($this->filePath)
        );
    }
}