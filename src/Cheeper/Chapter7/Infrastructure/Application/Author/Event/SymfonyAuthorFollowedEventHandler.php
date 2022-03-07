<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application\Author\Event;

use Cheeper\Chapter7\Application\Author\Event\AuthorFollowedEventHandler;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-author-followed-event-handler
final class SymfonyAuthorFollowedEventHandler implements MessageSubscriberInterface
{
    public function __construct(
        private AuthorFollowedEventHandler $eventHandler
    ) {
    }

    public function handle(AuthorFollowed $event): void
    {
        $this->eventHandler->handle($event);
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorFollowed::class => [
            'method' => 'handle',
            'from_transport' => 'events_async',
        ];
    }
}
//end-snippet
