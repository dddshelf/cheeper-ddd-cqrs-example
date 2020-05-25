<?php

declare(strict_types=1);

namespace Architecture\CQRS\Application\Query;

use Architecture\CQRS\App\Entity\Followers;
use Architecture\CQRS\Infrastructure\Persistence\Doctrine\DoctrineFollowersRepository;
use Doctrine\ORM\EntityManagerInterface;

//snippet count-followers-handler
final class CountFollowersHandler
{
    private DoctrineFollowersRepository $followersRepository;

    public function __construct(DoctrineFollowersRepository $followersRepository)
    {
        $this->followersRepository = $followersRepository;
    }

    public function __invoke(CountFollowers $query): ?Followers
    {
        return $this->followersRepository->ofUserId($query->userId());
    }
}
//end-snippet
