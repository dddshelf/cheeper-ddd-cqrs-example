<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\Application\Author\Command;

use Cheeper\Chapter9\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter9\DomainModel\Author\AuthorsWithMoreThanAThousandOfFollowers;

final readonly class UpgradeAuthorsWithMoreThanAThousandOfFollowersCommandHandler
{
    public function __construct(
        private AuthorRepository $authors,
        private AuthorsWithMoreThanAThousandOfFollowers $authorsWithMoreThanAThousandOfFollowers
    ) {
    }

    public function __invoke(UpgradeAuthorsWithMoreThanAThousandOfFollowersCommand $command): void
    {
        $authorsWithMoreThanAThousandOfFollowers = $this->authorsWithMoreThanAThousandOfFollowers;
        $authorIds = $authorsWithMoreThanAThousandOfFollowers();

        foreach ($authorIds as $authorId) {
            $author = $this->authors->ofId($authorId);
            $author->upgrade();
            $this->authors->save($author);
        }
    }
}