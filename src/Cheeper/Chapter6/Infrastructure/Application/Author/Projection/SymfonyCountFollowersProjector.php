<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Author\Projection;

use Cheeper\AllChapters\DomainModel\Follow\AuthorFollowed;
use Cheeper\AllChapters\DomainModel\Follow\AuthorUnfollowed;
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

//        yield CountFollowers::class => [
//            'bus' => 'projection.bus',
//            'method' => 'handleProjectionRequest'
//        ];
    }

//    public function handleProjectionRequest(CountFollowers $projection): void
//    {
//        $this->appProjector->__invoke(
//            CountFollowers::ofAuthor($projection->authorId())
//        );
//    }

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
