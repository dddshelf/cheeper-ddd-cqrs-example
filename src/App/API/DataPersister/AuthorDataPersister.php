<?php

declare(strict_types=1);

namespace App\API\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\API\Resources\Author;
use App\Messenger\CommandBus;
use Cheeper\Application\Command\Author\SignUp;
use Ramsey\Uuid\Uuid;

/** @template-implements ContextAwareDataPersisterInterface<Author> */
final class AuthorDataPersister implements ContextAwareDataPersisterInterface
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /** @param mixed $data */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Author && null === $data->id;
    }

    /** @param Author $data */
    public function persist($data, array $context = []): Author
    {
        $authorId = Uuid::uuid4();

        $this->commandBus->execute(
            new SignUp(
                $authorId->toString(),
                $data->userName,
                $data->name,
                $data->biography,
                $data->location,
                $data->website,
                $data->birthDate->format('Y-m-d')
            )
        );

        $data->id = $authorId;

        return $data;
    }

    /** @param Author $data */
    public function remove($data, array $context = []): void
    {
        // TODO: Implement remove() method.
    }
}
