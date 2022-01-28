<?php

declare(strict_types=1);

namespace Cheeper\Chapter2;

use Cheeper\Chapter2\Layered\Authors;
use Cheeper\Chapter2\Layered\CheepDAO;

//snippet cheep-service
final class CheepService
{
    public function __construct(
        private Authors  $authors,
        private CheepDAO $cheepDao,
    ) {
    }

    public function postCheep(string $username, string $message): Cheep
    {
        $author = $this->authors->byUsername($username);

        if (null === $author) {
            throw new \RuntimeException(sprintf('%s username not found', $username));
        }

        $cheep = $author->compose($message);

        $this->cheepDao->add($cheep);

        return $cheep;
    }
}
//end-snippet
