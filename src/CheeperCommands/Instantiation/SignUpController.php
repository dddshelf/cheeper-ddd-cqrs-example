<?php

declare(strict_types=1);

namespace CheeperCommands\Instantiation;

use Cheeper\Application\Command\Author\SignUp;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//snippet signup-controller
final class SignUpController extends AbstractController
{
    /** @Route("/signup", methods={"POST"}) */
    public function __invoke(Request $request): Response
    {
        $command = new SignUp(
            Uuid::uuid4()->toString(),
            (string) $request->request->get('username'),
            (string) $request->request->get('email'),
            (string) $request->request->get('name'),
            (string) $request->request->get('biography'),
            (string) $request->request->get('location'),
            (string) $request->request->get('website'),
            (string) $request->request->get('birthdate'),
        );

        //ignore
        dump($command);

        return new Response();
        //end-ignore
    }
}
//end-snippet
