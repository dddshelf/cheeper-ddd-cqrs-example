<?php

declare(strict_types=1);

namespace CheeperHexagonal;

use CheeperLayered\Author;
use CheeperLayered\Authors;

//snippet author-service
final class AuthorService
{
    public function __construct(
        private Authors $authors
    ) {
    }

    public function update(
        int $id,
        string $username,
        ?string $website,
        ?string $bio
    ): Author {
        $author = $this->authors->byId($id);

        if (null === $author) {
            throw new \RuntimeException(sprintf('%s author not found', $username));
        }

        $author->setUsername($username);
        $author->setWebsite($website);
        $author->setBio($bio);

        $this->authors->save($author);

        return $author;
    }
}
//end-snippet
