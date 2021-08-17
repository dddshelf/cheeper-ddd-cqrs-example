<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Projector\Author;

use Cheeper\Chapter6\Application\Projector\Author\CountFollowerProjector;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowers;
use Cheeper\DomainModel\Follow\AuthorFollowed;
use Cheeper\DomainModel\Follow\AuthorUnfollowed;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-projector-count-followers
final class SymfonyCountFollowerProjector implements MessageSubscriberInterface
{
    public function __construct(
        private CountFollowerProjector $appProjector
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorFollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handlerAuthorFollowed'
        ];

        yield AuthorUnfollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handleAuthorUnfollowed'
        ];
    }

    public function handlerAuthorFollowed(AuthorFollowed $event): void
    {
        $this->project($event->toAuthorId());
    }

    public function handleAuthorUnfollowed(AuthorUnfollowed $event): void
    {
        $this->project($event->toAuthorId());
    }

    private function project(string $authorId): void
    {
        $this->appProjector->__invoke(
            CountFollowers::ofAuthor($authorId)
        );
    }
}
//end-snippet
