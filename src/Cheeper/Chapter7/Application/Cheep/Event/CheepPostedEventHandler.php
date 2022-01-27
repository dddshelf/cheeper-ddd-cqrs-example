<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Cheep\Event;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\Application\Cheep\Projection\AddCheepToTimelineProjection;
use Cheeper\Chapter7\Application\ProjectionBus;
use Cheeper\Chapter7\DomainModel\Cheep\CheepPosted;
use Cheeper\Chapter7\DomainModel\Follow\Follows;
use DateTimeImmutable;
use DateTimeInterface;

//snippet cheep-posted-event-handler
final class CheepPostedEventHandler
{
    public function __construct(
        private Follows $follows,
        private ProjectionBus $projectionBus,
    ) {
    }

    public function handle(CheepPosted $event): void
    {
        $follows = $this->follows->toAuthorId(
            AuthorId::fromString($event->authorId())
        );

        foreach ($follows as $follow) {
            $this->projectionBus->project(
                new AddCheepToTimelineProjection(
                    authorId:       $follow->fromAuthorId()->toString(),
                    cheepId:        $event->cheepId(),
                    cheepMessage:   $event->cheepMessage(),
                    cheepDate:      DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $event->cheepDate())
                        ->setTimezone(new \DateTimeZone('UTC'))
                        ->format(DateTimeInterface::ATOM),
                )
            );

            // This is an example on how to straightly do it with a Redis class instance
            // $this->redis->lPush(
            //     sprintf("timelines_%s", $follow->fromAuthorId()->toString()),
            //     serialize([
            //         'cheep_id' => $event->cheepId(),
            //         'cheep_message' => $event->cheepMessage(),
            //         'cheep_date' => DateTimeImmutable
            //             ::createFromFormat('Y-m-d H:i:s', $event->cheepDate())
            //             ->setTimezone(new \DateTimeZone('UTC'))
            //             ->format(DateTimeInterface::ATOM)
            //     ])
            // );
        }
    }
}
//end-snippet
