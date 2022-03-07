<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application\Author\Event;

use Cheeper\Chapter7\Application\Author\Event\WhenNewAuthorSignedThenCreateTimelineProjectionEventHandler;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final
    class SymfonyWhenNewAuthorSignedThenCreateTimelineProjectionEventHandler
    implements MessageSubscriberInterface
{
    public function __construct(
        private WhenNewAuthorSignedThenCreateTimelineProjectionEventHandler $eventHandler,
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
            'from_transport' => 'events_async',
        ];
    }
}
