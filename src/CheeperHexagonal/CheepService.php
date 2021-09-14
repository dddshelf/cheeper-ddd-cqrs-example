<?php

namespace CheeperHexagonal;

use CheeperLayered\Authors;
use CheeperLayered\Cheep;
use CheeperLayered\Cheeps;

//snippet cheep-service
class CheepService
{
    public function __construct(
        private Authors $authors,
        private Cheeps $cheeps,
    ) {
    }

    public function postCheep(string $username, string $message): Cheep
    {
        $author = $this->authors->byUsername($username);

        if (null === $author) {
            throw new \RuntimeException(sprintf('%s username not found', $username));
        }

        $cheep = $author->compose($message);

        $this->cheeps->add($cheep);

        return $cheep;
    }
}
//end-snippet
