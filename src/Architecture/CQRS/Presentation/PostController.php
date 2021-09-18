<?php

declare(strict_types=1);

namespace Architecture\CQRS\Presentation;

//snippet post-controller
final class PostController
{
    public function listAction(): array
    {
        $client = \Elasticsearch\ClientBuilder::create()->build();

        $response = $client->search([
            'index' => 'posts',
            'type'  => 'post',
            'body' => [
                'sort' => [
                    'created_at' => ['order' => 'desc'],
                ],
            ],
        ]);

        return [
            'posts' => $response,
        ];
    }
}
//end-snippet
