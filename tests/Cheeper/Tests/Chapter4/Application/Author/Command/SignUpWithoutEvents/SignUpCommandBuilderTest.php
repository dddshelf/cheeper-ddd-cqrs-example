<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter4\Application\Author\Command\SignUpWithoutEvents;

use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommand;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommandBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SignUpCommandBuilderMock {
    //snippet builder-method
    public static function builder(
        string $authorId,
        string $userName,
        string $email
    ): SignUpCommandBuilder
    {
        return SignUpCommandBuilder::create($authorId, $userName, $email);
    }
    //end-snippet
}

final class SignUpCommandBuilderTest extends TestCase
{
    /** @test */
    public function itBuildsRequiredFields(): void
    {
        //snippet basic-builder-usage
        $authorId = Uuid::uuid4()->toString();
        $command = SignUpCommandBuilderMock::builder($authorId, 'johndoe', 'test@email.com')->build();
        //end-snippet

        $this->assertInstanceOf(SignUpCommand::class, $command);
    }

    /** @test */
    public function itBuildsOptionalFields(): void
    {
        //snippet sophisticated-builder-usage
        $command = SignUpCommandBuilderMock::builder(Uuid::uuid4()->toString(), 'johndoe', 'test@email.com')
            ->name('John Doe')
            ->website('https://johndoe.com')
            ->biography('An example author')
            ->build();
        //end-snippet

        $this->assertInstanceOf(SignUpCommand::class, $command);
    }
}
