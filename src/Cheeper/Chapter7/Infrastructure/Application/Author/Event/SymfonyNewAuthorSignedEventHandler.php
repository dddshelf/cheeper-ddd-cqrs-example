<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application\Author\Event;

use Cheeper\Chapter7\Application\Author\Event\NewAuthorSignedEventHandler;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-new-author-signed-event-handler
final class SymfonyNewAuthorSignedEventHandler implements MessageSubscriberInterface
{
    public function __construct(
        private NewAuthorSignedEventHandler $eventHandler
    ) {
    }

    public function handle(NewAuthorSigned $event): void
    {
        $this->eventHandler->handle($event);
    }

    public static function getHandledMessages(): iterable
    {
        yield NewAuthorSigned::class => [
            'method' => 'handle',
            'from_transport' => 'chapter7_events',
        ];
    }
}
//end-snippet
