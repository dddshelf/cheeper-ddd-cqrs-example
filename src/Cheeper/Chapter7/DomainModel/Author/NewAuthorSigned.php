<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Author;

use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\DomainModel\Author\NewAuthorSigned as NewAuthorSignedChapter6;

final class NewAuthorSigned extends NewAuthorSignedChapter6
{
    use MessageTrait;
}
