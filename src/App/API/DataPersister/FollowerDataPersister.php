<?php

declare(strict_types=1);

namespace App\API\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\API\Resources\Follower;
use App\Messenger\CommandBus;
use Cheeper\Application\Command\Author\Follow;

final class FollowerDataPersister implements DataPersisterInterface
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /** @param mixed $data */
    public function supports($data): bool
    {
        return $data instanceof Follower;
    }

    /** @param mixed|Follower $data */
    public function persist($data): Follower
    {
        $this->commandBus->execute(
            Follow::anAuthor(
                $data->to,
                $data->from
            )
        );

        return $data;
    }

    /** @param mixed|Follower $data */
    public function remove($data): void
    {
        // TODO: Implement remove() method.
    }
}
