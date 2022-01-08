<?php

declare(strict_types=1);

namespace Cheeper\Chapter2;

//snippet author-service
use Cheeper\Chapter2\Layered\Authors;

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
