<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query\CountFollowersHandlerWithRepositoriesAccess;

use Architecture\CQRS\App\Entity\Followers;
use Architecture\CQRS\Application\Query\CountFollowersResponse;
use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;

//snippet count-followers-handler
final class CountFollowersHandler
{
    private Followers $followersRepository;
    private Authors $authorsRepository;

    public function __construct(
        Followers $followersRepository,
        Authors $authorsRepository
    )
    {
        $this->followersRepository = $followersRepository;
        $this->authorsRepository = $authorsRepository;
    }

    public function __invoke(CountFollowers $query): CountFollowersResponse
    {
        $authorId = AuthorId::fromUuid($query->authorId());

        $author = $this->authorsRepository->ofId($authorId);
        $followersCount = count($this->followersRepository->ofAuthorId($authorId));

        // Other option would be with a counter method in the Repository
        // $followersCount = $this->followersRepository->countOfAuthorId($authorId));

        return new CountFollowersResponse(
            authorId: $authorId->toString(),
            authorUsername: $author->userName()->userName(),
            numberOfFollowers: $followersCount
        );
    }
}
//end-snippet