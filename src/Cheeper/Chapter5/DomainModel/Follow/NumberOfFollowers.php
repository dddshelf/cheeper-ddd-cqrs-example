<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\DomainModel\Follow;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: "chapter5_followers")]
class NumberOfFollowers
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
