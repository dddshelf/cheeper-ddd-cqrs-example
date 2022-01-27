<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event;

use Cheeper\Chapter7\Application\Projector\Author\CountFollowerProjectionHandler;
use Cheeper\Chapter7\Application\Projector\Author\CountFollowersProjection;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;

//snippet author-followed-event-handler
final class AuthorFollowedEventHandler
{
    public function __construct(
        private CountFollowerProjectionHandler $projector
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
