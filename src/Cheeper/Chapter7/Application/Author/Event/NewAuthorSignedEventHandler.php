<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event;

use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjection;
use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjectionHandlerInterface;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;

//snippet new-author-signed-event-handler
final class NewAuthorSignedEventHandler
{
    public function __construct(
        private CreateFollowersCounterProjectionHandlerInterface $followersProjector
    ) {
    }

    public function __invoke(NewAuthorSigned $event): void
    {
        $this->followersProjector->__invoke(
            CreateFollowersCounterProjection::ofAuthor(
                $event->authorId(),
                $event->authorUsername()
            )
        );

        // Other actions, like welcoming the new author,
        // can be added here. Alternatively, a more
        // scalable design is to create one Event Handler
        // for each of the needed actions to happen in
        // reaction. All of those listening to the same
        // NewAuthorSigned Domain Event.
        // @see: WhenNewAuthorSignedThenCreateEmptyFollowersCounterProjectionEventHandler
        // @see: WhenNewAuthorSignedThenWelcomeAuthorEventHandler
    }
}
//end-snippet
