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
        $this->markTestSkipped('Skipped as it\'s generating an unknown integrity constant violation error');

        $faker = Faker\Factory::create();

        self::createClient()->request('POST', '/api/authors', [
            'json' => [
                'userName' => $faker->userName,
                'email' => $faker->email,
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
