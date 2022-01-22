<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Query\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Redis;

//snippet count-followers-handler
final class CountFollowersHandler
{
    public function __construct(
        private Redis $redis
    ) {
    }

    public function __invoke(CountFollowers $query): CountFollowersResponse
    {
        dd($query);

        $authorId = AuthorId::fromString($query->authorId());

        $data = $this->redis->get(
            'author_followers_counter_projection:'.$authorId->toString()
        );

        if (false === $data) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        $result = json_decode($data, true, flags: JSON_THROW_ON_ERROR);

        return new CountFollowersResponse(
            authorId: $result['id'],
            authorUsername: $result['username'],
            numberOfFollowers: (int)$result['followers']
        );
    }
}
//end-snippet
