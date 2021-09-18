<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Projection;

class PostId
{
    public static function create(): \Architecture\CQRS\Domain\PostId
    {
        return new \Architecture\CQRS\Domain\PostId('irrelevant');
    }
}
