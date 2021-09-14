<?php declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Projection\Elasticsearch;

use Architecture\CQRS\Domain\DomainEvent;
use Architecture\CQRS\Domain\PostWasCategorized;
use Architecture\CQRS\Domain\Projection;
use Elasticsearch\Client;

/** @implements Projection<PostWasCategorized> */
class PostWasCategorizedProjection implements Projection
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
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
