<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Persistence\Doctrine;

use Architecture\CQRS\Domain\Post;
use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Domain\PostRepository;
use Architecture\CQRS\Infrastructure\Projection\Projector;

use Doctrine\ORM\EntityManagerInterface;

//snippet doctrine-post-repository
final class DoctrinePostRepository implements PostRepository
{
    public function __construct(
        private EntityManagerInterface $em,
        private Projector $projector
    ) {
    }

    public function save(Post $post): void
    {
        $this->em->transactional(static function (EntityManagerInterface $em) use ($post): void {
            $em->persist($post);

            foreach ($post->recordedEvents() as $event) {
                $em->persist($event);
            }
        });

        $this->projector->project($post->recordedEvents());
    }

    public function byId(PostId $id): ?Post
    {
        return $this->em->find(Post::class, $id);
    }
}
//end-snippet
