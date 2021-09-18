<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Projection;

use Architecture\CQRS\Domain\CategoryId;

// use Architecture\CQRS\Domain\Post;

use Architecture\CQRS\Domain\PostContentWasChanged;
use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Domain\PostTitleWasChanged;
use Architecture\CQRS\Domain\PostWasCategorized;
use Architecture\CQRS\Domain\PostWasCreated;
use Architecture\CQRS\Domain\PostWasPublished;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostContentWasChangedProjection;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostTitleWasChangedProjection;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostWasCategorizedProjection;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostWasCreatedProjection;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostWasPublishedProjection;
use PHPUnit\Framework\TestCase;

final class ProjectorTest extends TestCase
{
    /** @test */
    public function itShouldProjectIntoElasticsearch(): void
    {
        //snippet projector-usage
        $client = \Elasticsearch\ClientBuilder::create()->build();

        $projector = new Projector();
        $projector->register([
            new PostWasCreatedProjection($client),
            new PostWasPublishedProjection($client),
            new PostWasCategorizedProjection($client),
            new PostTitleWasChangedProjection($client),
            new PostContentWasChangedProjection($client)
        ]);

        $postId = PostId::create();
        $categoryId = CategoryId::create();
        $projector->project([
            new PostWasCreated($postId, 'A title', 'Some content'),
            new PostWasPublished($postId),
            new PostWasCategorized($postId, $categoryId),
            new PostTitleWasChanged($postId, 'New title'),
            new PostContentWasChanged($postId, 'New content'),
        ]);
        //end-snippet

        $params = [
            'index' => 'posts',
            'type'  => 'post',
            'id'    => $postId->id()
        ];

        $document = $client->get($params);
        $this->assertEquals([
            'title' => 'New title',
            'content' => 'New content',
            'is_published' => true,
            'category_id' => $categoryId->id()
        ], $document['_source']);

        $client->delete($params);
        $client->indices()->delete(['index' => 'posts']);
    }
}
