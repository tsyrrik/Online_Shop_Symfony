<?php

declare(strict_types=1);

namespace App\Tests\Domain\User;

use App\Domain\User\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @dataProvider provideCreateUserWithValidDataSucceedsCases
     */
    public function testCreateUserWithValidDataSucceeds(string $name, string $phone, string $email): void
    {
        // Arrange
        $expectedId = null;

        // Act
        $user = new User(name: $name, phone: $phone, email: $email);

        // Assert
        self::assertInstanceOf(expected: User::class, actual: $user);
        self::assertEquals(expected: $name, actual: $user->getName());
        self::assertEquals(expected: $phone, actual: $user->getPhone());
        self::assertEquals(expected: $email, actual: $user->getEmail());
        self::assertNull(actual: $user->getId());
    }

    public function provideCreateUserWithValidDataSucceedsCases(): iterable
    {
        return [
            ['Иван', '+79990000000', 'test@test.com'],
            ['Толя', '+7999000000', 'test2@test.com'],
            ['Гослин', '+79990000000', 'test3@test.com'],
        ];
    }

    /**
     * @dataProvider provideCreateUserWithInvalidNameThrowsExceptionCases
     */
    public function testCreateUserWithInvalidNameThrowsException(string $name, string $phone, string $email): void
    {
        // Arrange
        $this->expectException(exception: InvalidArgumentException::class);

        // Act
        new User(name: $name, phone: $phone, email: $email);

        // Assert
    }

    public function provideCreateUserWithInvalidNameThrowsExceptionCases(): iterable
    {
        return [
            ['', '+79990000000', 'test@example.com'],
            ['Иван123', '+79990000000', 'test@example.com'],
            ['John_Doe', '+79990000000', 'test@example.com'],
        ];
    }

    /**
     * @dataProvider provideCreateUserWithInvalidPhoneThrowsExceptionCases
     */
    public function testCreateUserWithInvalidPhoneThrowsException(string $name, string $phone, string $email): void
    {
        // Arrange
        $this->expectException(exception: InvalidArgumentException::class);

        // Act
        new User(name: $name, phone: $phone, email: $email);

        // Assert
    }

    public function provideCreateUserWithInvalidPhoneThrowsExceptionCases(): iterable
    {
        return [
            ['Ванек', '799912345', 'test@123.com'],
            ['Ванюша', '+79991234', 'test@123.com'],
            ['Ванос', '+79991234567890', 'test@test.com'],
        ];
    }

    /**
     * @dataProvider provideCreateUserWithInvalidEmailThrowsExceptionCases
     */
    public function testCreateUserWithInvalidEmailThrowsException(string $name, string $phone, string $email): void
    {
        // Arrange
        $this->expectException(exception: InvalidArgumentException::class);

        // Act
        new User(name: $name, phone: $phone, email: $email);

        // Assert
    }

    public function provideCreateUserWithInvalidEmailThrowsExceptionCases(): iterable
    {
        return [
            ['Иван', '+79991234567', 'test'],
            ['Иван', '+79991234567', 'test@'],
            ['Иван', '+79991234567', 'test@example'],
        ];
    }

    public function testGetUserPropertiesReturnsCorrectValues(): void
    {
        // Arrange
        $name = 'Иван';
        $phone = '+79991234567';
        $email = 'test@example.com';
        $expectedId = null;

        // Act
        $user = new User(name: $name, phone: $phone, email: $email);

        // Assert
        self::assertEquals(expected: $name, actual: $user->getName());
        self::assertEquals(expected: $phone, actual: $user->getPhone());
        self::assertEquals(expected: $email, actual: $user->getEmail());
        self::assertNull(actual: $user->getId());
    }
}
