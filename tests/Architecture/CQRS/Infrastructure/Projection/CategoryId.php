<?php

namespace Architecture\CQRS\Infrastructure\Projection;

class CategoryId
{
    public static function create(): \Architecture\CQRS\Domain\CategoryId
    {
        return new \Architecture\CQRS\Domain\CategoryId('irrelevant');
    }
}
