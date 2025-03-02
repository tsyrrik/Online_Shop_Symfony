<?php

declare(strict_types=1);

namespace App\Tests\Domain\User;

use App\Domain\User\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     * @dataProvider validDataProvider
     */
    public function testCreateUserWithValidDataSucceeds(string $name, string $phone, string $email): void
    {
        // Arrange
        $expectedId = null;

        // Act
        $user = new User(name: $name, phone: $phone, email: $email);

        // Assert
        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertEquals(expected: $name, actual: $user->getName());
        $this->assertEquals(expected: $phone, actual: $user->getPhone());
        $this->assertEquals(expected: $email, actual: $user->getEmail());
        $this->assertNull(actual: $user->getId());
    }

    public function validDataProvider(): array
    {
        return [
            ['Иван', '+79990000000', 'test@test.com'],
            ['Толя', '+7999000000', 'test2@test.com'],
            ['Гослин', '+79990000000', 'test3@test.com'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidNameDataProvider
     */
    public function testCreateUserWithInvalidNameThrowsException(string $name, string $phone, string $email): void
    {
        // Arrange
        $this->expectException(exception: \InvalidArgumentException::class);

        // Act
        new User(name: $name, phone: $phone, email: $email);

        // Assert
    }

    public function invalidNameDataProvider(): array
    {
        return [
            ['', '+79990000000', 'test@example.com'],
            ['Иван123', '+79990000000', 'test@example.com'],
            ['John_Doe', '+79990000000', 'test@example.com'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidPhoneDataProvider
     */
    public function testCreateUserWithInvalidPhoneThrowsException(string $name, string $phone, string $email): void
    {
        // Arrange
        $this->expectException(exception: \InvalidArgumentException::class);

        // Act
        new User(name: $name, phone: $phone, email: $email);

        // Assert
    }

    public function invalidPhoneDataProvider(): array
    {
        return [
            ['Ванек', '799912345', 'test@123.com'],
            ['Ванюша', '+79991234', 'test@123.com'],
            ['Ванос', '+79991234567890', 'test@test.com'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidEmailDataProvider
     */
    public function testCreateUserWithInvalidEmailThrowsException(string $name, string $phone, string $email): void
    {
        // Arrange
        $this->expectException(exception: \InvalidArgumentException::class);

        // Act
        new User(name: $name, phone: $phone, email: $email);

        // Assert
    }

    public function invalidEmailDataProvider(): array
    {
        return [
            ['Иван', '+79991234567', 'test'],
            ['Иван', '+79991234567', 'test@'],
            ['Иван', '+79991234567', 'test@example'],
        ];
    }

    /**
     * @test
     */
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
        $this->assertEquals(expected: $name, actual: $user->getName());
        $this->assertEquals(expected: $phone, actual: $user->getPhone());
        $this->assertEquals(expected: $email, actual: $user->getEmail());
        $this->assertNull(actual: $user->getId());
    }
}