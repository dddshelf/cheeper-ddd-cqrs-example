<?php

namespace Architecture\ES\Infrastructure;

use Architecture\CQRS\Domain\AggregateRoot;

use Predis\Client;
use Zumba\JsonSerializer\JsonSerializer;

/**
 * @template T of AggregateRoot
 */
//snippet snapshot-repository
class SnapshotRepository
{
    /** @var Client<?string> */
    private Client $redis;
    private JsonSerializer $serializer;

    /** @param Client<?string> $redis */
    public function __construct(Client $redis, JsonSerializer $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    /** @return Snapshot<T>|null */
    public function byId(string $id): ?Snapshot
    {
        $key = 'snapshots:' . $id;

        /** @var ?string */
        $data = $this->redis->get($key);

        if (null === $data) {
            return null;
        }

        $metadata = (array) $this->serializer->unserialize($data);

        $snapshot = (array) $metadata['snapshot'];

        /** @var T $aggregate */
        $aggregate = $this->serializer->unserialize(
            (string) $snapshot['data']
        );

        return new Snapshot(
            $aggregate,
            (int) $metadata['version']
        );
    }

    /** @phpstan-param Snapshot<T> $snapshot */
    public function save(string $id, Snapshot $snapshot): void
    {
        $key = 'snapshots:' . $id;
        $aggregate = $snapshot->aggregate();

        $this->redis->set(
            $key,
            $this->serializer->serialize(
                [
                    'version' => $snapshot->version(),
                    'snapshot' => [
                        'type' => get_class($aggregate),
                        'data' => $this->serializer->serialize(
                            $aggregate
                        )
                    ]
                ]
            )
        );
    }

    public function has(string $id, int $version): bool
    {
        $snapshot = $this->byId($id);

        if (null === $snapshot) {
            return false;
        }

        return $snapshot->version() === $version;
    }
}
//end-snippet
