<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\DTO;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

#[OA\Schema(schema: 'Order', title: 'Order', description: 'Details of an order')]
class OrderDTO extends BaseDTO
{
    #[OA\Property(type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000')]
    #[Groups(['order:read'])]
    public string $id;

    #[OA\Property(type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440001')]
    #[Groups(['order:read'])]
    public string $userId;

    #[OA\Property(type: 'string', enum: ['paid', 'in_assembly', 'ready_for_delivery', 'delivered', 'cancelled'])]
    #[Groups(['order:read'])]
    public string $status;

    #[OA\Property(type: 'string', enum: ['courier', 'selfdelivery'])]
    #[Groups(['order:read'])]
    public string $deliveryMethod;

    #[OA\Property(type: 'string', example: '+71234567890')]
    #[Groups(['order:read'])]
    public string $orderPhone;

    #[OA\Property(type: 'string', format: 'date-time', example: '2023-01-01T12:00:00Z')]
    #[Groups(['order:read'])]
    public string $createdAt;

    #[OA\Property(type: 'array', items: new OA\Items(ref: '#/components/schemas/OrderItem'))]
    #[Groups(['order:read'])]
    public array $items;

    public function __construct(
        string $id,
        string $userId,
        string $status,
        string $deliveryMethod,
        string $orderPhone,
        string $createdAt,
        array $items = [],
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->status = $status;
        $this->deliveryMethod = $deliveryMethod;
        $this->orderPhone = $orderPhone;
        $this->createdAt = $createdAt;
        $this->items = $items;
    }
}
