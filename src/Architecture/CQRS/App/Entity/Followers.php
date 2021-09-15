<?php

declare(strict_types=1);

namespace Architecture\CQRS\App\Entity;

use Architecture\CQRS\Infrastructure\Persistence\Doctrine\DoctrineFollowersRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: DoctrineFollowersRepository::class)]
#[ORM\Table(name: "architecture_followers")]
class Followers
{
    public function __construct(
        #[ORM\Column(type: "uuid_binary")]
        #[ORM\GeneratedValue(strategy: "NONE")]
        #[ORM\Id]
        private UuidInterface $userId,

        #[ORM\Column(type: "integer")]
        private int $followers
    ) {
    }

    public function userId(): UuidInterface
    {
        return $this->userId;
    }

    public function followers(): int
    {
        return $this->followers;
    }
}
