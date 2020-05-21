<?php

declare(strict_types=1);

namespace Functional;

/**
 * @template A
 *
 * @psalm-param \Traversable<A>|list<A> $collection
 * @psalm-param callable(A, mixed=, A[]): bool $callback
 * @psalm-return list<A>
 */
function select($collection, callable $callback) {}

/**
 * @template B
 *
 * @psalm-param \Traversable<B>|list<B> $collection
 * @psalm-param callable(B, mixed=, B[]): bool $callback
 * @psalm-return B
 */
function head($collection, callable $callback = null) {}
