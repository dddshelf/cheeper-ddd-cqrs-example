<?php

declare(strict_types=1);

namespace CheeperCommandBus\SymfonyMessenger;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

//snippet doctrine-transactional-middleware
final class DoctrineTransactionalMiddleware implements MiddlewareInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        /** @var Envelope $result */
        $result = $this->em->transactional(static function () use ($envelope, $stack): Envelope {
            return $stack->next()->handle($envelope, $stack);
        });

        return $result;
    }
}
//end-snippet
