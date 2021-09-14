<?php declare(strict_types=1);

namespace Architecture\ES\Infrastructure\V0;

use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Infrastructure\Projection\Projector;
use Architecture\ES\Domain\EventStream;
use Architecture\ES\Domain\Post;
use Architecture\ES\Domain\PostRepository;
use Architecture\ES\Infrastructure\EventStore;

/**
 * @psalm-import-type PostEvents from Post
 */
//snippet event-store-post-repository
class EventStorePostRepository implements PostRepository
{
    /** @var EventStore<PostEvents> */
    private EventStore $eventStore;
    /** @var Projector<PostEvents> */
    private Projector $projector;

    /**
     * @param EventStore<PostEvents> $eventStore
     * @param Projector<PostEvents> $projector
     */
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
        return Post::reconstitute(
            $this->eventStore->getEventsFor($id->id())
        );
    }
}
//end-snippet
