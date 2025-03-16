<?php

declare(strict_types=1);

namespace App\Tests\Domain\User;

use App\Domain\User\User;
use App\Domain\ValueObject\UuidV7;
use InvalidArgumentException;
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

    /**
     * @dataProvider provideCreateUserWithInvalidNameThrowsExceptionCases
     */
    public function testCreateUserWithInvalidNameThrowsException(?UuidV7 $id, string $name, string $phone, string $email): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);
        // Act
        new User(id: $id, name: $name, phone: $phone, email: $email);
    }

    public function provideCreateUserWithInvalidNameThrowsExceptionCases(): iterable
    {
        return [
            [null, '', '+79990000000', 'test@example.com'],
            [null, 'Иван123', '+79990000000', 'test@example.com'],
            [null, 'John_Doe', '+79990000000', 'test@example.com'],
        ];
    }

    /**
     * @dataProvider provideCreateUserWithInvalidPhoneThrowsExceptionCases
     */
    public function testCreateUserWithInvalidPhoneThrowsException(?UuidV7 $id, string $name, string $phone, string $email): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);
        // Act
        new User(id: $id, name: $name, phone: $phone, email: $email);
    }

    public function provideCreateUserWithInvalidPhoneThrowsExceptionCases(): iterable
    {
        return [
            [null, 'Ванек', '799912345', 'test@123.com'],
            [null, 'Ванюша', '+79991234', 'test@123.com'],
            [null, 'Ванос', '+79991234567890', 'test@test.com'],
        ];
    }

    /**
     * @dataProvider provideCreateUserWithInvalidEmailThrowsExceptionCases
     */
    public function testCreateUserWithInvalidEmailThrowsException(?UuidV7 $id, string $name, string $phone, string $email): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);
        // Act
        new User(id: $id, name: $name, phone: $phone, email: $email);
    }

    public function provideCreateUserWithInvalidEmailThrowsExceptionCases(): iterable
    {
        return [
            [null, 'Иван', '+79991234567', 'test'],
            [null, 'Иван', '+79991234567', 'test@'],
            [null, 'Иван', '+79991234567', 'test@example'],
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
