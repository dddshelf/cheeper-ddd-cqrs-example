<?php

declare(strict_types=1);

namespace CheeperCommandBus\FinalSymfonyMessenger;

use App\Messenger\CommandBus;
use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//snippet final-post-cheep-controller
final class PostCheepController extends AbstractController
{
    #[Route("/cheeps", methods: ["POST"])]
    public function __invoke(Request $request, CommandBus $bus, ValidatorInterface $validator): Response
    {
        $authorId = $request->request->get('author_id');
        $message = $request->request->get('message');

        if (null === $authorId || null === $message) {
            throw new BadRequestHttpException('Invalid parameters given');
        }

        $command = PostCheep::fromArray([
            'author_id' => $authorId,
            'cheep_id' => Uuid::uuid4()->toString(),
            'message' => $message,
        ]);

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            throw new BadRequestHttpException(
                $this->toJson($errors),
                null,
                0,
                ['Content-Type' => 'application/json']
            );
        }

        try {
            $bus->handle($command);
        } catch (AuthorDoesNotExist | InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        return new Response('', Response::HTTP_CREATED);
    }

    //ignore
    private function toJson(ConstraintViolationListInterface $errors): string
    {
        $json = [];

        foreach ($errors as $error) {
            $json[$error->getPropertyPath()] = $error->getMessage();
        }

        return json_encode($json, JSON_THROW_ON_ERROR);
    }
    //end-ignore
}
//end-snippet
