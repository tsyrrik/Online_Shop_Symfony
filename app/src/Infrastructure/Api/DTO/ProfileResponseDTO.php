<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProfileResponse', title: 'ProfileResponse', description: 'Данные профиля пользователя')]
final class ProfileResponseDTO extends BaseDTO
{
    #[OA\Property(type: 'object', properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'name', type: 'string', example: 'Иван Иванов'),
        new OA\Property(property: 'phone', type: 'string', example: '+71234567890'),
        new OA\Property(property: 'email', type: 'string', example: 'ivan@example.com'),
    ])]
    public array $user;

    #[OA\Property(type: 'array', items: new OA\Items(properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440001'),
        new OA\Property(property: 'status', type: 'string', example: 'paid'),
        new OA\Property(property: 'deliveryMethod', type: 'string', example: 'courier'),
        new OA\Property(property: 'createdAt', type: 'string', format: 'date-time', example: '2023-01-01 12:00:00'),
    ]))]
    public array $orders;

    public function __construct(array $user, array $orders)
    {
        $this->user = $user;
        $this->orders = $orders;
    }
}
