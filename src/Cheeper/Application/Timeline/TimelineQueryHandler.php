<?php

declare(strict_types=1);

namespace Cheeper\Application\Timeline;

use App\Dto\CheepDto;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Psl\Vec;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class TimelineQueryHandler
{
    public function __construct(
        private readonly AuthorRepository $authorRepository,
        private readonly CheepRepository  $cheepRepository,
    ) {
    }

    public function __invoke(TimelineQuery $query): TimelineQueryResponse
    {
        $authorId = AuthorId::fromString($query->authorId);
        $author = $this->authorRepository->ofId($authorId);

        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        $cheeps = $this->cheepRepository->ofFollowingPeopleOf($author, $query->offset, $query->size);

        return new TimelineQueryResponse(
            Vec\map(
                $cheeps,
                static fn (Cheep $c) => CheepDto::assembleFrom($c),
            )
        );
    }
}
