<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Application;

use Cheeper\Application\Query;
use Cheeper\Application\QueryBus;
use Cheeper\Application\QueryResponse;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyMessengerQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $queryBus
    ) {
        $this->messageBus = $queryBus;
    }

    public function askFor(Query $query): QueryResponse
    {
        $result = $this->handle($query);
        assert($result instanceof QueryResponse);

        return $result;
    }
}