<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Author;

use Cheeper\Application\Command\Author\SignUp as SignUpCommand;
use Cheeper\Application\Command\Author\SignUpBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SignUp {
    //snippet builder-method
    public static function builder(string $authorId, string $userName): SignUpBuilder
    {
        return SignUpBuilder::create($authorId, $userName);
    }
    //end-snippet
}

final class SignUpBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function itBuildsRequiredFields(): void
    {
        //snippet basic-builder-usage
        $command = SignUp::builder(Uuid::uuid4()->toString(), 'johndoe')->build();
        //end-snippet

        $this->assertInstanceOf(SignUpCommand::class, $command);
    }

    /**
     * @test
     */
    public function itBuildsOptionalFields(): void
    {
        //snippet sophisticated-builder-usage
        $command = SignUp::builder(Uuid::uuid4()->toString(), 'johndoe')
            ->name('John Doe')
            ->website('https://johndoe.com')
            ->biography('An example author')
            ->build();
        //end-snippet

        $this->assertInstanceOf(SignUpCommand::class, $command);
    }
}
