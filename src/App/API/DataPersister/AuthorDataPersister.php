<?php

declare(strict_types=1);

namespace App\API\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\API\Resources\Author;
use App\Messenger\CommandBus;
use Cheeper\Application\Command\Author\SignUp;
use Ramsey\Uuid\Uuid;

final class AuthorDataPersister implements DataPersisterInterface
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public function supports(mixed $data): bool
    {
        return $data instanceof Author && null === $data->id;
    }

    public function persist(mixed $data): Author
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

    public function remove(mixed $data): void
    {
        // TODO: Implement remove() method.
    }
}
