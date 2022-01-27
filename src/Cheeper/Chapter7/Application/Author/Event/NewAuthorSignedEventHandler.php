<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event;

use Cheeper\Chapter7\Application\Projector\Author\CreateFollowersCounterProjection;
use Cheeper\Chapter7\Application\Projector\Author\CreateFollowersCounterProjectionProjector;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;

//snippet new-author-signed-event-handler
final class NewAuthorSignedEventHandler
{
    public function __construct(
        private CreateFollowersCounterProjectionProjector $projector
    ) {
    }

    public function handle(NewAuthorSigned $event): void
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
