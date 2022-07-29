<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\CheepApplicationService;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

final class CheepApplicationServiceTest extends TestCase
{
    private CheepRepository $cheeps;
    private AuthorRepository $authorRepository;
    private CheepApplicationService $cheepService;

    public function setUp(): void
    {
        $this->cheeps = Mockery::mock(CheepRepository::class);
        $this->authorRepository = Mockery::mock(AuthorRepository::class);
        $this->cheepService = new CheepApplicationService($this->authorRepository, $this->cheeps);
    }

    /** @test */
    public function itRaisesExceptionWhenAuthorNotFound(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->authorRepository->allows('ofUserName')->andReturns(null);

        $this->cheepService->postCheep('irrelevant', 'irrelevant');
    }

    /** @test */
    public function itShouldAddCheep(): void
    {
        $this->authorRepository->allows('ofUsername')->andReturns(self::anAuthor());
        $this->cheeps->expects('add');

        $cheep = $this->cheepService->postCheep('irrelevant', 'message');

        $this->assertNotNull($cheep);
        $this->assertNotNull($cheep->authorId());
        $this->assertEquals('message', $cheep->cheepMessage()->message());
    }

    private static function anAuthor(): Author
    {
        return Author::signUp(
            AuthorId::nextIdentity(),
            UserName::pick('irrelevant'),
            EmailAddress::from('test@gmail.com')
        );
    }
}
