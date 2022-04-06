<?php

namespace Architecture\ES\Infrastructure\V0;

use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Infrastructure\Projection\Projector;
use Architecture\ES\Domain\Post;
use Architecture\ES\Domain\PostRepository;
use Architecture\ES\Domain\EventStream;
use Architecture\ES\Infrastructure\EventStore;

//snippet event-store-post-repository
class EventStorePostRepository implements PostRepository
{
    private EventStore $eventStore;
    private Projector $projector;

    public function __construct(EventStore $eventStore, Projector $projector)
    {
        $this->eventStore = $eventStore;
        $this->projector = $projector;
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
        /** @var Post */
        return Post::reconstitute(
            $this->eventStore->getEventsFor($id->id())
        );
    }
}
//end-snippet