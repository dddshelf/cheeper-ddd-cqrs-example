<?php

declare(strict_types=1);

namespace Cheeper\Application\Timeline;

use App\Dto\CheepDto;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Psl\Json;
use Psl\Type;
use Psl\Vec;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class TimelineQueryHandler
{
    public function __construct(
        private readonly \Redis $redis,
    ) {
    }

    public function __invoke(TimelineQuery $query): TimelineQueryResponse
    {
        if (!$this->redis->exists("timeline_of:" . $query->authorId)) {
            $authorId = AuthorId::fromString($query->authorId);
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        $serializedCheeps = $this->redis->lRange("timeline_of:" . $query->authorId, $query->offset, $query->size);

        return new TimelineQueryResponse(
            Vec\map(
                $serializedCheeps,
                static function (string $sc) {
                    $cheepData = Json\typed(
                        $sc,
                        Type\shape([
                            'cheepId' => Type\non_empty_string(),
                            'authorId' => Type\non_empty_string(),
                            'cheepMessage' => Type\non_empty_string(),
                            'cheepDate' => Type\non_empty_string(),
                        ])
                    );

                    return new CheepDto(
                        id:         $cheepData['cheepId'],
                        authorId:   $cheepData['authorId'],
                        text:       $cheepData['cheepMessage'],
                        createdAt:  $cheepData['cheepDate'],
                    );
                },
            )
        );
    }
}
