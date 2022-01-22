<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Chapter5\Application\Query\QueryBus;
use Cheeper\Chapter7\Application\Query\Author\CountFollowers;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

//snippet follow-counter-controller
final class CountFollowersController extends AbstractController
{
    #[Route(path: "/chapter7/author/{authorId}/followers-counter", methods: ["GET"])]
    public function __invoke(string $authorId, QueryBus $queryBus, SerializerInterface $serializer, Request $request): Response
    {
        $httpCode = Response::HTTP_ACCEPTED;
        $httpContent = [
            '_meta' => [],
            'data' => []
        ];

        try {
            $query = CountFollowers::ofAuthor($authorId);
            $timeline = $queryBus->query($query);
            $httpContent['_meta']['message_id'] = $query->messageId()?->toString();
            $httpContent['data'] = $timeline;
        } catch (AuthorDoesNotExist $e) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $httpContent['data'] = $e->getMessage();
        } catch (InvalidArgumentException $e) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $httpContent['data'] = $e->getMessage();
        }

        return $this->json(
            $httpContent,
            $httpCode
        );
    }
}
//end-snippet