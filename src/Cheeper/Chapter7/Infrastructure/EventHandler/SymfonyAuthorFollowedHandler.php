<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\EventHandler;

use Cheeper\Chapter7\Application\Projector\Author\CountFollowerProjector;
use Cheeper\Chapter7\Application\Projector\Author\CountFollowersProjection;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-author-followed-handler
final class SymfonyAuthorFollowedHandler implements MessageSubscriberInterface
{
    public function __construct(
        private CountFollowerProjector $projector
    ) {
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorFollowed::class => [
            'bus' => 'event.bus',
            'method' => 'handle',
        ];
    }

    public function handle(AuthorFollowed $event): void
    {
        $this->projector->__invoke(
            CountFollowersProjection::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet