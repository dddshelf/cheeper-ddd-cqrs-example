<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Projector\Author;

use Cheeper\Chapter6\Application\Projector\Author\CountFollowerProjector;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowers;
use Cheeper\DomainModel\Follow\AuthorFollowed;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

//snippet symfony-projector-count-followers
final class SymfonyCountFollowerProjector implements MessageSubscriberInterface
{
    public function __construct(
        private CountFollowerProjector $appProjector
    ) { }

    public function __invoke(AuthorFollowed $event): void
    {
        $this->appProjector->__invoke(
            CountFollowers::ofAuthor($event->toAuthorId())
        );
    }

    public static function getHandledMessages(): iterable
    {
        yield AuthorFollowed::class => [
            'bus' => 'event.bus'
        ];
    }
}
//end-snippet