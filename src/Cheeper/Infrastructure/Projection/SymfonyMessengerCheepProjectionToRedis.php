<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Projection;

use App\Message\PutCheepOnRedisTimeline;
use Cheeper\Application\Projection\CheepProjection;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Cheep\CheepPosted;
use Cheeper\DomainModel\Follow\Follows;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

//snippet cheep-projection-to-redis
final class SymfonyMessengerCheepProjectionToRedis implements CheepProjection, MessageSubscriberInterface
{
    public function __construct(
        private Follows $follows,
        private MessageBusInterface $appBus,
    ) {
    }

    public function whenCheepPosted(CheepPosted $event): void
    {
        $follows = $this->follows->toAuthorId(
            AuthorId::fromString($event->authorId())
        );

        foreach ($follows as $follow) {
            $this->appBus->dispatch(
                new PutCheepOnRedisTimeline(
                    authorId:       $follow->fromAuthorId()->toString(),
                    cheepId:        $event->cheepId(),
                    cheepMessage:   $event->cheepMessage(),
                    cheepDate:      DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $event->cheepDate())->setTimezone(new \DateTimeZone('UTC'))->format(DateTimeInterface::ATOM),
                )
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
//end-snippet