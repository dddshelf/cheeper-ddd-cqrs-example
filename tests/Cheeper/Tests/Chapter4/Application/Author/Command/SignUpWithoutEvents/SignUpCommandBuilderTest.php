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
            ->birthDate('31/01/1983')
            ->location('California')
            ->build();
        //end-snippet

        $this->assertInstanceOf(SignUpCommand::class, $command);
    }

    /** @test */
    public function itBuildsOptionalFieldsAndCanUpdateMandatoryOnesBeforeBuilding(): void
    {
        $location = 'California';
        $name = 'John Doe';
        $newUserName = 'new_johndoe';

        $command = SignUpCommandBuilderMock::builder(Uuid::uuid4()->toString(), 'johndoe', 'test@email.com')
            ->name($name)
            ->website('https://johndoe.com')
            ->biography('An example author')
            ->birthDate('31/01/1983')
            ->location($location)
            ->username($newUserName)
            ->email('john@doe.domain')
            ->build();

        $this->assertInstanceOf(SignUpCommand::class, $command);
        $this->assertSame($command->location(), $location);
        $this->assertSame($command->username(), $newUserName);
    }
}
