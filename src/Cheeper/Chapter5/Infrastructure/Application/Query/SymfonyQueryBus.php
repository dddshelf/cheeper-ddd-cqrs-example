<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Infrastructure\Application\Query;

use Cheeper\Chapter5\Application\Query\Query;
use Cheeper\Chapter5\Application\Query\QueryBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use function Functional\first;

//snippet symfony-query-bus
final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function query(Query $query): Envelope
    {
        try {
            $envelope = $this->messageBus->dispatch($query);
        } catch (HandlerFailedException $exception) {
            throw first($exception->getNestedExceptions());
        }

        return $envelope;
    }
}
//end-snippet
