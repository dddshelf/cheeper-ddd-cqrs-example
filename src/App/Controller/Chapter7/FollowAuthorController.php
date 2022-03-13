<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use App\Messenger\CommandBus;
use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Chapter7\Application\Author\Command\FollowCommand;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function json_decode;

final class FollowAuthorController extends AbstractController
{
    #[Route("/chapter7/follow", methods: ["POST"])]
    public function __invoke(Request $request, CommandBus $commandBus): Response
    {
        $httpCode = Response::HTTP_ACCEPTED;
        $httpContent = [
            'meta' => [],
            'data' => [],
        ];

        $command = null;
        try {
            $command = FollowCommand::fromArray(
                $this->getRequestContentInJson($request)
            );

            $commandBus->handle($command);

            $httpContent['data']['from_author_id'] = $command->fromAuthorId();
            $httpContent['data']['to_author_id'] = $command->toAuthorId();
        } catch (
        AuthorDoesNotExist
        |InvalidArgumentException $exception
        ) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $httpContent['data']['message'] = $exception->getMessage();
        } finally {
            $httpContent['meta']['message_id'] = $command?->messageId()?->toString();
        }

        return $this->buildJsonResponse($httpContent, $httpCode);
    }

    private function getRequestContentInJson(Request $request): mixed
    {
        return json_decode(
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
