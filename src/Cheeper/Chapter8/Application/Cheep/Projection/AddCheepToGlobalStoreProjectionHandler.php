<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\Application\Cheep\Projection;

use App\Entity\PopularCheep;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

final class AddCheepToGlobalStoreProjectionHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(AddCheepToGlobalStoreProjection $projection): void
    {
        $popularCheep = new PopularCheep(
            Uuid::fromString($projection->cheepId),
            Uuid::fromString($projection->authorId),
            $projection->cheepMessage,
            DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $projection->cheepDate)
        );

        $this->entityManager->persist($popularCheep);
        $this->entityManager->flush();
    }
}
