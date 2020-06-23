<?php

declare(strict_types=1);

namespace CheeperCommandBus\Simplest;

use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SignUpController extends AbstractController
{
    private PostCheepHandler $postCheepHandler;

    public function __invoke(Request $request): Response
    {
//        $command = PostCheep::fromArray(['test' => 'test']);

        return new Response();
    }
}
