<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event;

use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandler;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;

//snippet author-followed-event-handler
final class AuthorFollowedEventHandler
{
    public function __construct(
        private CountFollowersProjectionHandler $projector
    ) {
    }

    public function handle(AuthorFollowed $event): void
    {
        $this->projector->__invoke(
            CountFollowersProjection::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet
