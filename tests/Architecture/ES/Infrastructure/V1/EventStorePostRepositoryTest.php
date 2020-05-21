<?php

namespace Architecture\ES\Infrastructure\V1;

use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Infrastructure\Projection\Projector;

use Architecture\ES\Domain\Post;

use Architecture\ES\Infrastructure\EventStore;

use Architecture\ES\Infrastructure\SnapshotRepository;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Zumba\JsonSerializer\JsonSerializer;

class EventStorePostRepositoryTest extends TestCase
{
    private EventStore $eventStore;

    public function setUp(): void
    {
        $redis = new Client();
        $serializer = new JsonSerializer();

        $this->postRepository = new EventStorePostRepository(
            new SnapshotRepository($redis, $serializer),
            new EventStore($redis, $serializer),
            new Projector()
        );
    }

    /**
     * @test
     */
    public function itShouldSaveAnRestoreAnEventStream(): void
    {
        $p = Post::writeNewFrom('A title', 'Some content');

        $this->postRepository->save($p);

        $this->assertPostCreated($p->id(), 'A title', 'Some content');
    }

    private function assertPostCreated(PostId $id, string $title, string $content): void
    {
        $found = $this->postRepository->byId($id);
        $this->assertEquals($title, $found->title());
        $this->assertEquals($content, $found->content());
    }
}
