<?php

declare(strict_types=1);


namespace Cheeper\Chapter9\Application\Author\Projection;

use Cheeper\Chapter7\Application\Projection;

final readonly class AddAuthorToListOfAuthorsWithMoreThanThousandFollowersProjection implements Projection
{
    public function __construct(
        public string $authorId
    ) {
    }
}