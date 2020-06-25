<?php

namespace Architecture\CQRS\Infrastructure\Persistence\Doctrine;

use Architecture\CQRS\Domain\Post;
use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Domain\PostRepository;
use Architecture\CQRS\Infrastructure\Projection\Projector;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @psalm-import-type PostEvents from Post
 */
//snippet doctrine-post-repository
class DoctrinePostRepository implements PostRepository
{
    private EntityManagerInterface $em;
    /** @var Projector<PostEvents>  */
    private Projector $projector;

    /** @param Projector<PostEvents> $projector */
    public function __construct(EntityManagerInterface $em, Projector $projector)
    {
        $this->em = $em;
        $this->projector = $projector;
    }

    public function save(Post $post): void
    {
        $this->em->transactional(static function(EntityManagerInterface $em) use ($post): void {
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
