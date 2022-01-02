<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use App\Messenger\CommandBus;
use Cheeper\Chapter7\Application\Command\Author\SignUp;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SignUpAuthorController extends AbstractController
{
    #[Route("/chapter7/author", methods: ["POST"])]
    public function follow(Request $request, CommandBus $commandBus): Response
    {
        $command = SignUp::fromArray(
            $this->getRequestContentInJson($request)
        );

        $commandBus->handle($command);

        return $this->json(
            data: [
                'message_id' => $command->messageId()->toString(),
                'author_id' => $command->authorId(),
            ],
            status: Response::HTTP_CREATED,
        );
    }

    private function getRequestContentInJson(Request $request): mixed
    {
        return \Safe\json_decode(
            $request->getContent(),
            true
        );
    }
}

