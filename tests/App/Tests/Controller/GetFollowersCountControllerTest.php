<?php

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Faker\Factory as FakerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetFollowersCountControllerTest extends ApiTestCase
{
    /** @test */
    public function itReturnsTheNumberOfFollowersOfAUser(): void
    {
        $client = self::createClient();
        $faker = FakerFactory::create();

        // Register first author
        $client->request(Request::METHOD_POST, "/authors", [
            'json' => [
                'username' => $faker->userName(),
                'email' => $faker->email(),
                'name' => $faker->name(),
                'biography' => $faker->sentence(),
                'location' => $faker->country(),
                'website' => $faker->url(),
                'birth_date' => $faker->dateTime()->format('Y-m-d')
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $response = $client->getResponse();
        $firstAuthorData = $response->toArray();

        // Register second author
        $client->request(Request::METHOD_POST, "/authors", [
            'json' => [
                'username' => $faker->userName(),
                'email' => $faker->email(),
                'name' => $faker->name(),
                'biography' => $faker->sentence(),
                'location' => $faker->country(),
                'website' => $faker->url(),
                'birth_date' => $faker->dateTime()->format('Y-m-d')
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $response = $client->getResponse();
        $secondAuthorData = $response->toArray();

        // Make first follow second author
        $client->request(Request::METHOD_POST, "/followers", [
            'json' => [
                'from_author_id' => $firstAuthorData['id'],
                'to_author_id' => $secondAuthorData['id'],
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $client->request(Request::METHOD_GET, "/authors/${secondAuthorData['id']}/followers/total");

        $this->assertJsonContains([
            'count' => 1
        ]);
    }
}
