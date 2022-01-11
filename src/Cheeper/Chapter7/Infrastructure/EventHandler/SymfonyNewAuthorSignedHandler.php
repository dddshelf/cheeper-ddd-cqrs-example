<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application\Projector\Author;

use Cheeper\Chapter6\Application\Projector\Author\CountFollowerProjector;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowers;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-new-author-signed-handler
final class SymfonyNewAuthorSignedHandler implements MessageSubscriberInterface
{
    public function __construct(
        private CountFollowerProjector $projector
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield NewAuthorSigned::class => [
            'bus' => 'event.bus',
            'method' => 'handlerNewAuthorSigned',
        ];
    }

    public function handlerNewAuthorSigned(NewAuthorSigned $event): void
    {
        $this->projector->__invoke(
            CountFollowers::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet