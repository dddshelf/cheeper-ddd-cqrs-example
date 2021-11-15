<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Query;

use Elasticsearch\Client;
use Cheeper\Application\Query\Timeline\TimelineReadLayer;

final class ElasticsearchTimelineReadLayer implements TimelineReadLayer
{
    public function __construct(
        private Client $elasticsearch
    ) {
    }

    public function byAuthorId(string $authorId): array
    {
        $indexName = 'timelines_' . $authorId;

        if (!$this->elasticsearch->indices()->exists(['index' => $indexName])) {
            return [];
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

        return $result['hits']['hits'] ?? [];
    }
}