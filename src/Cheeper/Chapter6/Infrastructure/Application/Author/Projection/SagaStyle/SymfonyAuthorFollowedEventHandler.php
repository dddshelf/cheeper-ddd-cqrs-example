<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Author\Projection\SagaStyle;

use Cheeper\Chapter4\DomainModel\Author\AuthorFollowed;
use Cheeper\Chapter6\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter6\Application\ProjectionBus;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet code
final class SymfonyAuthorFollowedEventHandler implements MessageSubscriberInterface
{
    public function __construct(
        private ProjectionBus $projectionBus
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorFollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handlerAuthorFollowed',
        ];
    }

    public function handlerAuthorFollowed(AuthorFollowed $event): void
    {
        $this->projectionBus->project(
            CountFollowersProjection::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet
