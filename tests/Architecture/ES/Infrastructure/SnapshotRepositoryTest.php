<?php

namespace Architecture\ES\Infrastructure;

use Architecture\CQRS\Domain\Post;
use PHPUnit\Framework\TestCase;

use Predis\Client;

use Zumba\JsonSerializer\JsonSerializer;

class SnapshotrepositoryTest extends TestCase
{
    private SnapshotRepository $snapshotRepository;

    public function setUp(): void
    {
        $this->snapshotRepository = new SnapshotRepository(
            $client = new Client(),
            $serializer = new JsonSerializer()
        );
    }

    /**
     * @test
     */
    public function itShouldSaveAndRestorePostSnapshot(): void
    {
        $snapshot = new Snapshot(
            $post = Post::writeNewFrom('A title', 'Some content'),
            0
        );

        $this->snapshotRepository->save('irrelevant', $snapshot);

        $this->assertSnapshotCreated('irrelevant', $snapshot);
    }

    private function assertSnapshotCreated(string $id, Snapshot $snapshot): void
    {
        $post = $snapshot->aggregate();

        $snapshotFound = $this->snapshotRepository->byId($id);
        $postFound = $snapshotFound->aggregate();

        $this->assertEquals($snapshot->version(), $snapshotFound->version());
        $this->assertEquals($post->title(), $postFound->title());
        $this->assertEquals($post->content(), $postFound->content());
    }
}
