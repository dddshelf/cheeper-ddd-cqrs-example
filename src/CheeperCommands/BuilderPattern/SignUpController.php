<?php

declare(strict_types=1);

namespace CheeperCommands\BuilderPattern;

use Cheeper\Application\Command\Author\SignUp;
use Cheeper\Application\Command\Author\SignUpBuilder;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//snippet signup-controller-with-builder
final class SignUpController extends AbstractController
{
    /** @Route("/signup", methods={"POST"}) */
    public function __invoke(Request $request): Response
    {
        $command = SignUpBuilder::create()
            ->withUserName((string)$request->request->get('username'))
            ->withName((string)$request->request->get('name'))
            ->withBiography((string)$request->request->get('biography'))
            ->withLocation((string)$request->request->get('location'))
            ->withWebsite((string)$request->request->get('website'))
            ->withBirthDate((string)$request->request->get('birthdate'))
            ->build()
        ;

        //ignore
        dump($command);

        return new Response();
        //end-ignore
    }
}
//end-snippet
