<?php

declare(strict_types=1);

namespace CheeperCommands\Validation;

use Cheeper\Application\Command\Author\SignUp;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//snippet validated-signup-controller
final class SignUpController extends AbstractController
{
    /** @Route("/signup", methods={"POST"}) */
    public function __invoke(Request $request, ValidatorInterface $validator): Response
    {
        $command = new SignUp(
            Uuid::uuid4()->toString(),
            (string)$request->request->get('username'),
            (string)$request->request->get('email'),
            (string)$request->request->get('name'),
            (string)$request->request->get('biography'),
            (string)$request->request->get('location'),
            (string)$request->request->get('website'),
            (string)$request->request->get('birthdate'),
        );

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

        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $json[$error->getPropertyPath()] = $error->getMessage();
        }

        return json_encode($json, JSON_THROW_ON_ERROR);
    }
}
//end-snippet
