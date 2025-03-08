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
        // Act
        $user = new User($name, $phone, $email);
        // Assert
        self::assertInstanceOf(User::class, $user);
        self::assertEquals($name, $user->getName());
        self::assertEquals($phone, $user->getPhone());
        self::assertEquals($email, $user->getEmail());
        self::assertNull($user->getId());
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
        $this->expectException(InvalidArgumentException::class);
        // Act
        new User($name, $phone, $email);
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
        $this->expectException(InvalidArgumentException::class);
        // Act
        new User($name, $phone, $email);
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
        $this->expectException(InvalidArgumentException::class);
        // Act
        new User($name, $phone, $email);
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
        // Act
        $user = new User($name, $phone, $email);
        // Assert
        self::assertEquals($name, $user->getName());
        self::assertEquals($phone, $user->getPhone());
        self::assertEquals($email, $user->getEmail());
        self::assertNull($user->getId());
    }
}
