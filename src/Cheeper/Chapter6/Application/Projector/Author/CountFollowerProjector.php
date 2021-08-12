<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Projector\Author;

use Cheeper\Chapter6\Application\Projector\CountFollowers;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Follow\Follows;
use Doctrine\DBAL\Portability\Connection;
use Predis\ClientInterface as Redis;

final class CountFollowerProjector
{
    public function __construct(
        Redis $redis,
        Con
    ) { }

    public function __invoke(CountFollowers $command): void
    {

    }
}
