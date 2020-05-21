<?php

namespace Architecture\CQRS\Infrastructure\Projection\Elasticsearch;

use Architecture\CQRS\Domain\DomainEvent;
use Architecture\CQRS\Domain\PostContentWasChanged;
use Architecture\CQRS\Domain\Projection;
use Elasticsearch\Client;

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

    public function project(DomainEvent $event): void
    {
        /** @var PostContentWasChanged $event */
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
