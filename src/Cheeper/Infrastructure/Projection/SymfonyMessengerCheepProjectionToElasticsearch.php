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

final class SymfonyMessengerCheepProjectionToElasticsearch implements CheepProjection, MessageSubscriberInterface
{
    public function __construct(
        private Elasticsearch $elasticsearch,
        private Follows $follows,
        private Cheeps $cheeps,
    ) {
    }

    public function whenCheepPosted(CheepPosted $event): void
    {
        $cheepId = CheepId::fromString($event->cheepId());

        $cheep = $this->cheeps->ofId($cheepId);

        if (null === $cheep) {
            throw CheepDoesNotExist::withIdOf($cheepId);
        }

        $authorId = $cheep->authorId();
        $follows = $this->follows->toAuthorId($authorId);

        foreach ($follows as $follow) {
            $this->elasticsearch->index([
                'index' => sprintf("timelines_%s", $follow->fromAuthorId()->toString()),
                'id' => $cheepId->toString(),
                'body' => [
                    'cheep_id' => $cheepId->toString(),
                    'cheep_message' => $cheep->cheepMessage()->message(),
                    'cheep_date' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $cheep->cheepDate()->date())->setTimezone(new \DateTimeZone('UTC'))->format(DateTimeInterface::ATOM)
                ]
            ]);
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