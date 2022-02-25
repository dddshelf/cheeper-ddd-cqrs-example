<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Author\Projection\SagaStyle;

use App\Messenger\CommandBus;
use Cheeper\Chapter4\DomainModel\Author\AuthorFollowed;
use Cheeper\Chapter6\Application\Author\Projection\CountFollowersProjection;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-author-followed-handler-in-saga-style
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
            'method' => 'handlerAuthorFollowed',
        ];
    }

    public function handlerAuthorFollowed(AuthorFollowed $event): void
    {
        $this->commandBus->handle(
            CountFollowersProjection::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet
