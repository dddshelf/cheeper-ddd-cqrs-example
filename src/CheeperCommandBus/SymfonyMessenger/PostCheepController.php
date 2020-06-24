<?php

declare(strict_types=1);

namespace CheeperCommandBus\SymfonyMessenger;

use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

// snippet symfony-controller-command-bus
final class PostCheepController extends AbstractController
{
    /** @Route("/cheeps", name="post_cheep") */
    public function __invoke(Request $request, MessageBusInterface $bus): Response
    {
        //ignore
        $authorId = $request->request->get('author_id');
        $cheepId = $request->request->get('cheep_id');
        $message = $request->request->get('message');

        if (null === $authorId || null === $cheepId || null === $message) {
            throw new BadRequestHttpException('Invalid parameters given');
        }

        $command = PostCheep::fromArray([
            'author_id' => $authorId,
            'cheep_id' => $cheepId,
            'message' => $message,
        ]);
        //end-ignore

        try {
            $bus->dispatch($command);
        } catch (AuthorDoesNotExist | InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        return new Response('', Response::HTTP_CREATED);
    }
}
//end-snippet
