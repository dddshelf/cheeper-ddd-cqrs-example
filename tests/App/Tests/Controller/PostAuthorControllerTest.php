<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

final class PostAuthorControllerTest extends ApiTestCase
{
    use HelperFunctions;

    /** @test */
    public function itSignsUpNewAuthors(): void
    {
        $client = self::createClient();

        $newAuthor = $this->createAuthorWithRandomizedData($client);
        $authors = $this->getAuthors($client);

        $this->assertContains($newAuthor, $authors);
    }
}