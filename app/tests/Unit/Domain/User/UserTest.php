<?php

declare(strict_types=1);

namespace App\Tests\Domain\User;

use App\Domain\User\User;
use App\Domain\ValueObject\UuidV7;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @dataProvider provideCreateUserWithValidDataSucceedsCases
     */
    public function testCreateUserWithValidDataSucceeds(?UuidV7 $id, string $name, string $phone, string $email): void
    {
        // Act
        $user = new User(id: $id, name: $name, phone: $phone, email: $email);
        // Assert
        self::assertInstanceOf(User::class, $user);
        self::assertEquals($name, $user->getName());
        self::assertEquals($phone, $user->getPhone());
        self::assertEquals($email, $user->getEmail());
        self::assertNotNull($user->getId());
    }

    public function provideCreateUserWithValidDataSucceedsCases(): iterable
    {
        return [
            [null, 'Иван', '+79990000000', 'test@test.com'],
            [null, 'Толя', '+7999000000', 'test2@test.com'],
            [null, 'Гослин', '+79990000000', 'test3@test.com'],
        ];
    }

    public function testGetUserPropertiesReturnsCorrectValues(): void
    {
        // Arrange
        $name = 'Иван';
        $phone = '+79991234567';
        $email = 'test@example.com';
        // Act
        $user = new User(id: null, name: $name, phone: $phone, email: $email);
        // Assert
        self::assertEquals($name, $user->getName());
        self::assertEquals($phone, $user->getPhone());
        self::assertEquals($email, $user->getEmail());
        self::assertNotNull($user->getId());
    }
}
