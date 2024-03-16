<?php

declare(strict_types=1);


namespace Cheeper\Chapter9\DomainModel\Author;

use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\DomainModel\DomainEvent;

final readonly class AuthorWasUpgraded implements DomainEvent
{
    use MessageTrait;

    public function __construct(
        public string $authorId
    ) {
    }
}