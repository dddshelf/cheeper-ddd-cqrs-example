<?php

declare(strict_types=1);

namespace CheeperCommands\Instantiation;

use Cheeper\Application\Command\Author\SignUp;
use Cheeper\Application\Command\Cheep\PostCheep;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//snippet signup-controller
final class PostCheepController extends AbstractController
{
    /** @Route("/cheep", methods={"POST"}) */
    public function __invoke(Request $request): Response
    {
        $command = PostCheep::fromArray([
            'author_id' => $request->request->getAlpha('authorId'),
            'cheep_id' => Uuid::uuid4()->toString(),
            'message' => $request->request->getAlpha('message'),
        ]);

        //ignore
        dump($command);

        return new Response();
        //end-ignore
    }
}
//end-snippet
