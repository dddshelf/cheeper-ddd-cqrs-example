<?php

declare(strict_types=1);

namespace App\Controller\Chapter4\Commands\Instantiation;

use Cheeper\Application\Command\Cheep\PostCheep;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//snippet commands-instantiation
final class PostCheepController extends AbstractController
{
    #[Route("/chapter4/commands/instantiation/cheeps", methods: ["POST"])]
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
