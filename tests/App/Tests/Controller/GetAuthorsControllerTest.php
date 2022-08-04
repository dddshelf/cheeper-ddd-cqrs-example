<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Component\HttpFoundation\Request;

final class GetAuthorsControllerTest extends ApiTestCase
{
    use HelperFunctions;

    /** @test */
    public function itReturnsAllAuthors(): void
    {
        $client = self::createClient();

        $firstAuthorsResponse = $this->getAuthors($client);

        $this->assertIsArray($firstAuthorsResponse);

        $this->createAuthorWithRandomizedData($client);

        $secondAuthorsResponse = $this->getAuthors($client);

        $this->assertCount(count($firstAuthorsResponse) + 1, $secondAuthorsResponse);
    }
}