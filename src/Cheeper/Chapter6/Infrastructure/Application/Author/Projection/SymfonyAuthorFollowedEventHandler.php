<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Author\Projection;

use Cheeper\Chapter4\DomainModel\Author\AuthorFollowed;
use Cheeper\Chapter6\Application\Author\Projection\CountFollowersProjectionHandler;
use Cheeper\Chapter6\Application\Author\Projection\CountFollowersProjection;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-author-followed-handler
final class SymfonyAuthorFollowedEventHandler implements MessageSubscriberInterface
{
    public function __construct(
        private CountFollowersProjectionHandler $projector
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
        $this->projector->__invoke(
            CountFollowersProjection::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet
