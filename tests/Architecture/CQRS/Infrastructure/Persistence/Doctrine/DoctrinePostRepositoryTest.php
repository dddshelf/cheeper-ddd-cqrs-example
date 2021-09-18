<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Persistence\Doctrine;

use Architecture\CQRS\Domain\Post;

use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Domain\PostWasCreated;
use Architecture\CQRS\Infrastructure\Persistence\Doctrine\Types\PostIdType;
use Architecture\CQRS\Infrastructure\Projection\Elasticsearch\PostWasCreatedProjection;
use Architecture\CQRS\Infrastructure\Projection\Projector;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

use PHPUnit\Framework\TestCase;

final class DoctrinePostRepositoryTest extends TestCase
{
    private Projector $projector;
    private DoctrinePostRepository $postRepository;

    public function setUp(): void
    {
        if (!Type::hasType('post_id')) {
            Type::addType('post_id', PostIdType::class);
        }

        $em = EntityManager::create(
            ['url' => 'sqlite:///:memory:'],
            Setup::createXMLMetadataConfiguration(
                [__DIR__.'/../../../../../../src/Architecture/CQRS/Infrastructure/Persistence/Doctrine/Mapping/'],
                true
            )
        );

        $tool = new SchemaTool($em);

        $tool->createSchema([
            $em->getClassMetadata(Post::class),
            $em->getClassMetadata(PostWasCreated::class)
        ]);

        $this->projector = new Projector();
        $this->postRepository = new DoctrinePostRepository($em, $this->projector);
    }

    /** @test */
    public function itShouldPersistPost(): void
    {
        $p = Post::writeNewFrom('A title', 'Some content');

        $this->postRepository->save($p);

        $this->assertPostCreated($p->id(), 'A title', 'Some content');
    }

    private function assertPostCreated(PostId $id, string $title, string $content): void
    {
        $found = $this->postRepository->byId($id);
        $this->assertEquals($title, $found->title());
        $this->assertEquals($content, $found->content());
    }

    /** @test */
    public function itShouldProjectToElastic(): void
    {
        $client = \Elasticsearch\ClientBuilder::create()->build();
        $this->projector->register([
            new PostWasCreatedProjection($client)
        ]);

        $p = Post::writeNewFrom('A title', 'Some content');

        $this->postRepository->save($p);

        $params = [
            'index' => 'posts',
            'type'  => 'post',
            'id'    => $p->id()->id()
        ];

        $document = $client->get($params);
        $this->assertEquals([
            'title' => 'A title',
            'content' => 'Some content',
        ], $document['_source']);

        $client->delete($params);
        $client->indices()->delete(['index' => 'posts']);
    }
}
