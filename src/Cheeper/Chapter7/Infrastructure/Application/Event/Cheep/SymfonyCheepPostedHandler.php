<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application\Event\Cheep;

use Cheeper\Chapter7\Application\Event\Cheep\CheepPostedEventHandler;
use Cheeper\Chapter7\DomainModel\Cheep\CheepPosted;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-cheep-posted-event-handler
final class SymfonyCheepPostedHandler implements MessageSubscriberInterface
{
    public function __construct(
        private CheepPostedEventHandler $eventHandler
    ) {
    }

    public function handle(CheepPosted $event): void
    {
        $this->eventHandler->handle($event);
    }

    public static function getHandledMessages(): iterable
    {
        yield CheepPosted::class => [
            'method' => 'handle',
            'from_transport' => 'chapter7_events',
        ];
    }
}
//end-snippet
