<?php

declare(strict_types=1);

namespace Doctrine\ORM
{
    /** @template T */
    abstract class AbstractQuery
    {
        /** @psalm-return EntityManagerInterface<T> */
        public function getEntityManager() { }

        /**
         * @psalm-param string|1|2|3|4|5|null $hydrationMode
         * @psalm-return T[]
         */
        public function getResult($hydrationMode = self::HYDRATE_OBJECT) { }

        /**
         * @psalm-param string|1|2|3|4|5|null $hydrationMode
         * @psalm-return ?T
         * @throws NonUniqueResultException
         */
        public function getOneOrNullResult($hydrationMode = null) { }

        /**
         * @psalm-param string|1|2|3|4|5|null $hydrationMode
         * @psalm-return T
         * @throws NonUniqueResultException
         * @throws NoResultException
         */
        public function getSingleResult($hydrationMode = null) { }

        /**
         * @psalm-param ArrayCollection|array|null $parameters
         * @psalm-param string|1|2|3|4|5|null $hydrationMode
         * @psalm-return \Doctrine\ORM\Internal\Hydration\IterableResult<T>
         */
        public function iterate($parameters = null, $hydrationMode = null) { }

        /**
         * @psalm-param ArrayCollection|array|null $parameters
         * @psalm-param string|1|2|3|4|5|null $hydrationMode
         * @psalm-return T|T[]|null
         */
        public function execute($parameters = null, $hydrationMode = null) { }
    }

    /**
     * @template T
     * @template-extends AbstractQuery<T>
     */
    final class Query extends AbstractQuery {}

    /**
     * @template T
     */
    class QueryBuilder
    {
        /** @psalm-return \Doctrine\ORM\Query<T> */
        public function getQuery() { }
    }

    /** @template T */
    class EntityRepository
    {
        /**
         * @psalm-param string $alias
         * @psalm-param string $indexBy
         * @psalm-return \Doctrine\ORM\QueryBuilder<T>
         */
        public function createQueryBuilder($alias, $indexBy = null) { }
    }
}

namespace Doctrine\ORM\Internal\Hydration
{
    /**
     * @template T
     * @template-implements \Iterator<T>
     */
    class IterableResult implements \Iterator { }
}
