<?php

namespace CheeperHexagonal;

use CheeperLayered\Authors;
use CheeperLayered\Cheeps;
use CheeperLayered\Cheep;

//snippet cheep-service
class CheepService
{
    private Authors $authors;
    private Cheeps $cheeps;

    public function __construct(Authors $authors, Cheeps $cheeps)
    {
        $this->authors = $authors;
        $this->cheeps = $cheeps;
    }

    public function postCheep(string $username, string $message): Cheep
    {
        if (!$author = $this->authors->byUsername($username)) {
            throw new \RuntimeException(sprintf('%s username not found', $username));
        }
        
        $cheep = $author->compose($message);

        $this->cheeps->add($cheep);

        return $cheep;
    }
}
//end-snippet
