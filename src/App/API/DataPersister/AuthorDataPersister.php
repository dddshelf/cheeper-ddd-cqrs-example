<?php

declare(strict_types=1);

namespace App\API\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\API\Resources\Author;
use App\Messenger\CommandBus;
use Cheeper\Application\Command\Author\SignUp;
use Ramsey\Uuid\Uuid;

/** @implements DataPersisterInterface<Author> */
final class AuthorDataPersister implements DataPersisterInterface
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function supports($data): bool
    {
        return $data instanceof Author && null === $data->id;
    }

    public function persist($data): Author
    {
        $authorId = Uuid::uuid4();

        $this->commandBus->handle(
            new SignUp(
                $authorId->toString(),
                $data->userName,
                $data->email,
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

    public function remove($data): void
    {
        // TODO: Implement remove() method.
    }
}
