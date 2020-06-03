<?php

declare(strict_types=1);

namespace Cheeper\Tests\DomainModel\Cheep;

use Cheeper\DomainModel\Cheep\CheepMessage;
use PHPUnit\Framework\TestCase;

final class CheepMessageTest extends TestCase
{
    /** @test */
    public function throwsAnExceptionWhenMessageIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        CheepMessage::write('');
    }

    /** @test */
    public function throwsAnExceptionWhenMessageIsTooLong(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        CheepMessage::write(str_repeat('a', 1500));
    }
}
