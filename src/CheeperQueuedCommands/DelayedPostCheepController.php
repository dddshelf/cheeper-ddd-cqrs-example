<?php

declare(strict_types=1);

namespace CheeperQueuedCommands;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class DelayedPostCheepController extends AbstractController
{
    //snippet delayed-post-cheep-controller
    #[Route("/cheeps", methods: ["POST"])]
    public function __invoke(Request $request, MessageBusInterface $bus): Response
    {
        $authorId = $request->request->get('authorId');
        $message = $request->request->get('message');

        if (!$authorId) {
            throw new InvalidArgumentException(
                sprintf("Author ID should be provided")
            );
        }

        if (!$message) {
            throw new InvalidArgumentException(
                sprintf("Message should be provided")
            );
        }

        $cheepId = Uuid::uuid4()->toString();

        $command = PostCheep::fromArray([
            'author_id' => $authorId,
            'cheep_id' => $cheepId,
            'message' => $message
        ]);

        $queuedCommand = new QueuedCommand($command);

        $bus->dispatch($queuedCommand);

        return new Response('', Response::HTTP_ACCEPTED);
    }
    //end-snippet
}
