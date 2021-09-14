<?php

declare(strict_types=1);

namespace App\API\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\API\Resources\Cheep;
use App\Messenger\CommandBus;
use Cheeper\Application\Command\Cheep\PostCheep;
use Ramsey\Uuid\Uuid;

//snippet cheeper-data-persister
/**
 * @implements ContextAwareDataPersisterInterface<Cheep>
 */
final class CheepDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public function supports(mixed $data, array $context = []): bool
    {
        return $data instanceof Cheep;
    }

    public function persist(mixed $data, array $context = [])
    {
        $id = Uuid::uuid4();

        $this->commandBus->handle(
            PostCheep::fromArray([
                'author_id' => $data->authorId,
                'cheep_id'  => $id->toString(),
                'message' => $data->message,
            ])
        );

        $data->id = $id;

        return $data;
    }

    public function remove(mixed $data, array $context = []): void
    {
        // TODO: Implement remove() method.
    }
}
//end-snippet
