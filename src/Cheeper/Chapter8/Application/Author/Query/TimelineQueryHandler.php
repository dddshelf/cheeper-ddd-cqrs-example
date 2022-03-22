<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\Application\Author\Query;

use App\Repository\PopularCheepRepository;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter8\DomainModel\Follow\FollowRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Redis;

//snippet chapter8-timeline-query-handler
final class TimelineQueryHandler
{
    public function __construct(
        private Redis $redis,
        private PopularCheepRepository $popularCheepRepository,
        private FollowRepository $followRepository,
    ) {
    }

    public function __invoke(TimelineQuery $query): TimelineQueryResponse
    {
        $serializedCheeps = $this->redis->lRange(
            sprintf('timelines_%s', $query->authorId()),
            $query->offset(),
            ($query->offset() + $query->size()) - 1
        );

        $authorsFollowing = $this->followRepository->findFollowingOf(
            AuthorId::fromString($query->authorId())
        );

        $cheeps = [];
        $popularCheeps = $this->popularCheepRepository->of($authorsFollowing);

        foreach ($popularCheeps as $popularCheep) {
            $cheeps[] = [
                'cheep_id' => $popularCheep->getId()->toString(),
                'cheep_message' => $popularCheep->getMessage(),
                'cheep_date' => $popularCheep->getCreatedAt()->format(DateTimeInterface::ATOM),
            ];
        }

        foreach ($serializedCheeps as $serializedCheep) {
            $cheeps[] = json_decode($serializedCheep, true, flags: JSON_THROW_ON_ERROR);
        }

        usort(
            $cheeps,
            function (array $c1, array $c2): int {
                $date1 = DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $c1['cheep_date']);
                $date2 = DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $c2['cheep_date']);
                return $date1 <=> $date2;
            }
        );

        return new TimelineQueryResponse($cheeps);
    }
}
//end-snippet