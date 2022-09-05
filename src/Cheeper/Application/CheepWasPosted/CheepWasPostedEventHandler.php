<?php

declare(strict_types=1);

namespace Cheeper\Application\CheepWasPosted;

use Cheeper\Application\AddCheepToFollowersTimeline\AddCheepToFollowersTimelineProjection;
use Cheeper\Application\ProjectionBus;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Cheep\CheepWasPosted;
use Cheeper\DomainModel\Follow\FollowRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CheepWasPostedEventHandler
{
    public function __construct(
        private readonly ProjectionBus $projectionBus,
        private readonly FollowRepository $followRepository,
    ) {
    }

    public function __invoke(CheepWasPosted $event): void
    {
        $followerIds = $this->followRepository->toAuthorId(
            AuthorId::fromString($event->authorId)
        );

        foreach ($followerIds as $followerId) {
            $this->projectionBus->project(
                new AddCheepToFollowersTimelineProjection(
                    $followerId->fromAuthorId()->id,
                    $event->cheepId,
                    $event->authorId,
                    $event->cheepMessage,
                    $event->cheepDate
                )
            );
        }
    }
}
