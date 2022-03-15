<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use App\Messenger\CommandBus;
use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Chapter7\Application\Cheep\Command\PostCheepCommand;
use InvalidArgumentException;
use function Safe\json_decode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//snippet chapter7-postcheep-controller
final class PostCheepController extends AbstractController
{
    #[Route("/chapter7/cheep", methods: ["POST"])]
    public function __invoke(Request $request, CommandBus $commandBus): Response
    {
        $httpCode = Response::HTTP_ACCEPTED;
        $httpContent = [
            'meta' => [],
            'data' => [],
        ];

        $command = null;
        try {
            $command = PostCheepCommand::fromArray(
                $this->getRequestContentInJson($request)
            );

            $commandBus->handle($command);

            $httpContent['data']['cheep_id'] = $command->cheepId();
        } catch (
            AuthorDoesNotExist
            | InvalidArgumentException $exception
        ) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $httpContent['data']['message'] = $exception->getMessage();
        } finally {
            $httpContent['meta']['message_id'] = $command?->messageId()?->toString();
        }

        return $this->buildJsonResponse(
            $httpContent,
            $httpCode
        );
    }

    //ignore
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
    //end-ignore
}
//end-snippet
