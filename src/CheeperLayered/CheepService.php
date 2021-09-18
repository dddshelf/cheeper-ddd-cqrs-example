<?php declare(strict_types=1);

namespace CheeperLayered;

final class AuthorNotFound extends \RuntimeException
{
}

//snippet cheep-service
final class CheepService
{
    public function postCheep(string $username, string $message): Cheep
    {
        if (!$author = (new Authors())->byUsername($username)) {
            throw new AuthorNotFound($username);
        }

        $cheep = $author->compose($message);

        (new Cheeps())->add($cheep);

        return $cheep;
    }
}
//end-snippet
