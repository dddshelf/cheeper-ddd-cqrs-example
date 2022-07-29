<?php

declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\FollowApplicationService;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetFollowersCountController extends AbstractController
{
    public function __construct(
        private readonly FollowApplicationService $followApplicationService,
    ) {
    }

    #[Route(path: "/authors/{authorId}/followers/total", methods: [Request::METHOD_GET])]
    public function __invoke(string $authorId): Response
    {
        try {
            $count = $this->followApplicationService->countFollowersOf($authorId);
            return $this->json([
                'count' => $count
            ]);
        } catch (AuthorDoesNotExist $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }
}