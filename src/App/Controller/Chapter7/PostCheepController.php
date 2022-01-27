<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use App\Messenger\CommandBus;
use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Chapter7\Application\Cheep\Command\PostCheepCommand;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PostCheepController extends AbstractController
{
    #[Route("/chapter7/cheep", methods: ["POST"])]
    public function __invoke(Request $request, CommandBus $commandBus): Response
    {
        $httpCode = Response::HTTP_ACCEPTED;
        try {
            $command = PostCheepCommand::fromArray(
                $this->getRequestContentInJson($request)
            );

            $commandBus->handle($command);
            $httpContent = [
                'message_id' => $command->messageId()?->toString(),
                'cheep_id' => $command->authorId(),
            ];
        } catch (
            AuthorDoesNotExist
            |InvalidArgumentException $exception
        ) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $httpContent = ['message' => $exception->getMessage()];
        }

        return $this->buildJsonResponse($httpContent, $httpCode);
    }

    private function getRequestContentInJson(Request $request): mixed
    {
        return \Safe\json_decode(
            $request->getContent(),
            true
        );
    }

    private function buildJsonResponse(array $httpContent, int $httpCode): JsonResponse
    {
        return $this->json(
            data: $httpContent,
            status: $httpCode,
        );
    }
}
