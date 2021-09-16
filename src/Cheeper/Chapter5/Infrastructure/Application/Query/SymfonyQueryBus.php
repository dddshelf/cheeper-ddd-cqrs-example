<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Infrastructure\Application\Query;

use Cheeper\Chapter5\Application\Query\Query;
use Cheeper\Chapter5\Application\Query\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

//snippet symfony-query-bus
final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function query(Query $query): mixed
    {
        return $this->handle($query);
    }
}
//end-snippet
