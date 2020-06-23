<?php

declare(strict_types=1);

namespace CheeperCommandBus\SimplestNoCommandBus;

use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

//snippet simple-command-handler-execution
final class SignUpController extends AbstractController
{
    private PostCheepHandler $postCheepHandler;

    //ignore
    public function __construct(PostCheepHandler $postCheepHandler)
    {
        $this->postCheepHandler = $postCheepHandler;
    }
    //end-ignore

    public function __invoke(Request $request): Response
    {
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

        try {
            ($this->postCheepHandler)($command);
        } catch (AuthorDoesNotExist | InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        return new Response('', Response::HTTP_CREATED);
    }
}
//end-snippet
