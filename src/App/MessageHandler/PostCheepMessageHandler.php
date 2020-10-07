<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\PostCheepMessage;
use App\Messenger\CommandBus;
use Cheeper\Application\Command\Cheep\PostCheep;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

//snippet post-cheep-message-handler
final class PostCheepMessageHandler implements MessageHandlerInterface
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(PostCheepMessage $message): void
    {
        $this->commandBus->handle(
            PostCheep::fromArray([
                'cheep_id' => $message->cheepId(),
                'author_id' => $message->authorId(),
                'message' => $message->message()
            ])
        );
    }
}
//end-snippet
