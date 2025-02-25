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
    public function CreateUserValidData(string $name, string $phone, string $email): void
    {
        $user = new User($name, $phone, $email);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($phone, $user->getPhone());
        $this->assertEquals($email, $user->getEmail());
        $this->assertNull($user->getId());
    }

    public function validDataProvider(): array
    {
        return [
            ['Иван', '+79990000000', 'test@test.com'],
            ['Толя', '+79990000000', 'test2@test.com'],
            ['Гослин', '+79990000000', 'test3@test.com']
        ];
    }

    /**
     * @test
     * @dataProvider invalidNameDataProvider
     */
    public function ThrowExceptionInvalidName(string $name, string $phone, string $email): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new User($name, $phone, $email);
    }

    public function invalidNameDataProvider(): array
    {
        return [
            ['', '+79990000000', 'test@example.com'], // Пустое имя
            ['Иван123', '+79990000000', 'test@example.com'], // Имя с цифрами
            ['John_Doe', '+79990000000', 'test@example.com'], // Имя с недопустимыми символами
        ];
    }




    /**
     * @test
     * @dataProvider invalidPhoneDataProvider
     */
    public function ThrowExceptionInvalidPhone(string $name, string $phone, string $email): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new User($name, $phone, $email);
    }

    public function invalidPhoneDataProvider(): array
    {
        return [
            ['Ванек', '7999123456', 'test@123.com'],
            ['Ванюша', '+7999123456', 'test@123.com'],
            ['Ванос', 'abcdefg', 'test@test.com']
        ];
    }

    /**
     * @test
     * @dataProvider invalidEmailDataProvider
     */
    public function ThrowExceptionInvalidEmail(string $name, string $phone, string $email): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new User($name, $phone, $email);
    }
    public function invalidEmailDataProvider(): array
    {
        return [
            ['Иван', '+79991234567', 'test'],
            ['Иван', '+79991234567', 'test@'],
            ['Иван', '+79991234567', 'test@example']
        ];
    }

    /**
     * @test
     */
    public function testGettersReturnCorrectValues(): void
    {
        $name = 'Иван';
        $phone = '+79991234567';
        $email = 'test@example.com';

        $user = new User($name, $phone, $email);

        $this->assertEquals($name, $user->getName());
        $this->assertEquals($phone, $user->getPhone());
        $this->assertEquals($email, $user->getEmail());
        $this->assertNull($user->getId());
    }
}