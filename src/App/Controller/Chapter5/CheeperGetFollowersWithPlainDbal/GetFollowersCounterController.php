<?php

declare(strict_types=1);

namespace App\Controller\Chapter5\CheeperGetFollowersWithPlainDbal;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class GetFollowersCounterController extends AbstractController
{
    public function __construct(
        private Connection $dbal
    )
    {
    }

//    #[Route("/api/followers-counter/{userId}", methods:["GET"])]
    public function __invoke(string $userId): JsonResponse
    {
        $numberOfFollowers = $this->dbal->executeQuery(
            'SELECT count(*) FROM authors a, user_followers f WHERE a.author_id_id = f.user_id AND user_id = ?',
            [$userId]
        )->fetchNumeric();

        $numberOfFollowers = $this->dbal->executeQuery(
            'SELECT * FROM authors a WHERE a.author_id_id = f.user_id AND user_id = ?',
            [$userId]
        )->fetchNumeric();

        return $this->json([$result]);
    }
}
