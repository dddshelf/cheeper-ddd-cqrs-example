<?php

namespace Architecture\ES\Infrastructure;

use Architecture\CQRS\Domain\CategoryId;
use Architecture\CQRS\Domain\PostContentWasChanged;

use Architecture\CQRS\Domain\PostId;

use Architecture\CQRS\Domain\PostTitleWasChanged;
use Architecture\CQRS\Domain\PostWasCategorized;
use Architecture\CQRS\Domain\PostWasCreated;
use Architecture\CQRS\Domain\PostWasPublished;
use Architecture\ES\Domain\EventStream;
use PHPUnit\Framework\TestCase;
use Predis\Client;

use Zumba\JsonSerializer\JsonSerializer;

class EventStoreTest extends TestCase
{
    private EventStore $eventStore;

    public function setUp(): void
    {
        $this->eventStore = new EventStore(
            $client = new Client(),
            $serializer = new JsonSerializer()
        );
    }

    /**
     * @test
     */
    public function itShouldSaveAnRestoreAnEventStream(): void
    {
        $postId = PostId::create();
        $categoryId = CategoryId::create();

        $stream = new EventStream($postId->id(), [
            new PostWasCreated($postId, 'A title', 'Some content'),
            new PostWasPublished($postId),
            new PostWasCategorized($postId, $categoryId),
            new PostTitleWasChanged($postId, 'New title'),
            new PostContentWasChanged($postId, 'New content'),
        ]);

        $this->eventStore->append($stream);

        $foundStream = $this->eventStore->getEventsFor($postId->id());

        $this->assertEquals($foundStream, $stream);
    }
}
