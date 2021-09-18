<?php

declare(strict_types=1);

namespace Architecture\ES\Infrastructure\V0;

use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Infrastructure\Projection\Projector;
use Architecture\ES\Domain\Post;
use Architecture\ES\Infrastructure\InMemoryEventStore;
use Architecture\ES\Infrastructure\RedisEventStore;
use PHPUnit\Framework\TestCase;
use Zumba\JsonSerializer\JsonSerializer;

final class EventStorePostRepositoryTest extends TestCase
{
    private EventStorePostRepository $postRepository;

    public function setUp(): void
    {
        $this->postRepository = new EventStorePostRepository(
            new InMemoryEventStore(),
            new Projector()
        );
    }

    /** @test */
    public function itShouldSaveAnRestoreAnEventStream(): void
    {
        $p = Post::writeNewFrom('A title', 'Some content');

        $this->postRepository->save($p);

        $this->assertPostCreated($p->id());
    }

    private function assertPostCreated(PostId $id): void
    {
        $found = $this->postRepository->byId($id);
        $this->assertEquals('A title', $found->title());
        $this->assertEquals('Some content', $found->content());
    }
}
