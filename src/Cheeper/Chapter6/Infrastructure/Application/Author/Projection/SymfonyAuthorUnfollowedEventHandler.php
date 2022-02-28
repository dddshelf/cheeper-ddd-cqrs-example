<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Author\Projection;

use App\Messenger\CommandBus;
use Cheeper\Chapter4\DomainModel\Author\AuthorUnfollowed;
use Cheeper\Chapter6\Application\Author\Projection\CountFollowersProjection;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-author-unfollowed-handler
final class SymfonyAuthorUnfollowedEventHandler implements MessageSubscriberInterface
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorUnfollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handleAuthorUnfollowed',
        ];
    }

    public function handleAuthorUnfollowed(AuthorUnfollowed $event): void
    {
        $this->commandBus->handle(
            CountFollowersProjection::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet
