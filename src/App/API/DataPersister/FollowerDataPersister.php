<?php

declare(strict_types=1);

namespace App\API\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\API\Resources\Follower;
use App\Messenger\CommandBus;
use Cheeper\Application\Command\Author\Follow;
use Ramsey\Uuid\Uuid;

/** @template-implements ContextAwareDataPersisterInterface<Follower> */
final class FollowerDataPersister implements ContextAwareDataPersisterInterface
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /** @param mixed $data */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Follower;
    }

    /** @param Follower $data */
    public function persist($data, array $context = []): Follower
    {
        $data->id = Uuid::uuid4();

        $this->commandBus->execute(
            new Follow(
                $data->id->toString(),
                $data->from,
                $data->to
            )
        );

        return $data;
    }

    /** @param Follower $data */
    public function remove($data, array $context = []): void
    {
        // TODO: Implement remove() method.
    }
}
