<?php

declare(strict_types=1);

namespace CheeperCommands\Validation;

use Cheeper\Application\Command\Author\SignUp;
use Cheeper\Application\Command\Cheep\PostCheep;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//snippet validated-signup-controller
final class PostCheepController extends AbstractController
{
    #[Route("/cheep", methods: ["POST"])]
    public function __invoke(
        Request $request,
        ValidatorInterface $validator
    ): Response {
        $command = PostCheep::fromArray([
            'author_id' => $request->request->getAlpha('authorId'),
            'cheep_id' => Uuid::uuid4()->toString(),
            'message' => $request->request->getAlpha('message'),
        ]);

        $errors = $validator->validate($command);

        if (count($errors) > 0) {
            throw new BadRequestHttpException(
                $this->toJson($errors),
                null,
                0,
                [
                    'Content-Type' => 'application/json'
                ]
            );
        }

        //ignore
        return new Response();
        //end-ignore
    }

    private function toJson(ConstraintViolationListInterface $errors): string
    {
        $json = [];

        foreach ($errors as $error) {
            $json[$error->getPropertyPath()] = $error->getMessage();
        }

        return json_encode($json, JSON_THROW_ON_ERROR);
    }
}
//end-snippet
