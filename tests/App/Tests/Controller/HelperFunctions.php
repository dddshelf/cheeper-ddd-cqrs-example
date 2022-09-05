<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Faker\Factory as FakerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psl\Json;
use Psl\Type;

/**
 * @psalm-type AuthorResponse = array{id: non-empty-string, userName: non-empty-string, email: non-empty-string, name: non-empty-string|null, biography: non-empty-string|null, location: non-empty-string|null, website: non-empty-string|null, birthDate: non-empty-string|null}
 * @psalm-type CheepResponse = array{id: non-empty-string, authorId: non-empty-string, text: non-empty-string, createdAt: non-empty-string}
 */
trait HelperFunctions
{
    /** @psalm-return AuthorResponse */
    private function createAuthor(Client $client, array $data): array
    {
        $response = $client->request(Request::METHOD_POST, "/api/authors", [
            'json' => $data
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        return Json\typed(
            $response->getContent(),
            Type\shape([
                'id' => Type\non_empty_string(),
                'userName' => Type\non_empty_string(),
                'email' => Type\non_empty_string(),
                'name' => Type\union(Type\non_empty_string(), Type\null()),
                'biography' => Type\union(Type\non_empty_string(), Type\null()),
                'location' => Type\union(Type\non_empty_string(), Type\null()),
                'website' => Type\union(Type\non_empty_string(), Type\null()),
                'birthDate' => Type\union(Type\non_empty_string(), Type\null()),
            ])
        );
    }

    /** @psalm-return AuthorResponse */
    private function createAuthorWithRandomizedData(Client $client): array
    {
        $faker = FakerFactory::create();

        return $this->createAuthor($client, [
            'username' => $faker->userName(),
            'email' => $faker->email(),
            'name' => $faker->name(),
            'biography' => $faker->sentence(),
            'location' => $faker->country(),
            'website' => $faker->url(),
            'birth_date' => $faker->dateTime()->format('Y-m-d'),
        ]);
    }

    private function makeFollow(Client $client, string $fromAuthorId, string $toAuthorId): void
    {
        $client->request(Request::METHOD_POST, "/api/followers", [
            'json' => [
                'from_author_id' => $fromAuthorId,
                'to_author_id' => $toAuthorId,
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    /** @psalm-return CheepResponse */
    private function makeRandomizedCheep(Client $client, string $authorUserName): array
    {
        $faker = FakerFactory::create();

        $response = $client->request(Request::METHOD_POST, "/api/cheeps", [
            'json' => [
                'username' => $authorUserName,
                'message' => $faker->text(260),
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $headers = $response->getHeaders();
        $cheepUri = $headers["location"][0];
        $cheepResponse = $client->request(Request::METHOD_GET, parse_url($cheepUri, PHP_URL_PATH));

        return Json\typed(
            $cheepResponse->getContent(),
            Type\shape([
                'id' => Type\non_empty_string(),
                'authorId' => Type\non_empty_string(),
                'text' => Type\non_empty_string(),
                'createdAt' => Type\non_empty_string(),
            ])
        );
    }

    /** @psalm-return list<AuthorResponse> */
    private function getAuthors(Client $client): array
    {
        $response = $client->request(Request::METHOD_GET, "/api/authors");

        return Json\typed(
            $response->getContent(),
            Type\vec(
                Type\shape([
                    'id' => Type\non_empty_string(),
                    'userName' => Type\non_empty_string(),
                    'email' => Type\non_empty_string(),
                    'name' => Type\union(Type\non_empty_string(), Type\null()),
                    'biography' => Type\union(Type\non_empty_string(), Type\null()),
                    'location' => Type\union(Type\non_empty_string(), Type\null()),
                    'website' => Type\union(Type\non_empty_string(), Type\null()),
                    'birthDate' => Type\union(Type\non_empty_string(), Type\null()),
                ])
            )
        );
    }

    /** @psalm-return list<CheepResponse> */
    private function getAuthorTimeline(Client $client, string $authorId): array
    {
        $response = $client->request(Request::METHOD_GET, "/api/authors/${authorId}/timeline");

        $this->assertResponseIsSuccessful();

        return Json\typed(
            $response->getContent(),
            Type\vec(
                Type\shape([
                    'id' => Type\non_empty_string(),
                    'authorId' => Type\non_empty_string(),
                    'text' => Type\non_empty_string(),
                    'createdAt' => Type\non_empty_string(),
                ])
            )
        );
    }

    private function getFollowersCount(Client $client, string $authorId): int
    {
        $response = $client->request(Request::METHOD_GET, "/api/authors/${authorId}/followers/total");

        $this->assertResponseIsSuccessful();

        $responseBody = Json\typed(
            $response->getContent(),
            Type\shape([
                'count' => Type\int()
            ])
        );

        return $responseBody['count'];
    }
}