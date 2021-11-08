<?php

declare(strict_types=1);

namespace App\API\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\API\Resources\Timeline;
use Elasticsearch\Client as Elasticsearch;
use Ramsey\Uuid\Uuid;

final class TimelineDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private Elasticsearch $elasticsearch
    ) {
    }

    /** @inheritDoc */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): Timeline
    {
        $timeline = new Timeline();
        $timeline->id = Uuid::fromString($id);
        $timeline->cheeps = [];

        $indexName = 'timelines_' . (string)$id;

        if (!$this->elasticsearch->indices()->exists(['index' => $indexName])) {
            return $timeline;
        }

        $result = $this->elasticsearch->search([
            'index' => $indexName,
            'body' => [
                'sort' => [
                    'cheep_date' => ['order' => 'desc']
                ],
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ]
        ]);

        $timeline->cheeps = $result['hits']['hits'];

        return $timeline;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Timeline::class === $resourceClass;
    }
}