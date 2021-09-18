<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Projection\Elasticsearch;

use Architecture\CQRS\Domain\DomainEvent;
use Architecture\CQRS\Domain\PostWasCategorized;
use Architecture\CQRS\Domain\Projection;
use Elasticsearch\Client;

final class PostWasCategorizedProjection implements Projection
{
    public function __construct(
        private Client $client
    ) {
    }

    public function listensTo(): string
    {
        return PostWasCategorized::class;
    }

    /** @param PostWasCategorized $event */
    public function project(DomainEvent $event): void
    {
        $id = $event->postId()->id();

        $this->client->update([
            'index' => 'posts',
            'type'  => 'post',
            'id'    => $id,
            'body'  => ['doc' => [
                'category_id' => $event->categoryId()->id(),
            ]],
        ]);
    }
}
