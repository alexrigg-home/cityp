<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class emailTest extends TestCase
{
    public function testCanBECreatedFromEmail(): void
    {
        
        $this->assertInstanceOf(
            email::class,
            email::fromString('user@example.com')
        );
    }
}