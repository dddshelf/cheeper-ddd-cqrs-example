<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Projection;

use App\Elasticsearch\Config;
use Cheeper\Application\Projection\CheepProjection;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Cheep\CheepDoesNotExist;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepPosted;
use Cheeper\DomainModel\Cheep\Cheeps;
use Cheeper\DomainModel\Follow\Follows;
use DateTimeImmutable;
use DateTimeInterface;
use Elasticsearch\Client as Elasticsearch;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class SymfonyMessengerCheepProjectionToRedis implements CheepProjection, MessageSubscriberInterface
{
    public function __construct(
        private \Redis $redis,
        private Follows $follows,
    ) {
    }

    public function whenCheepPosted(CheepPosted $event): void
    {
        $follows = $this->follows->toAuthorId(
            AuthorId::fromString($event->authorId())
        );

        foreach ($follows as $follow) {
            $this->redis->lPush(
                sprintf("timelines_%s", $follow->fromAuthorId()->toString()),
                serialize([
                    'cheep_id' => $event->cheepId(),
                    'cheep_message' => $event->cheepMessage(),
                    'cheep_date' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $event->cheepDate())->setTimezone(new \DateTimeZone('UTC'))->format(DateTimeInterface::ATOM)
                ])
            );
        }
    }

    public static function getHandledMessages(): iterable
    {
        yield CheepPosted::class => [
            'method' => 'whenCheepPosted',
            'from_transport' => 'projections'
        ];
    }
}