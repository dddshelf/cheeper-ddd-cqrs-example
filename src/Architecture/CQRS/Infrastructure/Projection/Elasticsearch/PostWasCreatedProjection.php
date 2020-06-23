<?php

namespace Architecture\CQRS\Infrastructure\Projection\Elasticsearch;

use Architecture\CQRS\Domain\DomainEvent;
use Architecture\CQRS\Domain\PostWasCreated;
use Architecture\CQRS\Domain\Projection;
use Elasticsearch\Client;

/** @implements Projection<PostWasCreated> */
//snippet elasticsearch-projection
class PostWasCreatedProjection implements Projection
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function listensTo(): string
    {
        return PostWasCreated::class;
    }

    /** @param PostWasCreated $event */
    public function project(DomainEvent $event): void
    {
        $id = $event->postId()->id();

        $this->client->index([
            'index' => 'posts',
            'type'  => 'post',
            'id'    => $id,
            'body'  => [
                'title' => $event->title(),
                'content' => $event->content()
            ]
        ]);
    }
}
//end-snippet
