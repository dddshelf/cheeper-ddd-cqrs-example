<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PopularCheepRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: PopularCheepRepository::class)]
class PopularCheep
{
    public function __construct(
        #[ORM\Id,
        ORM\GeneratedValue(strategy: "NONE"),
        ORM\Column(type: UuidType::NAME)]
        private UuidInterface $id,
        #[ORM\Column(type: UuidType::NAME)]
        private UuidInterface $authorId,
        #[ORM\Column(type: Types::STRING)]
        private string $message,
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
        private DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getAuthorId(): UuidInterface
    {
        return $this->authorId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
