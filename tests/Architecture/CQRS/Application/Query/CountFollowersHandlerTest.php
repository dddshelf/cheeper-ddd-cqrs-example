<?php

declare(strict_types=1);

namespace Tests\Architecture\CQRS\Application\Query;

use Architecture\CQRS\App\Entity\Followers;
use Architecture\CQRS\Application\Query\CountFollowers;
use Architecture\CQRS\Application\Query\CountFollowersHandler;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CountFollowersHandlerTest extends WebTestCase
{
    /** @test */
    public function countFollowers(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        /** @var EntityManagerInterface $em */
        $em = $doctrine->getManager();

        $userId = Uuid::uuid4();
        $em->persist(new Followers($userId, 50));

        /** @var CountFollowersHandler $handler */
        $handler = $container->get(CountFollowersHandler::class);
        /** @var Followers $result */
        $result = $handler(CountFollowers::ofUser($userId));

        $this->assertNotNull($result);
        $this->assertSame(50, $result->followers());
    }
}
