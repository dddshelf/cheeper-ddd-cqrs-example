<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application;

use Cheeper\Chapter7\Application\Query;
use Cheeper\Chapter7\Application\QueryBus;
use function Functional\first;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
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
        try {
            return $this->handle($query);
        } catch (HandlerFailedException $e) {
            throw first($e->getNestedExceptions());
        }
    }
}
//end-snippet
