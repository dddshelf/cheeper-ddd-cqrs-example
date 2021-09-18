<?php

declare(strict_types=1);

namespace Architecture\ES\Infrastructure\V0;

use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Infrastructure\Projection\Projector;
use Architecture\ES\Domain\EventStore;
use Architecture\ES\Domain\EventStream;
use Architecture\ES\Domain\Post;
use Architecture\ES\Domain\PostRepository;

//snippet event-store-post-repository
final class EventStorePostRepository implements PostRepository
{
    public function __construct(
        private EventStore $eventStore,
        private Projector $projector
    ) {
    }

    public function save(Post $post): void
    {
        $events = $post->recordedEvents();

        $this->eventStore->append(new EventStream($post->id()->id(), $events));
        $post->clearEvents();

        $this->projector->project($events);
    }

    public function byId(PostId $id): Post
    {
        return Post::reconstitute(
            $this->eventStore->getEventsFor($id->id())
        );
    }
}
//end-snippet
