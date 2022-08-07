<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Faker\Factory as FakerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait HelperFunctions
{
    private function createAuthor(Client $client, array $data): array
    {
        $client->request(Request::METHOD_POST, "/api/authors", [
            'json' => $data
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        return $client->getResponse()->toArray();
    }

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

    private function makeRandomizedCheep(Client $client, string $authorUserName): array
    {
        $faker = FakerFactory::create();

        $client->request(Request::METHOD_POST, "/api/cheeps", [
            'json' => [
                'username' => $authorUserName,
                'message' => $faker->text(260),
            ]
        ]);

        $this->assertResponseIsSuccessful();

        return $client->getResponse()->toArray();
    }

    private function getAuthors(Client $client): array
    {
        $client->request(Request::METHOD_GET, "/api/authors");

        return $client->getResponse()->toArray();
    }

    private function getAuthorTimeline(Client $client, string $authorId): array
    {
        $client->request(Request::METHOD_GET, "/api/authors/${authorId}/timeline");

        $this->assertResponseIsSuccessful();

        return $client->getResponse()->toArray();
    }

    private function getFollowersCount(Client $client, string $authorId): int
    {
        $client->request(Request::METHOD_GET, "/api/authors/${authorId}/followers/total");

        $this->assertResponseIsSuccessful();

        return $client->getResponse()->toArray()['count'];
    }
}