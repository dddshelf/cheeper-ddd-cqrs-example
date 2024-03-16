<?php

declare(strict_types=1);


namespace Cheeper\Chapter9\Application\Author\Projection;

final readonly class AddAuthorToListOfAuthorsWithMoreThanThousandFollowersProjectionHandler
{
    public function __construct(
        private string $filePath
    ) {
    }

    public function __invoke(AddAuthorToListOfAuthorsWithMoreThanThousandFollowersProjection $projection): void
    {
        file_put_contents($this->filePath, $projection->authorId . PHP_EOL, FILE_APPEND);
    }
}