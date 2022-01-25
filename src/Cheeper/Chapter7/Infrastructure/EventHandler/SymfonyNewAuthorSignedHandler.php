<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\EventHandler;

use Cheeper\Chapter7\Application\Projector\Author\CreateFollowersCounterProjection;
use Cheeper\Chapter7\Application\Projector\Author\CreateFollowersCounterProjectionProjector;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-new-author-signed-handler
final class SymfonyNewAuthorSignedHandler implements MessageSubscriberInterface
{
    public function __construct(
        private CreateFollowersCounterProjectionProjector $projector
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield NewAuthorSigned::class => [
            'method' => 'handlerNewAuthorSigned',
            'from_transport' => 'chapter7_events'
        ];
    }

    public function handlerNewAuthorSigned(NewAuthorSigned $event): void
    {
        $this->projector->__invoke(
            CreateFollowersCounterProjection::ofAuthor(
                $event->authorId(),
                $event->authorUsername()
            )
        );
    }
}
//end-snippet