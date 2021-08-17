<?php

declare(strict_types=1);

namespace App\API\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\API\Resources\Follower;
use App\Messenger\CommandBus;
use Cheeper\Application\Command\Author\Follow;

/** @implements DataPersisterInterface<Follower> */
final class FollowerDataPersister implements DataPersisterInterface
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public function supports(mixed $data): bool
    {
        return $data instanceof Follower;
    }

    public function persist(mixed $data)
    {
        $this->commandBus->handle(
            Follow::anAuthor(
                $data->to,
                $data->from
            )
        );

        return $data;
    }

    public function remove(mixed $data): void
    {
        // TODO: Implement remove() method.
    }
}
