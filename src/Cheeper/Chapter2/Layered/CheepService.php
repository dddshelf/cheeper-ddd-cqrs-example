<?php
declare(strict_types=1);

namespace Cheeper\Chapter2\Layered;

use Cheeper\Chapter2\Cheep;

//snippet cheep-service
final class CheepService
{
    public function postCheep(string $username, string $message): Cheep
    {
        if (!$author = (new AuthorDAO())->byUsername($username)) {
            throw new AuthorNotFound($username);
        }

        $cheep = $author->compose($message);

        (new CheepDAO())->add($cheep);

        return $cheep;
    }
}
//end-snippet
