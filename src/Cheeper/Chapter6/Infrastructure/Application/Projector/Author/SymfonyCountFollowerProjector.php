<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Projector\Author;

use Cheeper\Chapter6\Application\Projector\Author\CountFollowerProjector;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowers;
use Cheeper\AllChapters\DomainModel\Follow\AuthorFollowed;
use Cheeper\AllChapters\DomainModel\Follow\AuthorUnfollowed;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-projector-count-followers
final class SymfonyCountFollowerProjector implements MessageSubscriberInterface
{
    public function __construct(
        private CountFollowerProjector $projector
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorUnfollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handleAuthorUnfollowed'
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
            CountFollowers::ofAuthor($event->toAuthorId())
        );
    }

    public function handleAuthorUnfollowed(AuthorUnfollowed $event): void
    {
        $this->projector->__invoke(
            CountFollowers::ofAuthor($event->toAuthorId())
        );
    }

}
//end-snippet
