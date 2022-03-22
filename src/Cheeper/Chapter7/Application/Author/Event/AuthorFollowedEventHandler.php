<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event;

use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandlerInterface;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;

//snippet author-followed-event-handler
final class AuthorFollowedEventHandler
{
    public function __construct(
        private CountFollowersProjectionHandlerInterface $projectionHandler
    ) {
    }

    public function __invoke(AuthorFollowed $event): void
    {
        $this->projectionHandler->__invoke(
            CountFollowersProjection::ofAuthor(
                $event->toAuthorId()
            )
        );

        // Other actions, like welcoming the new author,
        // can be added here. Alternatively, a more
        // scalable design is to create one Event Handler
        // for each of the needed actions to happen in
        // reaction. All of those listening to the same
        // AuthorFollowed Domain Event.
        // @see: WhenAuthorFollowedThenCreateFollowersCounterProjectionEventHandler
        // @see: WhenAuthorFollowedThenWelcomeAuthorEventHandler
    }
}
//end-snippet