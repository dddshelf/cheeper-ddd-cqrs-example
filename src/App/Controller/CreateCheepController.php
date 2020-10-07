<?php

declare(strict_types=1);

namespace App\Controller;

use App\Message\PostCheepMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Safe\sprintf;

//snippet create-cheep-controller
final class CreateCheepController extends AbstractController
{
    /** @Route("/create-cheep", methods={"POST"}) */
    public function __invoke(Request $request, MessageBusInterface $bus): Response
    {
        $authorId = $request->request->get('authorId');
        $message = $request->request->get('message');

        if (!$authorId) {
            throw new \InvalidArgumentException(
                sprintf("Author ID should be provided")
            );
        }

        if (!$message) {
            throw new \InvalidArgumentException(
                sprintf("Message should be provided")
            );
        }

        $cheepId = Uuid::uuid4()->toString();

        $bus->dispatch(
            new PostCheepMessage(
                $cheepId,
                $authorId,
                $message
            )
        );

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
//end-snippet
