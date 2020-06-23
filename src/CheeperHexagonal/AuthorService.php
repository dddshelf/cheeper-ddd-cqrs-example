<?php

namespace CheeperHexagonal;

use CheeperLayered\Authors;
use CheeperLayered\Author;
use function Safe\sprintf;

//snippet author-service
class AuthorService
{
    private Authors $authors;

    public function __construct(Authors $authors)
    {
        $this->authors = $authors;
    }

    public function update(
        int $id,
        string $username,
        ?string $website,
        ?string $bio
    ): Author
    {
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
