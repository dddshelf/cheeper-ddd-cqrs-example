<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\EventHandler;

use Cheeper\Chapter7\Application\Projector\Timeline\AddCheepToTimelineProjection;
use Cheeper\Chapter7\Application\Projector\Timeline\AddCheepToTimelineProjector;
use Cheeper\Chapter7\DomainModel\Cheep\CheepPosted;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-cheep-posted-handler
final class SymfonyCheepPostedHandler implements MessageSubscriberInterface
{
    public function __construct(
        private AddCheepToTimelineProjector $projector
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield CheepPosted::class => [
            'bus' => 'event.bus',
            'method' => 'handle',
        ];
    }

    public function handle(CheepPosted $event): void
    {
        $this->projector->__invoke(
            new AddCheepToTimelineProjection(
                authorId: $event->authorId(),
                cheepId: $event->cheepId(),
                cheepMessage: $event->cheepMessage(),
                cheepDate: $event->cheepDate(),
            )
        );
    }
}
//end-snippet