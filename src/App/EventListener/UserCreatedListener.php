<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use App\Messenger\CommandBus;
use Cheeper\Application\Command\Author\SignUpBuilder;
use Doctrine\ORM\Event\LifecycleEventArgs;

final class UserCreatedListener
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public function postPersist(User $user, LifecycleEventArgs $event): void
    {
        $signUpBuilder = SignUpBuilder::create(
            authorId: $user->getId()->toString(),
            userName: $user->getUserIdentifier(),
            email: $user->getEmail()
        );

        $this->commandBus->handle(
            $signUpBuilder
                    ->biography($user->getBiography())
                    ->birthDate($user->getBirthDate()?->format('Y-m-d'))
                    ->location($user->getLocation())
                    ->name($user->getName())
                    ->website($user->getWebsite())
                ->build()
        );
    }
}
