<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Author\Projection;

use Cheeper\Chapter4\DomainModel\Author\AuthorFollowed;
use Cheeper\Chapter4\DomainModel\Author\AuthorUnfollowed;
use Cheeper\Chapter6\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter6\Application\Author\Projection\CountFollowersProjectionHandler;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-projector-count-followers
final class SymfonyCountFollowersProjector implements MessageSubscriberInterface
{
    public function __construct(
        private CountFollowersProjectionHandler $projector
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorUnfollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handleAuthorUnfollowed',
        ];

        yield AuthorFollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handleAuthorFollowed',
        ];
    }

    public function handleAuthorFollowed(AuthorFollowed $event): void
    {
        $this->projector->__invoke(
            CountFollowersProjection::ofAuthor($event->toAuthorId())
        );
    }

    public function handleAuthorUnfollowed(AuthorUnfollowed $event): void
    {
        $this->projector->__invoke(
            CountFollowersProjection::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet
