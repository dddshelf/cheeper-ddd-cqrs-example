<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Projector\Author;

use App\Messenger\CommandBus;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowers;
use Cheeper\DomainModel\Follow\AuthorUnfollowed;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-author-unfollowed-handler
final class SymfonyAuthorUnfollowedHandler implements MessageSubscriberInterface
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorUnfollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handleAuthorUnfollowed'
        ];
    }

    public function handleAuthorUnfollowed(AuthorUnfollowed $event): void
    {
        $this->commandBus->handle(
            CountFollowers::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet
