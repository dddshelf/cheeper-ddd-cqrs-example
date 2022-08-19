<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;

final class PostCheepControllerTest extends ApiTestCase
{
    use HelperFunctions;

    /** @test */
    public function itPostsACheep(): void
    {
        $client = self::createClient();

        $autorData = $this->createAuthorWithRandomizedData($client);
        $cheepData = $this->makeRandomizedCheep($client, $autorData['userName']);

        $response = $client->request(Request::METHOD_GET, "/api/cheeps/${cheepData['id']}");

        $this->assertResponseIsSuccessful();

        $data = $response->toArray();

        $this->assertEquals($cheepData, $data);
    }
}