<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use App\Messenger\CommandBus;
use Cheeper\Chapter7\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\FollowId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus
    )
    {
    }

    #[Route("/chapter7/follow", methods: ["POST"])]
    public function follow(Request $request): Response
    {
        $this->commandBus->handle(
            Follow::fromAuthorToAuthor(
                FollowId::nextIdentity(),
                AuthorId::nextIdentity(),
                AuthorId::nextIdentity(),
            )
        );

        return $this->json("hello");
    }
}
