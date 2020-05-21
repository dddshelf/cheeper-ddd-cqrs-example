<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Traits\RefreshDatabase;
use Faker;
use Ramsey\Uuid\Uuid;

final class SignUpTest extends ApiTestCase
{
    use RefreshDatabase;

    /** @test */
    public function post(): void
    {
        $faker = Faker\Factory::create();

        static::createClient()->request('POST', '/api/authors', [
            'json' => [
                'authorId' => Uuid::uuid4(),
                'userName' => $faker->userName,
                'name' => $faker->name,
                'biography' => $faker->paragraph,
                'location' => $faker->word,
                'website' => $faker->url,
                'birthDate' => $faker->date()
            ]
        ]);

        self::assertResponseIsSuccessful();
    }
}
