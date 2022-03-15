<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\Application\Cheep\Event;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\Application\Cheep\Projection\AddCheepToTimelineProjection;
use Cheeper\Chapter7\Application\ProjectionBus;
use Cheeper\Chapter7\DomainModel\Cheep\CheepPosted;
use Cheeper\Chapter8\Application\Cheep\Projection\AddCheepToGlobalStoreProjection;
use Cheeper\Chapter8\DomainModel\Follow\FollowRepository;
use DateTimeImmutable;
use DateTimeInterface;

//snippet chapter8-cheep-posted-event-handler
final class CheepPostedEventHandler
{
    private const MAX_NUMBER_OF_FOLLOWERS = 1000000;

    public function __construct(
        private FollowRepository $followRepository,
        private ProjectionBus    $projectionBus,
    ) {
    }

    public function handle(CheepPosted $event): void
    {
        $authorId = AuthorId::fromString($event->authorId());
        $numberOfFollowers = $this->followRepository->numberOfFollowersFor($authorId);

        $cheepDate = DateTimeImmutable
            ::createFromFormat('Y-m-d H:i:s', $event->cheepDate())
            ->setTimezone(new \DateTimeZone('UTC'))
            ->format(DateTimeInterface::ATOM);

        if ($numberOfFollowers >= self::MAX_NUMBER_OF_FOLLOWERS) {
            $this->projectionBus->project(
                new AddCheepToGlobalStoreProjection(
                    authorId:       $authorId->toString(),
                    cheepId:        $event->cheepId(),
                    cheepMessage:   $event->cheepMessage(),
                    cheepDate: $cheepDate,
                )
            );

            return;
        }

        $follows = $this->followRepository->toAuthorId($authorId);

        foreach ($follows as $follow) {
            $this->projectionBus->project(
                new AddCheepToTimelineProjection(
                    authorId:       $follow->fromAuthorId()->toString(),
                    cheepId:        $event->cheepId(),
                    cheepMessage:   $event->cheepMessage(),
                    cheepDate: $cheepDate,
                )
            );
        }
    }
}
//end-snippet