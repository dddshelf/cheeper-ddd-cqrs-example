<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Traits\RefreshDatabase;
use Faker;
use Ramsey\Uuid\Uuid;

final class FollowTest extends ApiTestCase
{
    use RefreshDatabase;

    /** @test */
    public function post(): void
    {
        $faker = Faker\Factory::create();

        $client = static::createClient();

        $from = $faker->userName;

        $client->request('POST', '/api/authors', [
            'json' => [
                'authorId' => Uuid::uuid4(),
                'userName' => $from,
                'name' => $faker->name,
                'biography' => $faker->paragraph,
                'location' => $faker->word,
                'website' => $faker->url,
                'birthDate' => $faker->date()
            ]
        ]);

        $to = $faker->userName;

        $client->request('POST', '/api/authors', [
            'json' => [
                'authorId' => Uuid::uuid4(),
                'userName' => $to,
                'name' => $faker->name,
                'biography' => $faker->paragraph,
                'location' => $faker->word,
                'website' => $faker->url,
                'birthDate' => $faker->date()
            ]
        ]);

        $client->request('POST', '/api/followers', [
            'json' => [
                'from' => $from,
                'to' => $to,
            ]
        ]);

        self::assertResponseIsSuccessful();
    }
}
