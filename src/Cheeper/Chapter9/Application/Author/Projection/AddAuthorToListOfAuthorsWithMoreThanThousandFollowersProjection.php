<?php

declare(strict_types=1);


namespace Cheeper\Chapter9\Application\Author\Projection;

final readonly class AddAuthorToListOfAuthorsWithMoreThanThousandFollowersProjection
{
    public function __construct(
        public string $authorId
    ) {
    }
}