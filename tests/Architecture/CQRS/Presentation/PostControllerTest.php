<?php

namespace Architecture\CQRS\Presentation;

use Elasticsearch\Client;

use Elasticsearch\ClientBuilder;
use PHPUnit\Framework\TestCase;

class PostControllerTest extends TestCase
{
    private PostController $postController;
    private Client $elasticClient;

    public function setUp(): void
    {
        $this->elasticClient = ClientBuilder::create()->build();
        $this->elasticClient->indices()->create([
            'index' => 'posts',
            'body' => [
                'mappings' => [
                    'post' => [
                        'properties' => [
                            'created_at' => [
                                'type' => 'date'
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->postController = new PostController();
    }

    /**
     * @test
     */
    public function itShouldListPosts(): void
    {
        $response = $this->postController->listAction();

        $this->assertNotNull($response['posts']);
    }

    public function tearDown(): void
    {
        $this->elasticClient->indices()->delete(['index' => 'posts']);
    }
}
