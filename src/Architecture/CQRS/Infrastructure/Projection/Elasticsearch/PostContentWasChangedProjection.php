<?php

namespace Architecture\CQRS\Infrastructure\Projection\Elasticsearch;

use Architecture\CQRS\Domain\DomainEvent;
use Architecture\CQRS\Domain\PostContentWasChanged;
use Architecture\CQRS\Domain\Projection;
use Elasticsearch\Client;

/** @implements Projection<PostContentWasChanged> */
class PostContentWasChangedProjection implements Projection
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function listensTo(): string
    {
        return PostContentWasChanged::class;
    }

    /** @param PostContentWasChanged $event */
    public function project(DomainEvent $event): void
    {
        $id = $event->postId()->id();

        $this->client->update([
            'index' => 'posts',
            'type'  => 'post',
            'id'    => $id,
            'body'  => ['doc' => [
                'content' => $event->content()
            ]]
        ]);
    }
}
