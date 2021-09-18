<?php

declare(strict_types=1);

namespace Architecture\ES\Infrastructure;

use Redis;
use Zumba\JsonSerializer\JsonSerializer;

//snippet snapshot-repository
final class RedisSnapshotRepository implements SnapshotRepository
{
    public function __construct(
        private Redis $redis,
        private JsonSerializer $serializer
    ) {
    }

    public function byId(string $id): ?Snapshot
    {
        $key = 'snapshots:' . $id;

        $data = $this->redis->get($key);

        if (false === $data) {
            return null;
        }

        $metadata = (array) $this->serializer->unserialize($data);

        $snapshot = (array) $metadata['snapshot'];

        $aggregate = $this->serializer->unserialize(
            (string) $snapshot['data']
        );

        return new Snapshot(
            $aggregate,
            (int) $metadata['version']
        );
    }

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
                        ),
                    ],
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
