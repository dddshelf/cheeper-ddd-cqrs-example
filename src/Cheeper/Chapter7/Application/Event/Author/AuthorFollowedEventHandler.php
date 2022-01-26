<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Event\Author;

use Cheeper\Chapter7\Application\Projector\Author\CountFollowerProjector;
use Cheeper\Chapter7\Application\Projector\Author\CountFollowersProjection;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;

//snippet author-followed-event-handler
final class AuthorFollowedEventHandler
{
    public function __construct(
        private CountFollowerProjector $projector
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
