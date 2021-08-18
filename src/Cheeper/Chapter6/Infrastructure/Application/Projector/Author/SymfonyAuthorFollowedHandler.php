<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Projector\Author;

use App\Messenger\CommandBus;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowers;
use Cheeper\DomainModel\Follow\AuthorFollowed;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-author-followed-handler
final class SymfonyAuthorFollowedHandler implements MessageSubscriberInterface
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorFollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handlerAuthorFollowed'
        ];
    }

    public function handlerAuthorFollowed(AuthorFollowed $event): void
    {
        $this->commandBus->handle(
            CountFollowers::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet