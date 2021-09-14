<?php declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Projection\Elasticsearch;

use Architecture\CQRS\Domain\DomainEvent;
use Architecture\CQRS\Domain\PostWasPublished;
use Architecture\CQRS\Domain\Projection;
use Elasticsearch\Client;

/** @implements Projection<PostWasPublished> */
class PostWasPublishedProjection implements Projection
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function listensTo(): string
    {
        return PostWasPublished::class;
    }

    /** @param PostWasPublished $event */
    public function project(DomainEvent $event): void
    {
        $id = $event->postId()->id();

        $this->client->update([
            'index' => 'posts',
            'type'  => 'post',
            'id'    => $id,
            'body'  => ['doc' => [
                'is_published' => true,
            ]],
        ]);
    }
}
